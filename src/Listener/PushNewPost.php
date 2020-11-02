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

use Flarum\Post\Event\Posted;
use Flarum\User\Guest;
use Flarum\User\User;
use Illuminate\Support\Str;
use Pusher\Pusher;

class PushNewPost
{
    /**
     * @var Pusher
     */
    protected $pusher;

    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    public function handle(Posted $event)
    {
        $channels = [];

        if ($event->post->isVisibleTo(new Guest)) {
            $channels[] = 'public';
        } else {
            // Retrieve private channels, used for each user.
            $response = $this->pusher->get_channels([
                'filter_by_prefix' => 'private-user'
            ]);

            if (! $response) {
                return;
            }


            foreach ($response->channels as $name => $channel) {
                $userId = Str::after($name, 'private-user');

                if ($userId !== strval($event->post->user->id) && ($user = User::find($userId)) && $event->post->isVisibleTo($user)) {

                    $channels[] = $name;
                }
            }
        }

        if (count($channels)) {
            foreach (array_chunk($channels, 99) as $channelChunk) {
                $tags = $event->post->discussion->tags;

                $this->pusher->trigger($channelChunk, 'newPost', [
                    'postId'       => $event->post->id,
                    'discussionId' => $event->post->discussion->id,
                    'tagIds'       => $tags ? $tags->pluck('id') : null
                ]);
            }
        }
    }
}
