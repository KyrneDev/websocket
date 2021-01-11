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

namespace Kyrne\Websocket\Commands;

use BeyondCode\LaravelWebSockets\Console\Commands\StartServer;
use BeyondCode\LaravelWebSockets\API\FetchChannel;
use BeyondCode\LaravelWebSockets\API\FetchChannels;
use BeyondCode\LaravelWebSockets\API\FetchUsers;
use BeyondCode\LaravelWebSockets\ServerFactory;
use Illuminate\Support\Str;
use BeyondCode\LaravelWebSockets\Contracts\ChannelManager;
use Flarum\Settings\SettingsRepositoryInterface;
use Kyrne\Websocket\Channels\Managers\LocalChannelManager;
use Kyrne\Websocket\WebSockets\SocketHandler;
use Kyrne\Websocket\WebSockets\TriggerBroadcastController;

class WebsocketServer extends StartServer
{

    protected function registerEchoRoutes()
    {
        $router = app('websockets.router');

        $router->get('/app/{appKey}', SocketHandler::class);
        $router->post('/apps/{appId}/events', TriggerBroadcastController::class);
        $router->get('/apps/{appId}/channels', FetchChannels::class);
        $router->get('/apps/{appId}/channels/{channelName}', FetchChannel::class);
        $router->get('/apps/{appId}/channels/{channelName}/users', FetchUsers::class);

        return $this;
    }

    protected function supersedeOption(string $option)
    {
        $input = $this->option($option);

        /** @var SettingsRepositoryInterface $settings */
        $settings = app('flarum.settings');

        if (isset($input) && ($option === 'host' && $input !== '0.0.0.0')) {
            return $input;
        } elseif ($option === 'host' && parse_url(app('flarum.config')['url'])['host'] === $settings->get("kyrne-websocket.app_host")) {
            return '0.0.0.0';
        }

        $setting = $settings->get("kyrne-websocket.app_{$option}");

        return !empty($setting) ? $setting : $input;
    }

    public function handle()
    {
        $this->input->setOption(
            'port',
            $port = $this->supersedeOption('port')
        );

        $this->input->setOption(
            'host',
            $host = $this->supersedeOption('host')
        );

        $setting = app('flarum.settings');
        $pk = $setting->get('kyrne-websocket.local_pk');
        $cert = $setting->get('kyrne-websocket.local_cert');

        if ((!is_readable($cert) || !is_readable($pk)) && $pk && $cert) {
            $this->error('Cannot access local certificate/passkey!');
            return;
        }

        $this->info("Selecting $host:$port.");


        $this->configureLoggers();

        $this->configureManagers();

        $this->registerEchoRoutes();

        $this->configurePongTracker();

        $this->startServer();
    }

    public function registerCustomRoutes()
    {
        return $this;
    }

    protected function configureManagers()
    {
        app()->singleton(ChannelManager::class, function () {
            return new LocalChannelManager($this->loop);
        });
    }

    /**
     * Build the server instance.
     *
     * @return void
     */
    protected function buildServer()
    {
        $this->server = new ServerFactory(
            $this->option('host'), $this->option('port')
        );

        if ($loop = $this->option('loop')) {
            $this->loop = $loop;
        }

        $this->server = $this->server
            ->setLoop($this->loop)
            ->withRoutes(app('websockets.router')->getRoutes())
            ->setConsoleOutput($this->output)
            ->createServer();
    }
}
