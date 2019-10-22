<?php

namespace Hyn\Websocket\Extend;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Illuminate\Contracts\Container\Container;

class Provider implements ExtenderInterface
{
    /**
     * @var string
     */
    private $provider;

    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->register($this->provider);
    }
}
