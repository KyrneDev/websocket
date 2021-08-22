<?php

namespace Kyrne\Websocket\Jobs;

use Flarum\Post\Post;
use Flarum\Queue\AbstractJob;
use Flarum\User\Guest;
use Flarum\User\User;
use Illuminate\Support\Str;
use Pusher\Pusher;
use GuzzleHttp\Promise;

class SendWebsocketPostJob extends AbstractJob
{
    /**
     * @var Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        $pusher = app(Pusher::class);
        $post = $this->post;

        $channels = [];

        if ($post->isVisibleTo(new Guest())) {
            $channels[] = 'public';
        } else {
            // Retrieve private channels, used for each user.
            $response = $pusher->get_channels([
                'filter_by_prefix' => 'private-user',
            ]);

            if (!$response) {
                return;
            }

            foreach ($response->channels as $name => $channel) {
                $userId = Str::after($name, 'private-user');

                if (($user = User::find($userId)) && $post->isVisibleTo($user)) {
                    $channels[] = $name;
                }
            }
        }

        if (count($channels)) {
            $promises = (function () use ($channels, $post, $pusher) {
                $tags = $post->discussion->tags;

                foreach (array_chunk($channels, 99) as $channelChunk) {
                    yield $pusher->triggerAsync($channelChunk, 'newPost', [
                        'postId'       => $post->id,
                        'discussionId' => $post->discussion->id,
                        'tagIds'       => $tags ? $tags->pluck('id') : null,
                    ]);
                }
            })();

            Promise\settle($promises)->wait();
        }
    }
}
