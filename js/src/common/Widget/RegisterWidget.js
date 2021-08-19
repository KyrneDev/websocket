import Widgets from 'flarum/extensions/afrux-forum-widgets-core/common/extend/Widgets';

import WebsocketOnlineUsersWidget from './WebsocketOnlineUsersWidget';

export default function(app) {
  (new Widgets).add({
    key: 'WebsocketOnlineUsersWidget',
    component: WebsocketOnlineUsersWidget,

    isDisabled: false,

    placement: 'end',
    position: 1,
  }).extend(app, 'kyrne-websocket');
};
