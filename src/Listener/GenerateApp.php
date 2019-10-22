<?php

namespace Hyn\Websocket\Listener;

use Flarum\Extension\Event\Enabled;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Str;

class GenerateApp
{
    public function __invoke(Enabled $event)
    {
        if ($event->extension->name !== 'hyn/flarum-websocket') {
            return;
        }

        if ($event instanceof Enabled) {
            /** @var SettingsRepositoryInterface $settings */
            $settings = app('flarum.settings');

            $appId = $settings->get('hyn-websocket.pusher_app_id');
            $key = $settings->get('hyn-websocket.pusher_key');
            $secret = $settings->get('hyn-websocket.pusher_secret');

            if (empty($appId)) {
                $settings->set('hyn-websocket.pusher_app_id', rand(1, 1000));
            }
            if (empty($key)) {
                $settings->set('hyn-websocket.pusher_key', Str::random(32));
            }
            if (empty($secret)) {
                $settings->set('hyn-websocket.pusher_secret', Str::random(32));
            }
        }
    }
}
