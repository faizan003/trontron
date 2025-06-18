<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StakingPlan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class DashboardControllerOptimized extends Controller
{
    public function overview()
    {
        $userId = auth()->id();
        
        // Cache user dashboard data for 5 minutes
        $dashboardData = Cache::remember("dashboard_data_{$userId}", 300, function() use ($userId) {
            return [
                'total_staked' => $this->getUserTotalStaked($userId),
                'total_earned' => $this->getUserTotalEarned($userId),
                'active_stakings_count' => $this->getActiveStakingsCount($userId),
                'referral_earnings' => $this->getReferralEarnings($userId),
            ];
        });

        return view('dashboard.overview', compact('dashboardData'));
    }

    public function plans()
    {
        // Cache staking plans for 1 hour since they rarely change
        $plans = Cache::remember('staking_plans_active', 3600, function() {
            return StakingPlan::where('is_active', true)
                ->orderBy('minimum_amount', 'asc')
                ->get()
                ->map(function ($plan) {
                    $plan->daily_return = $plan->minimum_amount * ($plan->interest_rate / 100);
                    $plan->total_return = ($plan->duration * $plan->interest_rate) / 100;
                    return $plan;
                });
        });

        return view('dashboard.plans', compact('plans'));
    }

    private function getUserTotalStaked($userId)
    {
        return DB::table('stakings')
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'completed'])
            ->sum('amount');
    }

    private function getUserTotalEarned($userId)
    {
        return DB::table('stakings')
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'completed'])
            ->sum('earned_amount');
    }

    private function getActiveStakingsCount($userId)
    {
        return DB::table('stakings')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->count();
    }

    private function getReferralEarnings($userId)
    {
        return DB::table('referral_earnings')
            ->where('referrer_id', $userId)
            ->sum('amount');
    }
} 