<?php

namespace Hyn\Websocket\Provider;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider as Contract;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Contract::class, function () {
            return new class implements Contract {
                protected $app = [];

                public function __construct()
                {
                    /** @var SettingsRepositoryInterface $settings */
                    $settings = app('flarum.settings');

                    $this->app = new App(
                        $settings->get('hyn-websocket.pusher_app_id'),
                        $settings->get('hyn-websocket.pusher_app_key'),
                        $settings->get('hyn-websocket.pusher_app_secret')
                    );
                }

                /**  @return array[BeyondCode\LaravelWebSockets\AppProviders\App] */
                public function all(): array
                {
                    return [$this->app];
                }

                public function findById($appId): ?App
                {
                    if ($appId === $this->app->id) {
                        return $this->app;
                    }

                    return null;
                }

                public function findByKey(string $appKey): ?App
                {
                    if ($appKey === $this->app->key) {
                        return $this->app;
                    }

                    return null;
                }

                public function findBySecret(string $appSecret): ?App
                {
                    if ($appSecret === $this->app->secret) {
                        return $this->app;
                    }

                    return null;
                }
            };
        });
    }
}
