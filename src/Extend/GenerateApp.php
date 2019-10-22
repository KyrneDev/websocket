<?php

namespace Hyn\Websocket\Extend;

use Flarum\Extend\LifecycleInterface;
use Flarum\Extension\Extension;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;

class GenerateApp implements LifecycleInterface
{

    public function onEnable(Container $container, Extension $extension)
    {
        if ($extension->name === 'hyn/flarum-ext-websocket') {
            /** @var SettingsRepositoryInterface $settings */
            $settings = $container->make('flarum.settings');

            $appId = $settings->get('hyn-websocket.app_id');
            $key = $settings->get('hyn-websocket.app_key');
            $secret = $settings->get('hyn-websocket.app_secret');

            if (empty($appId)) {
                $settings->set('hyn-websocket.app_id', rand(1, 1000));
            }
            if (empty($key)) {
                $settings->set('hyn-websocket.app_key', Str::random(32));
            }
            if (empty($secret)) {
                $settings->set('hyn-websocket.app_secret', Str::random(32));
            }
        }
    }

    public function onDisable(Container $container, Extension $extension)
    {
        // TODO: Implement onDisable() method.
    }


    public function extend(Container $container, Extension $extension = null)
    {
        // TODO: Implement extend() method.
    }
}
