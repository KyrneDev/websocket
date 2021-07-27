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

namespace Kyrne\Websocket\Api\Controllers;

use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Pusher\Pusher;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;

class AuthController implements RequestHandlerInterface
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $userChannel = 'private-user'.$actor->id;
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
        } else if (strpos($channelName, 'presence') !== false) {
            if ($actor->id) {
                $pusher = new Pusher(
                    $this->settings->get('kyrne-websocket.app_key'),
                    $this->settings->get('kyrne-websocket.app_secret'),
                    $this->settings->get('kyrne-websocket.app_id'),
                    [],
                    $this->settings->get('kyrne-websocket.app_host'),
                    $this->settings->get('kyrne-websocket.app_port')
                );

                $payload = json_decode($pusher->presence_auth($channelName, $socketId, $actor->id, [
                    'username' => $actor->username,
                    'avatarUrl' => $actor->avatar_url,
                ]), true);

                return new JsonResponse($payload);
            }

        } else if (strpos($channelName, 'private-loginId') !== false) {
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
