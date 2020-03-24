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

namespace Kyrne\Websocket\Listener;

use Flarum\Notification\Event\Sending;
use Pusher\Pusher;

class PushNewNotification
{
    /**
     * @var Pusher
     */
    protected $pusher;

    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    public function handle(Sending $event)
    {
        $blueprint = $event->blueprint;

        foreach ($event->users as $user) {
            if ($user->shouldAlert($blueprint::getType())) {
                $this->pusher->trigger('private-user'.$user->id, 'notification', null);
            }
        }
    }
}