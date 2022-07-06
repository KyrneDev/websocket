import { extend } from 'flarum/extend';
import app from 'flarum/app';
import DiscussionList from 'flarum/components/DiscussionList';
import DiscussionPage from 'flarum/components/DiscussionPage';
import IndexPage from 'flarum/components/IndexPage';

import PresenceChannel from './PresenceChannel';
import RegisterWidget from '../common/Widget/RegisterWidget';

app.initializers.add('kyrne-websocket', () => {
  const loadPusher = new Promise((resolve, reject) => {
    if (app.socketStatus !== 'connected') {
      $.getScript('https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js', () => {
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
          channels: {
            main: socket.subscribe('public'),
            user: app.session.user ? socket.subscribe('private-user' + app.session.user.id()) : null,
            presence: socket.subscribe('presence-forum')
          },
          pusher: socket,
        });
      });
    }
  });

  app.pusher = loadPusher;
  app.pushedUpdates = [];

  extend(DiscussionList.prototype, 'oncreate', function (vnode) {
    app.pusher.then(object => {
      const channels = object.channels;
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) {
          return
        };
        channels[channel].bind('newPost', data => {
          const id = String(data.discussionId);
          const params = app.discussions.getParams();
          if (['user.posts', 'user.discussions'].indexOf(app.current.data.routeName) !== -1) {
            return
          };
          if (!params.q) {
            if ((!app.current.get('discussion') || id !== app.current.get('discussion').id()) && app.pushedUpdates.indexOf(id) === -1) {
              app.request({
                method: 'GET',
                url: app.forum.attribute('apiUrl') + '/discussions/' + id,
              }).then(payload => {
                if (payload.data.attributes.subscription === 'ignore') {
                  return
                }
                if (params.filter && params.filter.tag) {
                  let ids = payload.data.relationships.tags.data.map(function (t) {
                    return t.id;
                  });

                  let tags_ids = [];
                  let slugs = params.filter.tag.split(',');
                  slugs.forEach(function (slug) {
                    let tag = app.store.getBy('tags', 'slug', slug);
                    if (tag) {
                      tags_ids.push(tag.data.id);
                    }
                  });
                  
                  let found = ids.some(r=> tags_ids.includes(r));
                  console.log(found);
                  if (found == false) {
                    return
                  };
                }
                if (app.forum.attribute('websocketAutoUpdate')) {
                  let pages = app.discussions.getPages();
                  for (let p = pages.length; p > 0; p--) {
                    app.discussions.refresh(p);
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

  extend(DiscussionPage.prototype, 'oncreate', function () {
    app.pusher.then(object => {
      const channels = object.channels;
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) {
          return
        };
        channels[channel].bind('newPost', data => {
          const id = String(data.discussionId);
          if (this.discussion && this.discussion.id() === id && this.stream) {
            const oldCount = this.discussion.commentCount();
            app.store.find('discussions', this.discussion.id()).then(() => {
              this.stream.update().then(() => {
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

  //Disable NewPost chanel when going outside from DiscussionList
  extend(DiscussionList.prototype, 'onremove', function (vnode) {
    app.pusher.then(object => {
      const channels = object.channels;
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) return;
        channels[channel].unbind('newPost');
      });
    });
  });

  //Disable NewPost chanel when going outside from DiscussionPage
  extend(DiscussionPage.prototype, 'onremove', function () {
    app.pusher.then(object => {
      const channels = object.channels;
      Object.keys(channels).map((channel) => {
        if (channels[channel] === null) return;
        channels[channel].unbind('newPost');
      });
    });
  })

  //Notifications channel
  app.pusher.then(object => {
    const channels = object.channels;
    if (channels.user) {
      channels.user.bind('notification', () => {
        app.session.user.pushAttributes({
          unreadNotificationCount: app.session.user.unreadNotificationCount() + 1,
          newNotificationCount: app.session.user.newNotificationCount() + 1
        });
        app.notifications.clear();
        m.redraw();

        let element = document.querySelector(".MobileTab .item-notifications .unread");
        if (element) {
            element.innerHTML = app.session.user.unreadNotificationCount();
        }
      });
    }
  });
  PresenceChannel();
  if (app.initializers.has('afrux/forum-widgets-core')) {
    RegisterWidget(app);
  }
});
