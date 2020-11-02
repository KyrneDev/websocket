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

namespace Kyrne\Websocket\WebSockets;


use BeyondCode\LaravelWebSockets\API\TriggerEvent;
use Illuminate\Http\Request;

class TriggerBroadcastController extends TriggerEvent
{
    public function __invoke(Request $request)
    {
        $this->ensureValidSignature($request);

        foreach ($request->json()->get('channels', []) as $channelName) {
            $channel = $this->channelManager->find($request->appId, $channelName);

            optional($channel)->broadcastToEveryoneExcept((object) [
                'channel' => $channelName,
                'event' => $request->json()->get('name'),
                'data' => $request->json()->get('data'),
            ], $request->json()->get('socket_id'), app('flarum.settings')->get('kyrne-websocket.app_id'));

        }

        return $request->json()->all();
    }
}
