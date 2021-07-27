<?php

namespace Kyrne\Websocket\Api\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Http\RequestUtil;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Pusher\Pusher;

class TypingWebsocketController extends AbstractShowController
{
    public $serializer = BasicUserSerializer::class;

    /**
     * @var Pusher
     */
    protected $pusher;

    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    public function data(ServerRequestInterface $request, Document $document)
    {
        $data = $request->getParsedBody();
        $actor = RequestUtil::getActor($request);

        $this->pusher->trigger('presence-' . $data['discussionId'], 'typing', [
            'userId' => $actor->id,
            'avatarUrl' => $actor->avatar_url,
            'username' => $actor->username,
        ]);

        return true;
    }
}
