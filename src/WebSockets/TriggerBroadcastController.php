<?php
/**
 *  This file is part of kyrne/websocket.
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 */

namespace Kyrne\Websocket\WebSockets;

use BeyondCode\LaravelWebSockets\API\TriggerEvent;
use Illuminate\Http\Request;

class TriggerBroadcastController extends TriggerEvent
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|object
     */
    public function __invoke(Request $request)
    {
        $channels = $request->channels ?: [];

        if (is_string($channels)) {
            $channels = [$channels];
        }

        foreach ($channels as $channelName) {
            // Here you can use the ->find(), even if the channel
            // does not exist on the server. If it does not exist,
            // then the message simply will get broadcasted
            // across the other servers.
            $channel = $this->channelManager->find(
                $request->appId, $channelName
            );

            $payload = [
                'event' => $request->name,
                'channel' => $channelName,
                'data' => $request->data,
            ];

            if ($channel) {
                $channel->broadcastLocallyToEveryoneExcept(
                    (object) $payload,
                    $request->socket_id,
                    $request->appId
                );
            }
        }

        return (object) [];
    }
}
