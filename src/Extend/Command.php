<?php

namespace Hyn\Websocket\Extend;

use Flarum\Console\Event\Configuring;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;

class Command implements ExtenderInterface
{
    /**
     * @var array|string
     */
    protected $commands;

    public function __construct($commands)
    {
        $this->commands = (array) $commands;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        /** @var Dispatcher $events */
        $events = $container->make(Dispatcher::class);

        $events->listen(Configuring::class, function (Configuring $event) {
            foreach ($this->commands as $command) {
                $event->addCommand($command);
            }
        });
    }
}
