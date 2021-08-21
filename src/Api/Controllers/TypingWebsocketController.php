<?php

namespace Kyrne\Websocket\Api\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Psr\Http\Message\ServerRequestInterface;
use Pusher\Pusher;
use Tobscure\JsonApi\Document;

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

        if ($actor->isGuest()) {
            throw new PermissionDeniedException;
        } else {
            $this->pusher->trigger('presence-' . $data['discussionId'], 'typing', [
                'userId' => $actor->id,
                'avatarUrl' => $actor->avatar_url,
                'displayName' => $actor->getDisplayNameAttribute(),
            ]);

            return true;
        }

    }
}
