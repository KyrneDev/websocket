import LoadingIndicator from 'flarum/components/LoadingIndicator';
import Tooltip from 'flarum/components/Tooltip';
import avatar from 'flarum/helpers/avatar';
import Link from 'flarum/components/Link';
import stringToColor from 'flarum/utils/stringToColor';
import Stream from 'flarum/utils/Stream';

import Widget from 'flarum/extensions/afrux-forum-widgets-core/common/components/Widget';

let WebsocketWidget = null;

if (Widget) {
  WebsocketWidget = class WebsocketOnlineUsersWidget extends Widget {
    oninit(vnode) {
      super.oninit(vnode);

      this.users = [];
      this.loading = true;
      this.guests = 0;
    }

    oncreate(vnode) {
      super.oncreate(vnode);

      app.pusher.then(object => {
        const presence = object.channels.presence;

        const existingMembers = Object.keys(presence.members.members);

        const removeGuest = !app.session.user || (app.session.user && !app.session.user.preferences().discloseOnline);

        if (existingMembers.length === 0) {

          presence.bind("pusher:subscription_succeeded", members => {
            Object.keys(members.members).map(member => {
              if (!member.includes('Guest')) {
                members.members[member].id = member;
                this.pushMember(members.members[member]);
              } else {
                this.guests++;
              }
            })
            this.loading = false;
            m.redraw();
          });
          if (removeGuest) {
            this.guests--;
            m.redraw();
          }
        } else {
          existingMembers.map(member => {
            if (!member.includes('Guest')) {
              presence.members.members[member].id = member;
              this.pushMember(presence.members.members[member]);
            } else {
              this.guests++;
            }
          })
          this.loading = false;

          if (removeGuest) {
            this.guests--;
            m.redraw();
          }
        }

        presence.bind("pusher:member_removed", (member) => {
          if (typeof member.id !== 'string') {
            this.users.some((user, i) => {
              if (user.id() == member.id) {
                this.users.splice(i, 1);
                return true;
              }
            });
          } else {
            this.guests--;
          }
          m.redraw();
        });

        presence.bind("pusher:member_added", (member) => {
          if (typeof member.id !== 'string') {
            member.info.id = member.id;
            this.pushMember(member.info);
          } else {
            this.guests++;
          }
          m.redraw();
        });
      });
    }

    pushMember(member) {
      this.users.push({
        id: Stream(member.id),
        color: Stream('#' + stringToColor(member.displayName)),
        displayName: Stream(member.displayName),
        avatarUrl: Stream(member.avatarUrl),
        slug: Stream(member.slug)
      });
    }

    className() {
      return 'WebsocketOnlineUsersWidget';
    }

    icon() {
      return 'fas fa-user-friends';
    }

    title() {
      return app.translator.trans('kyrne-websocket.forum.widget.title');
    }

    content() {
      if (this.loading) {
        return <LoadingIndicator/>;
      }

      const max = 12;
      const users = this.users;

      return (
        <div className="WebsocketOnlineUsersWidget-users">
          <div className="WebsocketOnlineUsersWidget-users-message">
            {users.length === 0 ? app.translator.trans('kyrne-websocket.forum.widget.empty') : null}
          </div>
          <div className="WebsocketOnlineUsersWidget-users-list">
            {users.slice(0, max).map((user) => (
              <Link href={app.route('user', {username: user.slug()})} className="WebsocketOnlineUsersWidget-users-item">
                <Tooltip text={user.displayName()}>{avatar(user)}</Tooltip>
              </Link>
            ))}
            {this.guests > 0 ?
              <span style={users.length > 0 ? 'margin-left: 8px' : ''} className="WebsocketOnlineUsersWidget-users-guests">{app.translator.trans('kyrne-websocket.forum.widget.guests', {count: this.guests})}</span>
            : ''}
            {users.length > max ? (
              <span className="WebsocketOnlineUsersWidget-users-item WebsocketOnlineUsersWidget-users-item--plus">
              <span className="Avatar">{`+${max}`}</span>
            </span>
            ) : null}
          </div>
        </div>
      );
    }
  }
} else {
  WebsocketWidget = class WebsocketOnlineUsersWidget {};
}

export default WebsocketWidget;
