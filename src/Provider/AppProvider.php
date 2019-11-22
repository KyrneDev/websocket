<?php

namespace Hyn\Websocket\Provider;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider as Contract;
use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->resolving(WebsocketsLogger::class, function (WebsocketsLogger $logger) {
            $logger->enable(true);
        });
    }
    public function register()
    {
        $this->app->bind(Contract::class, function () {
            return new class implements Contract {
                protected $apps = [];

                public function __construct()
                {
                    /** @var SettingsRepositoryInterface $settings */
                    $settings = app('flarum.settings');

                    $appId = $settings->get('hyn-websocket.app_id') ?? 1;
                    $appKey = $settings->get('hyn-websocket.app_key');
                    $appSecret = $settings->get('hyn-websocket.app_secret');

                    if (isset($appId, $appSecret, $appKey)) {
                        $app = new App(
                            $settings->get('hyn-websocket.app_id') ?? 1,
                            $settings->get('hyn-websocket.app_key'),
                            $settings->get('hyn-websocket.app_secret')
                        );

                        $this->apps[] = $app
                            ->setName($settings->get('forum_title'))
                            ->setHost($settings->get('hyn-websocket.app_host'));
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
}
