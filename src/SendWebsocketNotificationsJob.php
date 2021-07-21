<?php

namespace Kyrne\Websocket;

use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Queue\AbstractJob;
use Flarum\User\User;
use Pusher\Pusher;

class SendWebsocketNotificationsJob extends AbstractJob
{
    /**
     * @var BlueprintInterface
     */
    private $blueprint;

    /**
     * @var User[]
     */
    private $recipients;

    /**
     * @var Pusher
     */
    protected $pusher;

    public function __construct(BlueprintInterface $blueprint, array $recipients, Pusher $pusher)
    {
        $this->blueprint = $blueprint;
        $this->recipients = $recipients;
        $this->pusher = $pusher;
    }

    public function handle()
    {
        foreach ($this->recipients as $user) {
            if ($user->shouldAlert($this->blueprint::getType())) {
                $this->pusher->trigger('private-user'.$user->id, 'notification', null);
            }
        }
    }
}
