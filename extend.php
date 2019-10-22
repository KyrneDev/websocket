<?php

namespace Hyn\Websocket;

use Flarum\Api\Event\Serializing;
use Flarum\Extend\Frontend;
use Flarum\Extend\Routes;

return [
    new Extend\Provider(Provider\AppProvider::class),
    new Extend\Command(Commands\WebsocketServer::class),

    new Extend\GenerateApp(),
    (new Extend\Listen)
        ->on(Serializing::class, Listener\AddPusherApi::class),

    (new Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Routes('api'))
        ->post('/websocket/auth', 'websocket.auth', Api\Controller\AuthController::class),
];
