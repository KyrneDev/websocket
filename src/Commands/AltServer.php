<?php
/**
 *  This file is part of kyrne/websocket.
 *
 *  Copyright (c) 2020 Charlie Kern.
 *
 *  For the full copyright and license information, please view the EULA.md
 *  file that was distributed with this source code.
 */

namespace Kyrne\Websocket\Commands;

use Flarum\Console\AbstractCommand;
use Symfony\Component\Process\Process;

class AltServer extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('websocket:beta')
            ->setDescription('Run Beta Websocket Server');
    }

    public function fire()
    {
        $this->error('Warning: This implementation of the websocket server is in beta, you may encounter errors.');
        $this->info('Attempting to start Poxa - this may take a few seconds...');

        $setting = app('flarum.settings');

        $id = $setting->get('kyrne-websocket.app_id');
        $key = $setting->get('kyrne-websocket.app_key');
        $secret = $setting->get('kyrne-websocket.app_secret');
        $port = $setting->get('kyrne-websocket.app_port');
        $pk = $setting->get('kyrne-websocket.local_pk');
        $cert = $setting->get('kyrne-websocket.local_cert');

        $ssl = false;

        if ($setting->get('kyrne-websocket.force_secure') || parse_url(app('flarum.config')['url'])['scheme'] === 'https') {
            $ssl = true;
        }

        $ssl = (bool) $setting->get('kyrne-websocket.reverse_proxy') ? false : $ssl;

        $process = new Process([__DIR__.'/../../poxa-'.PHP_OS.'/bin/poxa.sh', 'start', $id, $key, $secret, $port, ($ssl ? 'true ' : 'false'), $cert, $pk]);
        $process->setTimeout(0);

        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->info($data);
            } else {
                $this->output->writeln("<comment>$data</comment>");
            }
        }
    }
}
