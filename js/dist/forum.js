module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./forum.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./forum.js":
/*!******************!*\
  !*** ./forum.js ***!
  \******************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src_forum__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./src/forum */ "./src/forum/index.js");
/* empty/unused harmony star reexport *//*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/***/ }),

/***/ "./src/forum/index.js":
/*!****************************!*\
  !*** ./src/forum/index.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/extend */ "flarum/extend");
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_extend__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_app__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/app */ "flarum/app");
/* harmony import */ var flarum_app__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_app__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_components_DiscussionList__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/components/DiscussionList */ "flarum/components/DiscussionList");
/* harmony import */ var flarum_components_DiscussionList__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_components_DiscussionList__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/components/DiscussionPage */ "flarum/components/DiscussionPage");
/* harmony import */ var flarum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! flarum/components/IndexPage */ "flarum/components/IndexPage");
/* harmony import */ var flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! flarum/components/Button */ "flarum/components/Button");
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Button__WEBPACK_IMPORTED_MODULE_5__);
/*global Pusher*/






flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.initializers.add('flarum-pusher', function () {
  var loadPusher = m.deferred();
  $.getScript('//js.pusher.com/3.0/pusher.min.js', function () {
    var socket = new Pusher(flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.forum.attribute('pusherKey'), {
      authEndpoint: flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.forum.attribute('apiUrl') + '/pusher/auth',
      cluster: flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.forum.attribute('pusherCluster'),
      wsHost: window.location.hostname,
      wsPort: 6001,
      disableStats: true,
      encrypted: false,
      auth: {
        headers: {
          'X-CSRF-Token': flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.session.csrfToken
        }
      }
    });
    loadPusher.resolve({
      main: socket.subscribe('public'),
      user: flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.session.user ? socket.subscribe('private-user' + flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.session.user.id()) : null
    });
  });
  flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pusher = loadPusher.promise;
  flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates = [];
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_DiscussionList__WEBPACK_IMPORTED_MODULE_2___default.a.prototype, 'config', function (x, isInitialized, context) {
    var _this = this;

    if (isInitialized) return;
    flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pusher.then(function (channels) {
      channels.main.bind('newPost', function (data) {
        var params = _this.props.params;

        if (!params.q && !params.sort && !params.filter) {
          if (params.tags) {
            var tag = flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.store.getBy('tags', 'slug', params.tags);
            if (data.tagIds.indexOf(tag.id()) === -1) return;
          }

          var id = String(data.discussionId);

          if ((!flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.current.discussion || id !== flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.current.discussion.id()) && flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.indexOf(id) === -1) {
            flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.push(id);

            if (flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.current instanceof flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_4___default.a) {
              flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.setTitleCount(flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.length);
            }

            m.redraw();
          }
        }
      });
      Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(context, 'onunload', function () {
        return channels.main.unbind('newPost');
      });
    });
  });
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_DiscussionList__WEBPACK_IMPORTED_MODULE_2___default.a.prototype, 'view', function (vdom) {
    var _this2 = this;

    if (flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates) {
      var count = flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.length;

      if (count) {
        vdom.children.unshift(flarum_components_Button__WEBPACK_IMPORTED_MODULE_5___default.a.component({
          className: 'Button Button--block DiscussionList-update',
          onclick: function onclick() {
            _this2.refresh(false).then(function () {
              _this2.loadingUpdated = false;
              flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates = [];
              flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.setTitleCount(0);
              m.redraw();
            });

            _this2.loadingUpdated = true;
          },
          loading: this.loadingUpdated,
          children: flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.translator.transChoice('flarum-pusher.forum.discussion_list.show_updates_text', count, {
            count: count
          })
        }));
      }
    }
  }); // Prevent any newly-created discussions from triggering the discussion list
  // update button showing.
  // TODO: Might be better pause the response to the push updates while the
  // composer is loading? idk

  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_DiscussionList__WEBPACK_IMPORTED_MODULE_2___default.a.prototype, 'addDiscussion', function (returned, discussion) {
    var index = flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.indexOf(discussion.id());

    if (index !== -1) {
      flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.splice(index, 1);
    }

    if (flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.current instanceof flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_4___default.a) {
      flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.setTitleCount(flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pushedUpdates.length);
    }

    m.redraw();
  });
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_3___default.a.prototype, 'config', function (x, isInitialized, context) {
    var _this3 = this;

    if (isInitialized) return;
    flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pusher.then(function (channels) {
      channels.main.bind('newPost', function (data) {
        var id = String(data.discussionId);

        if (_this3.discussion && _this3.discussion.id() === id && _this3.stream) {
          var oldCount = _this3.discussion.commentCount();

          flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.store.find('discussions', _this3.discussion.id()).then(function () {
            _this3.stream.update();

            if (!document.hasFocus()) {
              flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.setTitleCount(Math.max(0, _this3.discussion.commentCount() - oldCount));
              $(window).one('focus', function () {
                return flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.setTitleCount(0);
              });
            }
          });
        }
      });
      Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(context, 'onunload', function () {
        return channels.main.unbind('newPost');
      });
    });
  });
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_4___default.a.prototype, 'actionItems', function (items) {
    items.remove('refresh');
  });
  flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.pusher.then(function (channels) {
    if (channels.user) {
      channels.user.bind('notification', function () {
        flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.session.user.pushAttributes({
          unreadNotificationCount: flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.session.user.unreadNotificationCount() + 1,
          newNotificationCount: flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.session.user.newNotificationCount() + 1
        });
        delete flarum_app__WEBPACK_IMPORTED_MODULE_1___default.a.cache.notifications;
        m.redraw();
      });
    }
  });
});

/***/ }),

/***/ "flarum/app":
/*!********************************************!*\
  !*** external "flarum.core.compat['app']" ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['app'];

/***/ }),

/***/ "flarum/components/Button":
/*!**********************************************************!*\
  !*** external "flarum.core.compat['components/Button']" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/Button'];

/***/ }),

/***/ "flarum/components/DiscussionList":
/*!******************************************************************!*\
  !*** external "flarum.core.compat['components/DiscussionList']" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/DiscussionList'];

/***/ }),

/***/ "flarum/components/DiscussionPage":
/*!******************************************************************!*\
  !*** external "flarum.core.compat['components/DiscussionPage']" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/DiscussionPage'];

/***/ }),

/***/ "flarum/components/IndexPage":
/*!*************************************************************!*\
  !*** external "flarum.core.compat['components/IndexPage']" ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/IndexPage'];

/***/ }),

/***/ "flarum/extend":
/*!***********************************************!*\
  !*** external "flarum.core.compat['extend']" ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['extend'];

/***/ })

/******/ });
//# sourceMappingURL=forum.js.map