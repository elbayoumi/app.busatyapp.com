<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunPm2Command extends Command
{
    protected $signature = 'run:pm2';
    protected $description = 'Run npm pm2 and save it';

    public function handle()
    {
        $projectPath = '/home/busatyapp-node/htdocs/node.busatyapp.com';

        // Run `npm run pm2`
        $this->info('Running: npm run pm2');
        $process1 = Process::fromShellCommandline('npm run pm2', $projectPath);
        $process1->run();

        if (!$process1->isSuccessful()) {
            $this->error($process1->getErrorOutput());
            return 1;
        }

        $this->info($process1->getOutput());

        // Run `pm2 save`
        $this->info('Running: pm2 save');
        $process2 = Process::fromShellCommandline('pm2 save', $projectPath);
        $process2->run();

        if (!$process2->isSuccessful()) {
            $this->error($process2->getErrorOutput());
            return 1;
        }

        $this->info($process2->getOutput());

        $this->info('PM2 run and save completed successfully.');
        return 0;
    }
}
