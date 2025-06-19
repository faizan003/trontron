<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Staking;
use App\Models\StakingPlan;
use App\Models\WithdrawalHistory;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FakeUserSeeder extends Seeder
{
    public function run()
    {
        // Create the user 90 days ago
        $joinedDate = Carbon::now()->subDays(90);
        
        $user = User::create([
            'name' => 'Md Faizan Khan',
            'email' => 'faizankhan003@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '+8801234567890',
            'referral_code' => 'FAIZAN' . rand(1000, 9999),
            'total_earnings' => 0, // Will be calculated
            'google2fa_enabled' => false,
            'email_verified_at' => $joinedDate,
            'created_at' => $joinedDate,
            'updated_at' => $joinedDate,
        ]);

        // Create wallet for the user
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'address' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE', // Fake TRON address
            'private_key' => encrypt('fake_private_key_' . $user->id),
            'balance' => 0, // Will be updated as we process
            'created_at' => $joinedDate,
            'updated_at' => $joinedDate,
        ]);

        // Get Premium Staking Plan
        $premiumPlan = StakingPlan::where('name', 'Premium Staking Plan')->first();
        
        if (!$premiumPlan) {
            // Create premium plan if it doesn't exist
            $premiumPlan = StakingPlan::create([
                'name' => 'Premium Staking Plan',
                'minimum_amount' => 150,
                'maximum_amount' => 350,
                'interest_rate' => 2, // 2% daily
                'duration' => 150,
                'is_active' => true
            ]);
        }

        // Create staking record for 350 TRX
        $stakingStartDate = $joinedDate->copy()->addHours(2); // Staked 2 hours after joining
        $staking = Staking::create([
            'user_id' => $user->id,
            'plan_id' => $premiumPlan->id,
            'amount' => 350.000000,
            'earned_amount' => 0, // Will be calculated
            'status' => 'active',
            'staked_at' => $stakingStartDate,
            'end_at' => $stakingStartDate->copy()->addDays($premiumPlan->duration),
            'last_reward_at' => $stakingStartDate,
            'created_at' => $stakingStartDate,
            'updated_at' => $stakingStartDate,
        ]);

        // Calculate daily rewards and withdrawals for 90 days
        $totalEarnings = 0;
        $totalWithdrawn = 0;
        $currentBalance = 0;
        $lastWithdrawalDay = 0;
        
        // Premium plan: 2% daily interest on 350 TRX
        $dailyReward = 350 * (2 / 100); // 7 TRX per day
        
        for ($day = 1; $day <= 90; $day++) {
            $currentDate = $stakingStartDate->copy()->addDays($day);
            
            // Add daily reward
            $totalEarnings += $dailyReward;
            $currentBalance += $dailyReward;
            
            // Check if it's time to withdraw (every 4-6 days randomly)
            $daysSinceLastWithdrawal = $day - $lastWithdrawalDay;
            $shouldWithdraw = $daysSinceLastWithdrawal >= 4 && (
                $daysSinceLastWithdrawal >= 6 || 
                ($daysSinceLastWithdrawal >= 4 && rand(1, 3) == 1)
            );
            
            if ($shouldWithdraw && $currentBalance >= 10) { // Minimum withdrawal of 10 TRX
                // Calculate withdrawal amount (80-95% of available balance)
                $withdrawalPercentage = rand(80, 95) / 100;
                $originalAmount = $currentBalance * $withdrawalPercentage;
                
                // Calculate withdrawal fee (1% of original amount)
                $fee = $originalAmount * 0.01;
                $finalAmount = $originalAmount - $fee;
                
                // Create withdrawal record
                WithdrawalHistory::create([
                    'user_id' => $user->id,
                    'amount' => $finalAmount,
                    'original_amount' => $originalAmount,
                    'fee' => $fee,
                    'address' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
                    'transaction_id' => 'txn_' . strtoupper(bin2hex(random_bytes(16))),
                    'status' => 'completed',
                    'created_at' => $currentDate->copy()->addHours(rand(10, 18)), // Random time during day
                    'updated_at' => $currentDate->copy()->addHours(rand(10, 18)),
                ]);
                
                $totalWithdrawn += $finalAmount;
                $currentBalance -= $originalAmount;
                $lastWithdrawalDay = $day;
            }
        }
        
        // Update staking earned amount
        $staking->update([
            'earned_amount' => $totalEarnings,
            'updated_at' => Carbon::now(),
        ]);
        
        // Update user total earnings
        $user->update([
            'total_earnings' => $totalEarnings,
            'updated_at' => Carbon::now(),
        ]);
        
        // Update wallet balance with remaining amount
        $wallet->update([
            'balance' => $currentBalance,
            'updated_at' => Carbon::now(),
        ]);
        
        echo "✅ Created fake user: {$user->name}\n";
        echo "📧 Email: {$user->email}\n";
        echo "🔑 Password: 12345678\n";
        echo "💰 Total Staked: 350 TRX\n";
        echo "📈 Total Earned: " . number_format($totalEarnings, 6) . " TRX\n";
        echo "💸 Total Withdrawn: " . number_format($totalWithdrawn, 6) . " TRX\n";
        echo "💳 Current Balance: " . number_format($currentBalance, 6) . " TRX\n";
        echo "📅 Staking Duration: 90 days\n";
        echo "🏦 Withdrawals Count: " . WithdrawalHistory::where('user_id', $user->id)->count() . "\n";
    }
} 