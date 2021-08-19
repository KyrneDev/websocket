<?php
/**
 *  This file is part of kyrne/websocket.
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 */

namespace Kyrne\Websocket\Api\Controllers;

use Flarum\Http\RequestUtil;
use Flarum\Http\SlugManager;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Pusher\Pusher;

class AuthController implements RequestHandlerInterface
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var SlugManager
     */
    protected $slugManager;

    public function __construct(SettingsRepositoryInterface $settings, SlugManager $slugManager)
    {
        $this->settings = $settings;
        $this->slugManager = $slugManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $userChannel = 'private-user' . $actor->id;
        $body = $request->getParsedBody();
        $channelName = Arr::get($body, 'channel_name');
        $socketId = Arr::get($body, 'socket_id');

        if ($channelName === $userChannel) {
            $pusher = new Pusher(
                $this->settings->get('kyrne-websocket.app_key'),
                $this->settings->get('kyrne-websocket.app_secret'),
                $this->settings->get('kyrne-websocket.app_id'),
                [],
                $this->settings->get('kyrne-websocket.app_host'),
                $this->settings->get('kyrne-websocket.app_port')
            );

            $payload = json_decode($pusher->socket_auth($userChannel, $socketId), true);

            return new JsonResponse($payload);
        } elseif (strpos($channelName, 'presence') !== false) {
            $pusher = new Pusher(
                $this->settings->get('kyrne-websocket.app_key'),
                $this->settings->get('kyrne-websocket.app_secret'),
                $this->settings->get('kyrne-websocket.app_id'),
                [],
                $this->settings->get('kyrne-websocket.app_host'),
                $this->settings->get('kyrne-websocket.app_port')
            );

            if ($actor->isGuest()) {
                $payload = json_decode($pusher->presence_auth($channelName, $socketId, 'Guest'.mt_rand(), []), true);
            } else {
                $payload = json_decode($pusher->presence_auth($channelName, $socketId, $actor->id, [
                    'username' => $actor->username,
                    'avatarUrl' => $actor->avatar_url,
                    'slug' => $this->slugManager->forResource(User::class)->toSlug($actor),
                ]), true);
            }

            return new JsonResponse($payload);


        } elseif (strpos($channelName, 'private-loginId') !== false) {
            $pusher = new Pusher(
                $this->settings->get('kyrne-websocket.app_key'),
                $this->settings->get('kyrne-websocket.app_secret'),
                $this->settings->get('kyrne-websocket.app_id'),
                [],
                $this->settings->get('kyrne-websocket.app_host'),
                $this->settings->get('kyrne-websocket.app_port')
            );

            $payload = json_decode($pusher->socket_auth(Arr::get($body, 'channel_name'), $socketId), true);

            return new JsonResponse($payload);
        }

        return new EmptyResponse(403);
    }
}
