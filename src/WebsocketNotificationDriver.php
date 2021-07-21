<?php

namespace Kyrne\Websocket;

use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\Driver\NotificationDriverInterface;
use Illuminate\Contracts\Queue\Queue;

class WebsocketNotificationDriver implements NotificationDriverInterface
{
    /**
     * @var Queue
     */
    protected $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * {@inheritDoc}
     */
    public function send(BlueprintInterface $blueprint, array $users): void
    {
        if (count($users)) {
            $this->queue->push(new SendWebsocketNotificationsJob($blueprint, $users));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function registerType(string $blueprintClass, array $driversEnabledByDefault): void
    {
        // ...
    }
}
