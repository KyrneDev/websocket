<?php
/**
 *
 *  This file is part of kyrne/websocket
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 *
 */

namespace Kyrne\Websocket\Listener;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider;
use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Settings\SettingsRepositoryInterface;

class AddPusherApi
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(Serializing $event)
    {
        if ($event->isSerializer(ForumSerializer::class)) {
            /** @var AppProvider $provider */
            $provider = app(AppProvider::class);
            /** @var App $app */
            $app = optional($provider->first());
            $settings = app('flarum.settings');

            $event->attributes['websocketSecure'] = (bool) $settings->get('kyrne-websocket.force_secure');
            $event->attributes['websocketReverseProxy'] = (bool) $settings->get('kyrne-websocket.reverse_proxy');
            $event->attributes['websocketKey'] = $app->key;
            $event->attributes['websocketHost'] = $app->host;
            $event->attributes['websocketPort'] = $app->port ?? $this->settings->get('kyrne-websocket.app_port') ?? 6001;
        }
    }
}
