<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateStakingProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staking:update-progress {--debug : Show debug information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update progress for all active stakings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update staking progress...');

        // Check if progress column exists
        if (!Schema::hasColumn('stakings', 'progress')) {
            $this->error('Progress column does not exist in stakings table. Please run migrations first.');
            return;
        }

        // Debug database connection
        try {
            DB::connection()->getPdo();
            $this->info("Database connected successfully: " . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error("Database connection failed: " . $e->getMessage());
            return;
        }

        // Get active stakings with verbose output
        $this->info('Fetching active stakings...');

        try {
            $activeStakings = DB::table('stakings')
                ->where('status', 'active')
                ->get();

            $this->info("Found {$activeStakings->count()} active stakings");

            if ($activeStakings->count() == 0) {
                $this->warn("No active stakings found. Checking total stakings in database...");
                $totalStakings = DB::table('stakings')->count();
                $this->info("Total stakings in database: {$totalStakings}");

                // Show status distribution
                $statusCounts = DB::table('stakings')
                    ->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get();

                $this->info("Status distribution:");
                foreach ($statusCounts as $status) {
                    $this->line("- {$status->status}: {$status->total}");
                }
                return;
            }

            foreach ($activeStakings as $staking) {
                try {
                    $lastRewardTime = $staking->last_reward_at ? Carbon::parse($staking->last_reward_at) : Carbon::parse($staking->staked_at);
                    $hoursSinceLastReward = $lastRewardTime->diffInHours(now());

                    $this->info("Processing Staking ID {$staking->id}:");
                    $this->line("- Last reward time: {$lastRewardTime}");
                    $this->line("- Hours since last reward: {$hoursSinceLastReward}");

                    // Calculate progress percentage (0-100)
                    $progress = min(100, ($hoursSinceLastReward / 24) * 100);

                    // Update progress
                    DB::table('stakings')
                        ->where('id', $staking->id)
                        ->update(['progress' => $progress]);

                    $this->info("- Progress updated to {$progress}%");

                    if ($progress >= 100) {
                        $this->info("- 24 hours completed, ready for reward");
                        Log::info("Staking ID {$staking->id} completed 24-hour cycle and ready for reward");
                    }
                } catch (\Exception $e) {
                    $this->error("Error processing staking {$staking->id}: " . $e->getMessage());
                    Log::error("Error processing staking {$staking->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->error("Error fetching stakings: " . $e->getMessage());
            Log::error("Error fetching stakings: " . $e->getMessage());
        }

        $this->info('Progress update completed!');
    }
}
