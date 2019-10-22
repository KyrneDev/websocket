<?php

namespace Hyn\Websocket\Commands;

use BeyondCode\LaravelWebSockets\Console\StartWebSocketServer;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;
use BeyondCode\LaravelWebSockets\Server\WebSocketServerFactory;
use Flarum\Settings\SettingsRepositoryInterface;
use Symfony\Component\Routing\RouteCollection;

class WebsocketServer extends StartWebsocketServer
{
    protected function registerEchoRoutes()
    {
        return $this;
    }

    protected function supersedeOption(string $option)
    {
        $input = $this->option($option);

        if (isset($input) && ($option === 'host' && $input !== '0.0.0.0')) {
            return $input;
        }

        /** @var SettingsRepositoryInterface $settings */
        $settings = app('flarum.settings');
        $setting = $settings->get("hyn-websocket.app_{$option}");

        return !empty($setting) ? $setting : $input;
    }

    public function handle()
    {
        $this->input->setOption(
            'port',
            $this->supersedeOption('port')
        );

        $this->input->setOption(
            'host',
            $this->supersedeOption('host')
        );

        $this
//            ->configureStatisticsLogger()
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

        $routes = new RouteCollection();

        /* 🛰 Start the server 🛰  */
        (new WebSocketServerFactory())
            ->setLoop($this->loop)
            ->useRoutes($routes)
            ->setHost($this->option('host'))
            ->setPort($this->option('port'))
            ->setConsoleOutput($this->output)
            ->createServer()
            ->run();
    }
}
