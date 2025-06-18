<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestStakingCompletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staking:test-completion {staking_id? : The ID of the staking to complete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test staking completion by simulating elapsed time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting staking completion test...');

        try {
            DB::beginTransaction();

            // Get the staking to test
            if ($stakingId = $this->argument('staking_id')) {
                $staking = Staking::findOrFail($stakingId);
                $stakings = collect([$staking]);
            } else {
                $stakings = Staking::where('status', 'active')->get();
            }

            if ($stakings->isEmpty()) {
                $this->error('No active stakings found to test.');
                return;
            }

            foreach ($stakings as $staking) {
                $this->info("\nProcessing Staking ID: {$staking->id}");
                $this->info("Current Status: {$staking->status}");
                $this->info("Current Earned Amount: {$staking->earned_amount}");

                // Calculate plan duration and earnings
                $duration = $staking->plan->duration;
                $dailyEarnings = ($staking->amount * $staking->plan->interest_rate) / 100;
                $totalEarnings = $dailyEarnings * $duration;

                // Set staked_at to be exactly duration days ago
                $newStakedAt = now()->subDays($duration);

                $this->info("Original Staked At: {$staking->staked_at}");
                $this->info("Setting Staked At to: {$newStakedAt}");
                $this->info("Plan Duration: {$duration} days");
                $this->info("Daily Earnings: {$dailyEarnings}");
                $this->info("Total Earnings to Set: {$totalEarnings}");

                // Update staking to completed state
                DB::table('stakings')
                    ->where('id', $staking->id)
                    ->update([
                        'staked_at' => $newStakedAt,
                        'earned_amount' => $totalEarnings,
                        'status' => 'completed',
                        'end_at' => now(),
                        'progress' => 100,
                        'last_reward_at' => now()
                    ]);

                // Update user's total earnings
                $staking->user->total_earnings = $totalEarnings;
                $staking->user->save();

                // Refresh the staking model
                $staking->refresh();

                $this->info("Final Earned Amount: {$staking->earned_amount}");
                $this->info("Status: {$staking->status}");
                $this->info("Days Elapsed: {$duration}");
            }

            DB::commit();
            $this->info("\nStaking completion test finished successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during test: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
