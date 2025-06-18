<?php

namespace App\Jobs;

use App\Models\Staking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessStakingReward implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stakingId;
    public $timeout = 60;
    public $tries = 3;

    public function __construct($stakingId)
    {
        $this->stakingId = $stakingId;
    }

    public function handle()
    {
        try {
            $staking = Staking::with(['plan', 'user'])->find($this->stakingId);
            
            if (!$staking || $staking->status !== 'active') {
                return;
            }

            // Check if reward is due
            $lastRewardTime = $staking->last_reward_at ?? $staking->staked_at;
            $hoursSinceLastReward = $lastRewardTime->diffInHours(now());

            if ($hoursSinceLastReward < 24) {
                return; // Not due yet
            }

            DB::beginTransaction();

            $dailyEarnings = ($staking->amount * $staking->plan->interest_rate) / 100;
            $daysElapsed = $staking->staked_at->diffInDays(now());
            $isCompleted = $daysElapsed >= $staking->plan->duration;

            // Update staking
            $updateData = [
                'earned_amount' => DB::raw("earned_amount + {$dailyEarnings}"),
                'last_reward_at' => now(),
                'updated_at' => now()
            ];

            if ($isCompleted) {
                $updateData['status'] = 'completed';
                $updateData['end_at'] = now();
            }

            DB::table('stakings')
                ->where('id', $staking->id)
                ->update($updateData);

            // Update user total earnings
            DB::table('users')
                ->where('id', $staking->user_id)
                ->increment('total_earnings', $dailyEarnings);

            DB::commit();

            Log::info("Processed staking reward", [
                'staking_id' => $staking->id,
                'user_id' => $staking->user_id,
                'earnings' => $dailyEarnings,
                'completed' => $isCompleted
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process staking reward", [
                'staking_id' => $this->stakingId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Staking reward job failed permanently", [
            'staking_id' => $this->stakingId,
            'error' => $exception->getMessage()
        ]);
    }
} 