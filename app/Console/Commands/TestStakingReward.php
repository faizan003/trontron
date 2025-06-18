<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestStakingReward extends Command
{
    protected $signature = 'staking:test-reward';
    protected $description = 'Test staking reward calculation';

    public function handle()
    {
        $this->info('Testing staking reward calculation...');

        $this->call('staking:process-daily-interest');

        $this->info('Test completed. Check the logs for details.');
    }
}
