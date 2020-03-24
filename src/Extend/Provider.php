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

namespace Kyrne\Websocket\Extend;

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
