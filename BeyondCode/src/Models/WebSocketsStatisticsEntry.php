<?php

namespace BeyondCode\LaravelWebSockets\Models;

use Carbon\Carbon;
use Flarum\Database\AbstractModel;

class WebSocketsStatisticsEntry extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'websockets_statistics_entries';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function build(array $data)
    {
        $stat = new static();

        $stat->app_id = $data['app_id'];
        $stat->peak_connection_count = $data['peak_connections_count'];
        $stat->websocket_message_count = $data['websocket_messages_count'];
        $stat->api_message_count = $data['api_messages_count'];
        $stat->created_at = Carbon::now();
        $stat->updated_at = Carbon::now();

        return $stat;
    }
}
