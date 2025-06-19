<?php

namespace App\Http\Controllers;

use App\Models\StakingPlan;
use App\Models\Staking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StakingController extends Controller
{
    public function stake(Request $request)
    {
            $request->validate([
                'plan_id' => 'required|exists:staking_plans,id',
            'amount' => 'required|numeric|min:0.000001|max:999999999'
            ]);

        return DB::transaction(function () use ($request) {
            $plan = StakingPlan::where('id', $request->plan_id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->firstOrFail();

            $user = auth()->user();
            $wallet = $user->wallet()->lockForUpdate()->firstOrFail();

            // Check if user has enough balance
            if ($wallet->tronstake_balance < $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient StakeTRX balance'
                ], 400);
            }

            // Check if amount meets plan requirements
            if ($request->amount < $plan->minimum_amount ||
                ($plan->maximum_amount > 0 && $request->amount > $plan->maximum_amount)) {
                return response()->json([
                    'success' => false,
                    'message' => sprintf(
                        'Amount must be between %s and %s TRX', 
                        number_format($plan->minimum_amount, 6),
                        $plan->maximum_amount > 0 ? number_format($plan->maximum_amount, 6) : 'unlimited'
                    )
                ], 400);
            }

            // Create staking record
            $staking = Staking::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $request->amount,
                'earned_amount' => 0,
                'status' => 'active',
                'staked_at' => now(),
                'end_at' => now()->addDays($plan->duration),
                'last_reward_at' => null
            ]);

            // Deduct from wallet balance
            $wallet->decrement('tronstake_balance', $request->amount);

            Log::info('Staking created successfully', [
                'user_id' => $user->id,
                'staking_id' => $staking->id,
                'plan_id' => $plan->id,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Staking activated successfully',
                'staking_id' => $staking->id
            ]);
        });
    }

    public function getStakingStats()
    {
        $userId = auth()->id();
        
        // Cache for 1 minute to reduce load
        $cacheKey = "staking_stats_{$userId}";
        
        $activeStakings = cache()->remember($cacheKey, 60, function () use ($userId) {
            return DB::table('stakings')
                ->join('staking_plans', 'stakings.plan_id', '=', 'staking_plans.id')
                ->where('stakings.user_id', $userId)
                ->where('stakings.status', 'active')
                ->select([
                    'stakings.id',
                    'stakings.amount',
                    'stakings.earned_amount',
                    'stakings.staked_at',
                    'stakings.last_reward_at',
                    'stakings.progress',
                    'stakings.updated_at',
                    'staking_plans.name as plan_name',
                    'staking_plans.duration',
                    'staking_plans.interest_rate'
                ])
                ->get()
                ->map(function ($staking) {
                    $startDate = \Carbon\Carbon::parse($staking->staked_at);
                    $now = now();
                    $totalDays = $staking->duration;

                    // Calculate progress
                    $daysElapsed = $startDate->diffInDays($now);
                    $progress = min(100, ($daysElapsed / $totalDays) * 100);
                    $daysRemaining = max(0, $totalDays - $daysElapsed);

                    // Calculate daily progress
                    $lastReward = $staking->last_reward_at 
                        ? \Carbon\Carbon::parse($staking->last_reward_at)
                        : $startDate;
                    $hoursSinceLastReward = $lastReward->diffInHours($now);
                    
                    // Check if progress was recently reset (reward was processed)
                    $progressRecentlyReset = $staking->last_reward_at && 
                                           \Carbon\Carbon::parse($staking->last_reward_at)->diffInMinutes($now) < 60 && 
                                           $staking->progress == 0;
                    
                    if ($progressRecentlyReset) {
                        // Use time since last reward for fresh calculation
                        $dailyProgress = min(100, ($hoursSinceLastReward / 24) * 100);
                    } else {
                        // Use database progress field if available, otherwise calculate from time
                        $calculatedProgress = min(100, ($hoursSinceLastReward / 24) * 100);
                        $dailyProgress = $staking->progress > 0 ? $staking->progress : $calculatedProgress;
                    }

                    // Calculate earnings
                    $dailyEarnings = ($staking->amount * $staking->interest_rate) / 100;
                    $earnedToday = ($dailyProgress / 100) * $dailyEarnings;
                    
                    // Next payout time
                    $nextPayout = $lastReward->copy()->addHours(24);

                    return [
                        'id' => $staking->id,
                        'plan_name' => $staking->plan_name,
                        'amount' => number_format($staking->amount, 6),
                        'earned_amount' => number_format($staking->earned_amount, 6),
                        'progress' => round($progress, 2),
                        'current_day' => $daysElapsed + 1,
                        'days_remaining' => $daysRemaining,
                        'total_days' => $totalDays,
                        'daily_earnings' => number_format($dailyEarnings, 6),
                        'earned_today' => number_format($earnedToday, 6),
                        'daily_progress' => round($dailyProgress, 2),
                        'staked_at' => $startDate->format('M d, Y'),
                        'end_at' => $startDate->copy()->addDays($totalDays)->format('M d, Y'),
                        'next_payout' => $nextPayout->format('M d, Y H:i'),
                        'time_until_payout' => $now->diffInSeconds($nextPayout),
                        'status' => 'active'
                    ];
                });
                });

            return response()->json([
                'success' => true,
            'data' => $activeStakings,
            'cached' => true
        ]);
    }

    public function withdraw(Request $request, Staking $staking)
    {
        // Security checks
        if ($staking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to staking record');
        }

        if ($staking->status !== 'active') {
            return back()->with('error', 'This staking is not active and cannot be withdrawn');
        }

        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|accepted'
        ]);

        // Verify password
        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Invalid password provided');
        }

        return DB::transaction(function () use ($staking) {
            $user = auth()->user();
            
            // Calculate final earnings
            $totalAmount = $staking->amount + $staking->earned_amount;
            
            // Update staking status
            $staking->update([
                'status' => 'withdrawn',
                'end_at' => now()
            ]);

            // Add to user's withdrawable balance
            $user->wallet->increment('balance', $totalAmount);

            Log::info('Staking withdrawn successfully', [
                'user_id' => $user->id,
                'staking_id' => $staking->id,
                'amount_withdrawn' => $totalAmount
            ]);

            return back()->with('success', 
                "Successfully withdrawn {$totalAmount} TRX (Principal: {$staking->amount} + Earnings: {$staking->earned_amount})"
            );
        });
    }
}
