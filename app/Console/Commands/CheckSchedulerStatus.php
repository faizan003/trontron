<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckSchedulerStatus extends Command
{
    protected $signature = 'scheduler:status';
    protected $description = 'Check if the scheduler is running properly';

    public function handle()
    {
        $logPath = storage_path('logs/staking-interest.log');

        if (!File::exists($logPath)) {
            $this->error('Staking interest log file does not exist!');
            return 1;
        }

        $lastLine = $this->getLastLine($logPath);
        $lastRunTime = File::lastModified($logPath);
        $hoursSinceLastRun = (time() - $lastRunTime) / 3600;

        $this->info('Last scheduler run: ' . date('Y-m-d H:i:s', $lastRunTime));
        $this->info('Hours since last run: ' . number_format($hoursSinceLastRun, 2));
        $this->info('Last output: ' . $lastLine);

        if ($hoursSinceLastRun > 24) {
            $this->error('Warning: Scheduler has not run in the last 24 hours!');
            return 1;
        }

        $this->info('Scheduler appears to be running normally.');
        return 0;
    }

    private function getLastLine($filepath)
    {
        $line = '';
        $f = fopen($filepath, 'r');
        $cursor = -1;

        fseek($f, $cursor, SEEK_END);
        $char = fgetc($f);

        while ($char === "\n" || $char === "\r") {
            fseek($f, $cursor--, SEEK_END);
            $char = fgetc($f);
        }

        while ($char !== false && $char !== "\n" && $char !== "\r") {
            $line = $char . $line;
            fseek($f, $cursor--, SEEK_END);
            $char = fgetc($f);
        }

        fclose($f);
        return $line;
    }
}
