import {extend} from 'flarum/extend';
import DiscussionPage from 'flarum/components/DiscussionPage';
import ReplyComposer from 'flarum/components/ReplyComposer';
import ReplyPlaceholder from 'flarum/components/ReplyPlaceholder';
import avatar from 'flarum/helpers/avatar';
import username from 'flarum/helpers/username';
import Stream from 'flarum/utils/Stream';
import stringToColor from 'flarum/utils/stringToColor';

export default function () {
  extend(DiscussionPage.prototype, 'view', function (vnode) {
    app.pusher.then(object => {
      if (!app.discussions.presence && app.session.user && this.discussion) {
        app.discussions.presence = object.pusher.subscribe('presence-' + this.discussion.id());

        app.discussions.presence.bind("pusher:subscription_succeeded", (members) => {
          this.membersOnline = [];
          Object.keys(members.members).map(member => {
            if (app.session.user.id() != member) {
              this.membersOnline.push({
                id: Stream(member),
                color: Stream(stringToColor(members.members[member].username)),
                displayName: Stream(members.members[member].username),
                avatarUrl: Stream(members.members[member].avatarUrl)
              });
              m.redraw();
            }
          })
        });

        app.discussions.presence.bind("pusher:member_removed", (member) => {
          this.membersOnline.some((user, i) => {
            if (user.id() == member.id) {
              this.membersOnline.splice(i, 1);
              m.redraw();
              return true;
            }
          });
        });

        app.discussions.presence.bind("pusher:member_added", (member) => {
          if (app.session.user.id() != member.id) {
            this.membersOnline.push({
              id: Stream(member.id),
              color: Stream(stringToColor(member.info.username)),
              displayName: Stream(member.info.username),
              avatarUrl: Stream(member.info.avatarUrl)
            });
            m.redraw();
          }
        });
      }
    });
  });

  extend(DiscussionPage.prototype, 'sidebarItems', function (items) {
    if (this.membersOnline && this.membersOnline.length) {
      items.add('viewing',
        <div className="UsersOnline">
          <legend
            className="UsersOnline-title">{app.translator.trans('kyrne-websocket.forum.discussion_page.viewing_title')}</legend>
          <ul className="UsersOnline-list">
            {this.membersOnline.map(user => {
              return <li className="UsersOnline-item">
                {avatar(user)}
                {username(user)}
              </li>
            })}
          </ul>
        </div>
        , -101)
    }
  });

  extend(DiscussionPage.prototype, 'oncreate', function (vnode) {
    if (!window.matchMedia("only screen and (max-width: 767px)").matches) {
      $(window).on('scroll', function (e) {
        const nav = $('.DiscussionPage-nav').children();
        if ($(window).scrollTop() > 147) {
          nav.css('position', 'fixed');
          nav.addClass('websocket-nav');
        } else {
          nav.css('position', 'absolute');
          nav.removeClass('websocket-nav');
        }
      })
    }
  });

  extend(DiscussionPage.prototype, 'onremove', function (vnode) {
    app.pusher.then(object => {
      if (app.discussions.presence) {
        app.discussions.presence = object.pusher.unsubscribe('presence-' + this.discussion.id());
        app.discussions.presence = null;
      }
    });
  });

  extend(ReplyComposer.prototype, 'oninit', function () {
    const interval1 = () => {
      this.typingTimeout = true;
      setTimeout(() => {
        interval1();
      }, 18000);
    };

    interval1();
  });

  extend(ReplyComposer.prototype, 'view', function () {
    $('.TextEditor-editor').on('keydown', () => {
      if (this.typingTimeout) {
        this.typingTimeout = false;
        app.request({
          method: 'POST',
          url: app.forum.attribute('apiUrl') + '/posts/typing',
          body: {
            discussionId: this.attrs.discussion.id()
          }
        })
      }
    })
  });

  extend(ReplyPlaceholder.prototype, 'oninit', function () {
    this.typers = {};

    setTimeout(() => {
      if (app.discussions.presence) {
        app.discussions.presence.bind('typing', (data) => {
          if (!this.typers[data.userId] && data.userId != app.session.user.id()) {
            this.typers[data.userId] = {
              id: Stream(data.userId),
              color: Stream(stringToColor(data.username)),
              displayName: Stream(data.username),
              avatarUrl: Stream(data.avatarUrl),
              time: new Date()
            };
          }

          m.redraw();
        })
      }
    }, 2000);

    const interval2 = () => {
      Object.keys(this.typers).map(typer => {
        if (this.typers[typer].time < new Date(Date.now() - 20000)) {
          delete this.typers[typer];
          m.redraw();
        }
      })
      setTimeout(() => {
        interval2();
      }, 20000);
    };

    interval2();
  });

  extend(ReplyPlaceholder.prototype, 'view', function (vnode) {
    if (Object.keys(this.typers).length) {
      vnode.children.push(
        <div className="ReplyPlaceholder-typers">
          <ul className="ReplyPlaceholder-typers-list">
            {Object.keys(this.typers).map(typer => {
              return <li className="ReplyPlaceholder-typers-item">
                {avatar(this.typers[typer])}
                {username(this.typers[typer])}
                <div className="tiblock">
                  <div className="tidot"/>
                  <div className="tidot"/>
                  <div className="tidot"/>
                </div>
              </li>
            })}
          </ul>
        </div>
      )
    }
  });
}
