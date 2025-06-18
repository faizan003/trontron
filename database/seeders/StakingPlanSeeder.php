<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StakingPlan;

class StakingPlanSeeder extends Seeder
{
    public function run()
    {
        StakingPlan::create([
            'name' => 'Premium Staking Plan',
            'minimum_amount' => 150,
            'maximum_amount' => 350,
            'interest_rate' => 2, // Daily interest rate to achieve 3x in 150 days
            'duration' => 150,
            'is_active' => true
        ]);
        StakingPlan::create([
            'name' => 'Advanced Staking Plan',
            'minimum_amount' => 400,
            'maximum_amount' => 800,
            'interest_rate' => 2.31, // Daily interest rate to achieve 3x in 130 days
            'duration' => 130,
            'is_active' => true
        ]);

        StakingPlan::create([
            'name' => 'Elite Staking Plan',
            'minimum_amount' => 850,
            'maximum_amount' => 5000,
            'interest_rate' => 2.73, // Daily interest rate to achieve 3x in 110 days
            'duration' => 110,
            'is_active' => true
        ]);
    }
}
