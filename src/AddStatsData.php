<?php

namespace Kyrne\Websocket;

use BeyondCode\LaravelWebSockets\Contracts\StatisticsStore;
use Flarum\Frontend\Document;

class AddStatsData
{

    /**
     * @var StatisticsStore
     */
    protected $stats;

    /**
     * AddStatsData constructor.
     * @param StatisticsStore $stats
     */
    public function __construct(StatisticsStore $stats)
    {
        $this->stats = $stats;
    }

    public function __invoke(Document $view)
    {
        $view->payload['websocket_statistics'] = $this->stats->getForGraph();
    }
}
