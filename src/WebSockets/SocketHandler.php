<?php
/**
 *  This file is part of kyrne/websocket.
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 */

namespace Kyrne\Websocket\WebSockets;

use BeyondCode\LaravelWebSockets\Contracts\ChannelManager;
use BeyondCode\LaravelWebSockets\Contracts\PusherMessage;
use BeyondCode\LaravelWebSockets\Server\Messages\PusherClientMessage;
use BeyondCode\LaravelWebSockets\Server\WebSocketHandler;
use BeyondCode\LaravelWebSockets\Statistics\Collectors\MemoryCollector;
use Illuminate\Support\Str;
use Kyrne\Websocket\WebSockets\Messages\PusherChannelProtocolMessage;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class SocketHandler extends WebSocketHandler
{
    /**
     * @var MemoryCollector
     */
    protected $collector;

    public function __construct(MemoryCollector $collector, ChannelManager $channelManager)
    {
        $this->collector = $collector;
        parent::__construct($channelManager);
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {
        $message = $this->createForMessage($message, $connection, $this->channelManager);

        $this->collector->webSocketMessage($connection->app->id);

        $message->respond();
    }

    public function onOpen(ConnectionInterface $connection)
    {
        if (!$this->connectionCanBeMade($connection)) {
            return $connection->close();
        }

        $this->verifyAppKey($connection)
            ->verifyOrigin($connection)
            ->limitConcurrentConnections($connection)
            ->generateSocketId($connection)
            ->establishConnection($connection);

        if (isset($connection->app)) {
            $this->collector->connection($connection->app->id);

            $this->channelManager->subscribeToApp($connection->app->id);

            $this->channelManager->connectionPonged($connection);
        }
    }

    public function onClose(ConnectionInterface $connection)
    {
        $this->channelManager
            ->unsubscribeFromAllChannels($connection)
            ->then(function () use ($connection) {
                $this->channelManager->unsubscribeFromApp($connection->app->id);
            });

        $this->collector->disconnection($connection->app->id);
    }

    public static function createForMessage(
        MessageInterface $message,
        ConnectionInterface $connection,
        ChannelManager $channelManager
    ): PusherMessage {
        $payload = json_decode($message->getPayload());

        return Str::startsWith($payload->event, 'pusher:')
            ? new PusherChannelProtocolMessage($payload, $connection, $channelManager)
            : new PusherClientMessage($payload, $connection, $channelManager);
    }

    protected function establishConnection(ConnectionInterface $connection)
    {
        $connection->send(json_encode([
            'event' => 'pusher:connection_established',
            'data'  => json_encode([
                'socket_id'        => $connection->socketId,
                'activity_timeout' => 30,
            ]),
        ]));

        return $this;
    }
}
