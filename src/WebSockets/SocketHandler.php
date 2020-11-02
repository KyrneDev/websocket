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

namespace Kyrne\Websocket\WebSockets;

use BeyondCode\LaravelWebSockets\Server\Messages\PusherMessageFactory;
use BeyondCode\LaravelWebSockets\Server\WebSocketHandler;
use BeyondCode\LaravelWebSockets\Server\Messages\PusherClientMessage;
use BeyondCode\LaravelWebSockets\Contracts\PusherMessage;
use BeyondCode\LaravelWebSockets\Contracts\ChannelManager;
use Illuminate\Support\Str;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Kyrne\Websocket\WebSockets\Messages\PusherChannelProtocolMessage;

class SocketHandler extends WebSocketHandler
{

    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {
        $message = $this->createForMessage($message, $connection, $this->channelManager);

        $message->respond();
    }

    public function onOpen(ConnectionInterface $connection) {
        if (! $this->connectionCanBeMade($connection)) {
            return $connection->close();
        }

        $this->verifyAppKey($connection)
            ->verifyOrigin($connection)
            ->limitConcurrentConnections($connection)
            ->generateSocketId($connection)
            ->establishConnection($connection);

        if (isset($connection->app)) {
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
    }

    public static function createForMessage(
        MessageInterface $message,
        ConnectionInterface $connection,
        ChannelManager $channelManager): PusherMessage
    {
        $payload = json_decode($message->getPayload());

        return Str::startsWith($payload->event, 'pusher:')
            ? new PusherChannelProtocolMessage($payload, $connection, $channelManager)
            : new PusherClientMessage($payload, $connection, $channelManager);
    }

    protected function establishConnection(ConnectionInterface $connection)
    {
        $connection->send(json_encode([
            'event' => 'pusher:connection_established',
            'data' => json_encode([
                'socket_id' => $connection->socketId,
                'activity_timeout' => 30,
            ]),
        ]));

        return $this;
    }
}
