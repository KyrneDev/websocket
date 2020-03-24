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

namespace Kyrne\Websocket\Provider;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider as Contract;
use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;
use BeyondCode\LaravelWebSockets\Server\Router;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManagers\ArrayChannelManager;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;


class AppProvider extends ServiceProvider
{
    public function boot()
    {
        $this->pusher();
        $this->configuration();

        $this->app->resolving(WebsocketsLogger::class, function (WebsocketsLogger $logger) {
            $logger->enable(true);
        });
    }

    public function register()
    {
        $this->app->singleton('websockets.router', function () {
            return new Router();
        });

        $this->app->singleton(ChannelManager::class, function () {
            return config('websockets.channel_manager') !== null && class_exists(config('websockets.channel_manager'))
                ? app(config('websockets.channel_manager')) : new ArrayChannelManager();
        });

        $this->app->bind(Contract::class, function () {
            return new class implements Contract
            {
                protected $apps = [];

                public function __construct()
                {
                    /** @var SettingsRepositoryInterface $settings */
                    $settings = app('flarum.settings');

                    $appId = $settings->get('kyrne-websocket.app_id') ?? 1;
                    $appKey = $settings->get('kyrne-websocket.app_key');
                    $appSecret = $settings->get('kyrne-websocket.app_secret');

                    if (isset($appId, $appSecret, $appKey)) {
                        $app = new App(
                            $settings->get('kyrne-websocket.app_id') ?? 1,
                            $settings->get('kyrne-websocket.app_key'),
                            $settings->get('kyrne-websocket.app_secret')
                        );

                        $this->apps[] = $app
                            ->setName($settings->get('forum_title'))
                            ->setHost($settings->get('kyrne-websocket.app_host'));
                    }
                }

                /**  @return array[BeyondCode\LaravelWebSockets\AppProviders\App] */
                public function all(): array
                {
                    return $this->apps;
                }

                public function first(): ?App
                {
                    return Arr::first($this->all());
                }

                public function findById($appId): ?App
                {
                    if ($appId === optional($this->first())->id) {
                        return $this->first();
                    }

                    return null;
                }

                public function findByKey(string $appKey): ?App
                {
                    if ($appKey === optional($this->first())->key) {
                        return $this->first();
                    }

                    return null;
                }

                public function findBySecret(string $appSecret): ?App
                {
                    if ($appSecret === optional($this->first())->secret) {
                        return $this->first();
                    }

                    return null;
                }
            };
        });
    }

    protected function configuration()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        if (!$config->has('websockets.max_request_size_in_kb')) {
            $config->set('websockets.max_request_size_in_kb', 250);
        }

        if (!$config->has('app.debug')) {
            $config->set('app.debug', app()->config('debug'));
        }

        $settings = app('flarum.settings');

        if (!$config->has('websockets.ssl') && !$settings->get('kyrne-websocket.reverse_proxy')) {
            $config->set('websockets.ssl', [
                'local_cert' => $settings->get('kyrne-websocket.local_cert'),
                'local_pk' => $settings->get('kyrne-websocket.local_pk'),
                'passphrase' => $settings->get('kyrne-websocket.cert_passphrase') ?? '',
                'verify_peer' => false,
                'allow_self_signed' => $settings->get('kyrne-websocket.cert_self_signed')
            ]);
        }
    }

    protected function pusher()
    {
        $this->app->bind(Pusher::class, function ($app) {
            $settings = $app->make(SettingsRepositoryInterface::class);

            $options = [];

            $host = null;
            $port = null;

            $parsedUrl = parse_url(app('flarum.config')['url']);

            $encrypted = false;

            if ($settings->get('kyrne-websocket.force_secure') || $parsedUrl['scheme'] === 'https') {
                $encrypted = true;
            }

            $host = empty($settings->get('kyrne-websocket.app_host')) ? $parsedUrl['host'] : $settings->get('kyrne-websocket.app_host');

            if ($settings->get('kyrne-websocket.reverse_proxy')) {
                $host = '127.0.0.1';
                $encrypted = false;
            }

            if ($cluster = $settings->get('kyrne-websocket.app_cluster')) {
                $options['cluster'] = $cluster;
            } else {
                $options['host'] = $host;
                $options['port'] = empty($settings->get('kyrne-websocket.app_port')) ? 2083 : $settings->get('kyrne-websocket.app_port');
                $options['encrypted'] = $encrypted;
                $options['scheme'] = $encrypted ? 'https' : 'http';
            }

            return new Pusher(
                $settings->get('kyrne-websocket.app_key'),
                $settings->get('kyrne-websocket.app_secret'),
                $settings->get('kyrne-websocket.app_id'),
                $options,
                $host,
                $port
            );
        });

        $this->app->alias(Pusher::class, 'websocket.pusher');
    }
}
