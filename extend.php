<?php

namespace Hyn\Websocket;

use Flarum\Api\Event\Serializing;
use Flarum\Extend\Frontend;
use Flarum\Extension\Event\Enabled;

return [
    new Extend\Provider(Provider\AppProvider::class),
    new Extend\Command(Commands\WebsocketServer::class),

    (new Extend\Listen)
        ->on(Enabled::class, Listener\GenerateApp::class)
        ->on(Serializing::class, Listener\AddPusherApi::class),

    (new Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),
];
