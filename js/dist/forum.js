module.exports=function(t){var e={};function n(o){if(e[o])return e[o].exports;var s=e[o]={i:o,l:!1,exports:{}};return t[o].call(s.exports,s,s.exports,n),s.l=!0,s.exports}return n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var s in t)n.d(o,s,function(e){return t[e]}.bind(null,s));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=9)}([function(t,e){t.exports=flarum.core.compat.app},function(t,e){t.exports=flarum.core.compat.extend},,function(t,e){t.exports=flarum.core.compat["components/DiscussionList"]},function(t,e){t.exports=flarum.core.compat["components/IndexPage"]},function(t,e){t.exports=flarum.core.compat["components/DiscussionPage"]},function(t,e){t.exports=flarum.core.compat["components/Button"]},,,function(t,e,n){"use strict";n.r(e);var o=n(1),s=n(0),r=n.n(s),u=n(3),a=n.n(u),i=n(5),c=n.n(i),d=n(4),f=n.n(d),p=n(6),l=n.n(p);r.a.initializers.add("kyrne-websocket",function(){var t=new Promise(function(t,e){"connected"!==r.a.socketStatus&&$.getScript("//cdnjs.cloudflare.com/ajax/libs/pusher/5.1.1/pusher.min.js",function(){if(!r.a.session.user&&r.a.forum.attribute("websocketAuthOnly"))return!1;r.a.forum.attribute("debug")&&(Pusher.logToConsole=!0);var e=r.a.forum.attribute("websocketReverseProxy")?443:r.a.forum.attribute("websocketPort")||2083,n=new Pusher(r.a.forum.attribute("websocketKey"),{authEndpoint:r.a.forum.attribute("apiUrl")+"/websocket/auth",cluster:null,wsHost:r.a.forum.attribute("websocketHost")||window.location.hostname,wsPort:r.a.forum.attribute("websocketPort")||2083,wssPort:e,enableStats:!1,encrypted:r.a.forum.attribute("websocketSecure"),auth:{headers:{"X-CSRF-Token":r.a.session.csrfToken}},disabledTransports:["xhr_polling","xhr_streaming","sockjs"]});return n.connection.bind("state_change",function(t){return r.a.socketStatus=t.current}),t({main:n.subscribe("public"),user:r.a.session.user?n.subscribe("private-user"+r.a.session.user.id()):null})})});r.a.pusher=t,r.a.pushedUpdates=[],Object(o.extend)(a.a.prototype,"oncreate",function(t){var e=this;r.a.pusher.then(function(t){Object.keys(t).map(function(n){null!==t[n]&&t[n].bind("newPost",function(t){var n=r.a.discussions.getParams();if(!n.q&&!n.sort&&!n.filter){if(n.tags){var o=r.a.store.getBy("tags","slug",n.tags);if(-1===t.tagIds.indexOf(o.id()))return}var s=String(t.discussionId);r.a.current.get("discussion")&&s===r.a.current.get("discussion").id()||-1!==r.a.pushedUpdates.indexOf(s)||(r.a.forum.attribute("websocketAutoUpdate")?r.a.store.find("discussions",s).then(function(t){e.attrs.state.removeDiscussion(t),e.attrs.state.addDiscussion(t),document.hasFocus()||(r.a.setTitleCount(r.a.titleCount+1),$(window).one("focus",function(){return r.a.setTitleCount(0)}))}):(r.a.pushedUpdates.push(s),r.a.current.matches(f.a)&&r.a.setTitleCount(r.a.pushedUpdates.length),m.redraw()))}})})})}),Object(o.extend)(a.a.prototype,"onremove",function(t){r.a.pusher.then(function(t){Object.keys(t).map(function(e){null!==t[e]&&t[e].unbind("newPost")})})}),Object(o.extend)(a.a.prototype,"view",function(t){var e=this;if(r.a.pushedUpdates){var n=r.a.pushedUpdates.length;n&&t.children.unshift(l.a.component({className:"Button Button--block DiscussionList-update",onclick:function(){e.attrs.state.refresh(!1).then(function(){e.loadingUpdated=!1,r.a.pushedUpdates=[],r.a.setTitleCount(0),m.redraw()}),e.loadingUpdated=!0},loading:this.loadingUpdated},r.a.translator.transChoice("kyrne-websocket.forum.discussion_list.show_updates_text",n,{count:n})))}}),Object(o.extend)(a.a.prototype,"addDiscussion",function(t,e){var n=r.a.pushedUpdates.indexOf(e.id());-1!==n&&r.a.pushedUpdates.splice(n,1),r.a.current.matches(f.a)&&r.a.setTitleCount(r.a.pushedUpdates.length),m.redraw()}),Object(o.extend)(c.a.prototype,"oncreate",function(){var t=this;r.a.pusher.then(function(e){Object.keys(e).map(function(n){null!==e[n]&&e[n].bind("newPost",function(e){var n=String(e.discussionId);if(t.discussion&&t.discussion.id()===n&&t.stream){var o=t.discussion.commentCount();r.a.store.find("discussions",t.discussion.id()).then(function(){t.stream.update().then(function(){document.hasFocus()||(r.a.setTitleCount(Math.max(0,t.discussion.commentCount()-o)),$(window).one("focus",function(){return r.a.setTitleCount(0)})),m.redraw()})})}})})})}),Object(o.extend)(c.a.prototype,"onremove",function(){r.a.pusher.then(function(t){Object.keys(t).map(function(e){null!==t[e]&&t[e].unbind("newPost")})})}),Object(o.extend)(f.a.prototype,"actionItems",function(t){t.remove("refresh")}),r.a.pusher.then(function(t){t.user&&t.user.bind("notification",function(){r.a.session.user.pushAttributes({unreadNotificationCount:r.a.session.user.unreadNotificationCount()+1,newNotificationCount:r.a.session.user.newNotificationCount()+1}),r.a.notifications.clear(),m.redraw()})})})}]);
//# sourceMappingURL=forum.js.map