<?php
/**
 *  This file is part of kyrne/websocket.
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 */

use Illuminate\Contracts\Config\Repository;

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        /** @var Repository $config */
        $config = app(Repository::class);

        return $config->get($key, $default);
    }
}
