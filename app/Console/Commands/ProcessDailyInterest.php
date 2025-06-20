<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputOption;

class ProcessDailyInterest extends Command
{
    protected $signature = 'staking:process-daily-interest {--force : Force the operation to run}';
    protected $description = 'Process daily interest for active stakings and update progress in real-time';

    public function handle()
    {
        // Force the command to run regardless of schedule
        if (!$this->option('force') && app()->environment('production')) {
            $this->info('This command should be run with --force in production');
            return;
        }

        $logMessage = sprintf(
            "[%s] ProcessDailyInterest started - PID: %s\n",
            date('Y-m-d H:i:s'),
            getmypid()
        );

        file_put_contents(storage_path('logs/cron-debug.log'), $logMessage, FILE_APPEND);

        try {
            // Get all active stakings and update their progress in real-time
            $activeStakings = Staking::where('status', '=', 'active')->get();

            $this->info("Found " . $activeStakings->count() . " active stakings");
            
            $processedCount = 0;
            $updatedCount = 0;

            foreach ($activeStakings as $staking) {
                try {
                    $lastRewardTime = $staking->last_reward_at ?? $staking->staked_at;
                    $hoursSinceLastReward = $lastRewardTime->diffInHours(now());

                    // Calculate daily progress (resets every 24 hours)
                    $dailyProgress = (fmod($hoursSinceLastReward, 24) / 24) * 100;
                    $dailyProgress = min(100, max(0, $dailyProgress));

                    // Always update progress for real-time display
                    DB::table('stakings')
                        ->where('id', $staking->id)
                        ->update([
                            'progress' => $dailyProgress,
                            'updated_at' => now()
                        ]);

                    $updatedCount++;

                    $this->info("Staking ID {$staking->id}:");
                    $this->info("- Hours since last reward: {$hoursSinceLastReward}");
                    $this->info("- Daily progress: " . number_format($dailyProgress, 2) . "%");

                    // Process reward if 24+ hours have passed
                    if ($hoursSinceLastReward >= 24) {
                        $processedCount++;
                        $dailyEarnings = ($staking->amount * $staking->plan->interest_rate) / 100;

                        // Check if staking duration is completed
                        $daysElapsed = $staking->staked_at->diffInDays(now());
                        $isCompleted = $daysElapsed >= $staking->plan->duration;

                        $this->info("- Processing reward: +{$dailyEarnings} TRX");
                        $this->info("- Days elapsed: {$daysElapsed} / {$staking->plan->duration}");

                        DB::beginTransaction();
                        try {
                            // Update staking with reward and reset progress
                            $updateData = [
                                'earned_amount' => DB::raw("earned_amount + {$dailyEarnings}"),
                                'last_reward_at' => now(),
                                'progress' => 0, // Reset progress after reward
                                'updated_at' => now()
                            ];

                            // If staking is completed, update status
                            if ($isCompleted) {
                                $updateData['status'] = 'completed';
                                $updateData['end_at'] = now();
                                $this->info("- Staking completed! Final reward processed.");
                            }

                            DB::table('stakings')
                                ->where('id', $staking->id)
                                ->update($updateData);

                            // Update user earnings
                            DB::table('users')
                                ->where('id', $staking->user_id)
                                ->update([
                                    'total_earnings' => DB::raw("total_earnings + {$dailyEarnings}")
                                ]);

                            DB::commit();

                            $this->info("- Reward processed successfully");
                        } catch (\Exception $e) {
                            DB::rollBack();
                            throw $e;
                        }
                    }
                } catch (\Exception $e) {
                    $this->error("Error processing staking {$staking->id}: " . $e->getMessage());
                }
            }

            $this->info("Process completed successfully");
            $this->info("Summary: {$processedCount} rewards processed, {$updatedCount} progress updates");

        } catch (\Exception $e) {
            $this->error("Process failed: " . $e->getMessage());
        }
    }
}
