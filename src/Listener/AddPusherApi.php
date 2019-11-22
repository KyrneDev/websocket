<?php

namespace Hyn\Websocket\Listener;

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

            $event->attributes['websocketKey'] = $app->key;
            $event->attributes['websocketHost'] = $app->host;
            $event->attributes['websocketPort'] = $app->port ?? $this->settings->get('hyn-websocket.app_port') ?? 6001;
        }
    }
}
