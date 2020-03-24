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

use BeyondCode\LaravelWebSockets\Console\StartWebSocketServer;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\FetchChannelController;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\FetchChannelsController;
use BeyondCode\LaravelWebSockets\HttpApi\Controllers\FetchUsersController;
use BeyondCode\LaravelWebSockets\Server\WebSocketServerFactory;
use Flarum\Settings\SettingsRepositoryInterface;
use Kyrne\Websocket\WebSockets\SocketHandler;
use Kyrne\Websocket\WebSockets\TriggerBroadcastController;

class WebsocketServer extends StartWebsocketServer
{

    protected function registerEchoRoutes()
    {
        $router = app('websockets.router');

        $router->get('/app/{appKey}', SocketHandler::class);
        $router->post('/apps/{appId}/events', TriggerBroadcastController::class);
        $router->get('/apps/{appId}/channels', FetchChannelsController::class);
        $router->get('/apps/{appId}/channels/{channelName}', FetchChannelController::class);
        $router->get('/apps/{appId}/channels/{channelName}/users', FetchUsersController::class);

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

        $this->info("Selecting $host:$port.");

        $this
            ->configureHttpLogger()
            ->configureMessageLogger()
            ->configureConnectionLogger()
            ->registerEchoRoutes()
            ->registerCustomRoutes()
            ->startWebSocketServer();
    }

    public function registerCustomRoutes()
    {
        return $this;
    }

    protected function startWebSocketServer()
    {
        $this->info("Starting the WebSocket server on port {$this->option('port')}...");

        /* 🛰 Start the server 🛰  */
        (new WebSocketServerFactory())
            ->setLoop($this->loop)
            ->useRoutes(app('websockets.router')->getRoutes())
            ->setHost($this->option('host'))
            ->setPort($this->option('port'))
            ->setConsoleOutput($this->output)
            ->createServer()
            ->run();
    }
}
