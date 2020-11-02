<?php

namespace Kyrne\Websocket\Channels\Managers;

use BeyondCode\LaravelWebSockets\ChannelManagers\LocalChannelManager as BaseLocalChannelManager;
use Illuminate\Support\Str;
use Kyrne\Websocket\Channels\Channel;
use Kyrne\Websocket\Channels\PrivateChannel;

class LocalChannelManager extends BaseLocalChannelManager
{
    protected function getChannelClassName(string $channelName): string
    {
        if (Str::startsWith($channelName, 'private-')) {
            return PrivateChannel::class;
        }

        return Channel::class;
    }
}
