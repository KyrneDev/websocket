<?php

namespace Kyrne\Websocket;

use Flarum\Api\Event\Serializing;
use Flarum\Extend\Frontend;
use Flarum\Extend\Routes;
use Flarum\Extend\Locales;
use Flarum\Notification\Event\Sending;
use Flarum\Post\Event\Posted;
use FoF\Components\Extend\AddFofComponents;
use Kyrne\ExtCore\Extend\AddKyrneCore;

return [
    new Extend\Provider(Provider\AppProvider::class),
    new Extend\Command(Commands\WebsocketServer::class),

    new Extend\GenerateApp(),
    new AddFofComponents(),
    new AddKyrneCore(),

    (new Extend\Listen)
        ->on(Serializing::class, Listener\AddPusherApi::class)
        ->on(Posted::class, Listener\PushNewPost::class)
        ->on(Sending::class, Listener\PushNewNotification::class),

    (new Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Locales(__DIR__.'/resources/locale'),

    (new Routes('api'))
        ->post('/websocket/auth', 'websocket.auth', Api\Controller\AuthController::class),
];
