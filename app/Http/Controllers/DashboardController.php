<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StakingPlan;

class DashboardController extends Controller
{
    private function getAdminWallet()
    {
        // Get admin wallet from secure encrypted storage
        $secureWallet = \App\Models\SecureWallet::getAdminWallet();
        
        if (!$secureWallet) {
            // Fallback to .env if secure wallet not configured yet
            return [
                'address' => env('ADMIN_WALLET_ADDRESS'),
                'privateKey' => env('ADMIN_WALLET_PRIVATE_KEY')
            ];
        } else {
            return [
                'address' => $secureWallet->getDecryptedAddress(),
                'privateKey' => $secureWallet->getDecryptedPrivateKey()
            ];
        }
    }

    public function overview()
    {
        $adminWallet = $this->getAdminWallet();
        return view('dashboard.overview', compact('adminWallet'));
    }

    public function convert()
    {
        $adminWallet = $this->getAdminWallet();
        return view('dashboard.convert', compact('adminWallet'));
    }

    public function stake()
    {
        return view('dashboard.stake');
    }

    public function referrals()
    {
        return view('dashboard.referrals');
    }

    public function plans()
    {
        $plans = StakingPlan::where('is_active', true)
            ->orderBy('minimum_amount', 'asc')
            ->get()
            ->map(function ($plan) {
                $plan->daily_return = $plan->minimum_amount * ($plan->interest_rate / 100);
                $plan->total_return = ($plan->duration * $plan->interest_rate) / 100;
                return $plan;
            });

        return view('dashboard.plans', compact('plans'));
    }

    public function withdraw()
    {
        $adminWallet = $this->getAdminWallet();
        return view('dashboard.withdraw', compact('adminWallet'));
    }
}
