<?php
/**
 *  This file is part of kyrne/websocket.
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 */

namespace Kyrne\Websocket\Listener;

use Flarum\Post\Event\Posted;
use Illuminate\Contracts\Queue\Queue;
use Kyrne\Websocket\Jobs\SendWebsocketPostJob;

class PushNewPost
{
    /**
     * @var Queue
     */
    protected $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function handle(Posted $event)
    {
        $this->queue->push(new SendWebsocketPostJob($event->post));
    }
}
