<?php

namespace Kyrne\Websocket\Channels;

use BeyondCode\LaravelWebSockets\Channels\Channel as BaseChannel;
use Ratchet\ConnectionInterface;
use stdClass;

class Channel extends BaseChannel
{
    public function subscribe(ConnectionInterface $connection, stdClass $payload): bool
    {
        $this->saveConnection($connection);

        $connection->send(json_encode([
            'event'   => 'pusher_internal:subscription_succeeded',
            'channel' => $this->getName(),
        ]));

        return true;
    }

    public function unsubscribe(ConnectionInterface $connection): bool
    {
        if (!$this->hasConnection($connection)) {
            return false;
        }

        unset($this->connections[$connection->socketId]);

        return true;
    }
}
