import {extend} from 'flarum/extend';
import app from 'flarum/app';
import DiscussionListState from 'flarum/states/DiscussionListState';
import DiscussionList from 'flarum/components/DiscussionList';
import DiscussionPage from 'flarum/components/DiscussionPage';
import IndexPage from 'flarum/components/IndexPage';
import Button from 'flarum/components/Button';

app.initializers.add('kyrne-websocket', () => {

  const loadPusher = new Promise((resolve, reject) => {
    if (app.socketStatus !== 'connected') {
      $.getScript('//cdnjs.cloudflare.com/ajax/libs/pusher/5.1.1/pusher.min.js', () => {
        if (!app.session.user && app.forum.attribute('websocketAuthOnly')) {
          return false;
        }
        if (app.forum.attribute('debug')) {
          Pusher.logToConsole = true;
        }
        let wssPort = app.forum.attribute('websocketReverseProxy') === '1' ? 443 : app.forum.attribute('websocketPort') || 2083;
        const socket = new Pusher(app.forum.attribute('websocketKey'), {
          authEndpoint: app.forum.attribute('apiUrl') + '/websocket/auth',
          cluster: null,
          wsHost: app.forum.attribute('websocketHost') || window.location.hostname,
          wsPort: app.forum.attribute('websocketPort') || 2083,
          wssPort,
          enableStats: false,
          encrypted: app.forum.attribute('websocketSecure'),
          auth: {
            headers: {
              'X-CSRF-Token': app.session.csrfToken
            }
          },
          enabledTransports: ["ws", "flash"],
          disabledTransports: ['xhr_polling', 'xhr_streaming', 'sockjs']
        });

        socket.connection.bind('state_change', (state) => app.socketStatus = state.current);

        return resolve({
          main: socket.subscribe('public'),
          user: app.session.user ? socket.subscribe('private-user' + app.session.user.id()) : null
        });
      });
    }
  });

  app.pusher = loadPusher;
  app.pushedUpdates = [];

  extend(DiscussionList.prototype, 'oncreate', function (vnode) {
    app.pusher.then(channels => {
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) return;
        channels[channel].bind('newPost', data => {
          const params = app.discussions.getParams();

          if (!params.q && !params.sort && !params.filter) {
            if (params.tags) {
              const tag = app.store.getBy('tags', 'slug', params.tags);

              if (data.tagIds.indexOf(Number(tag.id())) === -1) return;
            }

            const id = String(data.discussionId);

            if ((!app.current.get('discussion') || id !== app.current.get('discussion').id()) && app.pushedUpdates.indexOf(id) === -1) {
              app
                .request({
                    method: 'GET',
                    url: app.forum.attribute('apiUrl') + '/discussions/' + id + '?include=user',
                  })
                .then(payload => {
                  if (app.forum.attribute('websocketAutoUpdate')) {
                    const discussion = app.store.pushPayload(payload);
                    this.attrs.state.removeDiscussion(discussion);
                    this.attrs.state.addDiscussion(discussion);

                    if (!document.hasFocus()) {
                      app.setTitleCount(app.titleCount + 1);

                      $(window).one('focus', () => app.setTitleCount(0));
                    }
                  } else {
                    app.pushedUpdates.push(payload);

                    if (app.current.matches(IndexPage)) {
                      app.setTitleCount(app.pushedUpdates.length);
                    }

                    m.redraw();
                  }
                });
            }
          }
        });
      });
    });
  });

  extend(DiscussionList.prototype, 'onremove', function (vnode) {
    app.pusher.then(channels => {
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) return;
        channels[channel].unbind('newPost');
      });
    });
  });

  extend(DiscussionList.prototype, 'view', function (vdom) {
    if (app.pushedUpdates) {
      const count = app.pushedUpdates.length;

      let foundUser = false;

      app.pushedUpdates.map(payload => {
        if (app.current.data.user && payload.included[0].id == app.current.data.user.id()) {
          foundUser = true;
        }
      })

      const addButton = () => {
        vdom.children.unshift(
          Button.component({
            className: 'Button Button--block DiscussionList-update',
            onclick: async () => {
              this.loadingUpdated = true;
              await app.pushedUpdates.map((payload) => {
                const discussion = app.store.pushPayload(payload);
                this.attrs.state.removeDiscussion(discussion);
                this.attrs.state.addDiscussion(discussion);
              })
              app.pushedUpdates = [];
              app.setTitleCount(0);
              m.redraw();
              this.loadingUpdated = false;
            },
            loading: this.loadingUpdated,
          }, app.translator.trans('kyrne-websocket.forum.discussion_list.show_updates_text', {count}))
        );
      }

      if (app.current.data.user) {
        if (foundUser) {
          addButton();
        }
      } else if (count) {
        addButton();
      }

    }
  });

  extend(DiscussionListState.prototype, 'parseResults', function() {
    app.pushedUpdates = [];

    if (app.current.matches(IndexPage)) {
      app.setTitleCount(0);
    }

    m.redraw();
  });

  extend(DiscussionPage.prototype, 'oncreate', function () {
    app.pusher.then(channels => {
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) return;
        channels[channel].bind('newPost', data => {
          const id = String(data.discussionId);

          if (this.discussion && this.discussion.id() === id && this.stream) {
            const oldCount = this.discussion.commentCount();

            app.store.find('discussions', this.discussion.id()).then(() => {
              this.stream.update()
                .then(() => {
                  if (!document.hasFocus()) {
                    app.setTitleCount(Math.max(0, this.discussion.commentCount() - oldCount));

                    $(window).one('focus', () => app.setTitleCount(0));
                  }

                  m.redraw();
                })
            });
          }
        });
      });
    });
  });

  extend(DiscussionPage.prototype, 'onremove', function () {
    app.pusher.then(channels => {
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) return;
        channels[channel].unbind('newPost');
      });
    });
  })

  extend(IndexPage.prototype, 'actionItems', items => {
    items.remove('refresh');
  });

  app.pusher.then(channels => {
    if (channels.user) {
      channels.user.bind('notification', () => {
        app.session.user.pushAttributes({
          unreadNotificationCount: app.session.user.unreadNotificationCount() + 1,
          newNotificationCount: app.session.user.newNotificationCount() + 1
        });
        app.notifications.clear();
        m.redraw();
      });
    }
  });
});
