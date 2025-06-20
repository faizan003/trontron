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
    protected $description = 'Process daily interest for active stakings based on individual activation times';

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
            DB::beginTransaction();

            // Get all active stakings
            $activeStakings = Staking::where('status', '=', 'active')
                ->where(function ($query) {
                    $query->whereNull('last_reward_at')
                        ->orWhereRaw('TIMESTAMPDIFF(HOUR, COALESCE(last_reward_at, staked_at), NOW()) >= 24');
                })
                ->get();

            $this->info("Found " . $activeStakings->count() . " active stakings");

            foreach ($activeStakings as $staking) {
                try {
                    $lastRewardTime = $staking->last_reward_at ?? $staking->staked_at;
                    $hoursSinceLastReward = $lastRewardTime->diffInHours(now());

                    $this->info("Staking ID {$staking->id}:");
                    $this->info("- Hours since last reward: {$hoursSinceLastReward}");
                    $this->info("- Last reward time: {$lastRewardTime}");
                    $this->info("- Current time: " . now());

                    if ($hoursSinceLastReward >= 24) {
                        $dailyEarnings = ($staking->amount * $staking->plan->interest_rate) / 100;

                        // Check if staking duration is completed
                        $daysElapsed = $staking->staked_at->diffInDays(now());
                        $isCompleted = $daysElapsed >= $staking->plan->duration;

                        $this->info("- Processing reward: +{$dailyEarnings} TRX");
                        $this->info("- Days elapsed: {$daysElapsed} / {$staking->plan->duration}");

                        // Before update
                        $this->info("- Before update:");
                        $this->info("  * Earned amount: {$staking->earned_amount}");
                        $this->info("  * User total earnings: {$staking->user->total_earnings}");

                        DB::beginTransaction();
                        try {
                            // Update staking
                            $updateData = [
                                'earned_amount' => DB::raw("earned_amount + {$dailyEarnings}"),
                                'last_reward_at' => now()
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

                            // After update
                            $this->info("- After update:");
                            $this->info("  * New earned amount: " . ($staking->earned_amount + $dailyEarnings));
                            $this->info("  * New user total earnings: " . ($staking->user->total_earnings + $dailyEarnings));
                            $this->info("  * Last reward time updated, progress will be recalculated");
                        } catch (\Exception $e) {
                            DB::rollBack();
                            throw $e;
                        }
                    } else {
                        $this->info("- Skipping: Not enough time elapsed");
                    }
                } catch (\Exception $e) {
                    $this->error("Error processing staking {$staking->id}: " . $e->getMessage());
                }
            }

            DB::commit();
            $this->info("Process completed successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Process failed: " . $e->getMessage());
        }
    }
}
