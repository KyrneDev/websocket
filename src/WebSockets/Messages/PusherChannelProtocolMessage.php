<?php

namespace Kyrne\Websocket\WebSockets\Messages;

use BeyondCode\LaravelWebSockets\Server\Messages\PusherChannelProtocolMessage as BasePusherChannelProtocolMessage;
use Ratchet\ConnectionInterface;

class PusherChannelProtocolMessage extends BasePusherChannelProtocolMessage
{
    protected function ping(ConnectionInterface $connection)
    {
        $this->channelManager
            ->connectionPonged($connection)
            ->then(function () use ($connection) {
                $connection->send(json_encode(['event' => 'pusher:pong']));
            });
    }
}
