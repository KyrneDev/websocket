<?php

namespace Hyn\Websocket\Commands;

use BeyondCode\LaravelWebSockets\Console\StartWebSocketServer;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;
use BeyondCode\LaravelWebSockets\Server\WebSocketServerFactory;
use Symfony\Component\Routing\RouteCollection;

class WebsocketServer extends StartWebsocketServer
{
    protected function registerEchoRoutes()
    {
        return $this;
    }



    public function handle()
    {
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
        app(WebsocketsLogger::class)->enable(true);
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
