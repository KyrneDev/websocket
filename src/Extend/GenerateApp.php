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

namespace Kyrne\Websocket\Extend;

use Flarum\Extend\LifecycleInterface;
use Flarum\Extension\Extension;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;

class GenerateApp implements LifecycleInterface
{

    public function onEnable(Container $container, Extension $extension)
    {
        if ($extension->name === 'kyrne/websocket') {
            /** @var SettingsRepositoryInterface $settings */
            $settings = $container->make('flarum.settings');

            $appId = $settings->get('kyrne-websocket.app_id');
            $key = $settings->get('kyrne-websocket.app_key');
            $secret = $settings->get('kyrne-websocket.app_secret');
            $host = $settings->get('kyrne-websocket.app_host');
            $port = $settings->get('kyrne-websocket.app_port');
            $secure = $settings->get('kyrne-websocket.disable_secure');

            if (empty($appId)) {
                $settings->set('kyrne-websocket.app_id', rand(1, 1000));
            }
            if (empty($key)) {
                $settings->set('kyrne-websocket.app_key', Str::random(32));
            }
            if (empty($secret)) {
                $settings->set('kyrne-websocket.app_secret', Str::random(32));
            }
            if (empty($host)) {
                $settings->set('kyrne-websocket.app_host', parse_url(app('flarum.config')['url'])['host']);
            }
            if (empty($port)) {
                $settings->set('kyrne-websocket.app_port', 2083);
            }
            if (empty($secure)) {
                $settings->set('kyrne-websocket.force_secure', 0);
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
