<?php

namespace Kyrne\Websocket;

use Flarum\Api\Event\Serializing;
use Flarum\Extend\Frontend;
use Flarum\Extend\Routes;
use Flarum\Extend\Compat;
use Flarum\Extend\Console;
use Flarum\Extend\Locales;

use Flarum\Extend\Settings;
use Flarum\Foundation\Application;
use Flarum\Notification\Event\Sending;
use Flarum\Post\Event\Posted;
use Kyrne\Websocket\Provider\AppProvider;

return [
    (new Console)
        ->command(Commands\WebsocketServer::class)
        ->command(Commands\AltServer::class),

    new Extend\GenerateApp(),

    (new Settings())
        ->serializeToForum('websocketSecure', 'kyrne-websocket.force_secure', function($setting) {return boolval($setting);})
        ->serializeToForum('websocketReverseProxy', 'kyrne-websocket.reverse_proxy')
        ->serializeToForum('websocketPort', 'kyrne-websocket.app_port')
        ->serializeToForum('websocketAutoUpdate', 'kyrne-websocket.auto_update')
        ->serializeToForum('websocketKey', 'kyrne-websocket.app_key')
        ->serializeToForum('websocketHost', 'kyrne-websocket.app_host')
        ->serializeToForum('websocketAuthOnly', 'kyrne-websocket.auth_only'),

    (new Extend\Listen)
        ->on(Posted::class, Listener\PushNewPost::class)
        ->on(Sending::class, Listener\PushNewNotification::class),

    (new Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),

    new Locales(__DIR__.'/resources/locale'),

    (new Routes('api'))
        ->post('/websocket/auth', 'websocket.auth', Api\Controller\AuthController::class),
    new Compat(function(Application $app) {
        $app->register(AppProvider::class);
    }),
];
