<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrxTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\ReferralEarning;
use Carbon\Carbon;

class StakeTrxController extends Controller
{
    const CONVERT_COOLDOWN_MINUTES = 15;

    public function convert(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $amount = $request->amount;

            // Rate limiting check - prevent conversions within 15 minutes
            $lastConversion = TrxTransaction::where('user_id', $user->id)
                ->where('type', 'convert')
                ->where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subMinutes(self::CONVERT_COOLDOWN_MINUTES))
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastConversion) {
                $timeRemaining = Carbon::now()->diffInMinutes($lastConversion->created_at->addMinutes(self::CONVERT_COOLDOWN_MINUTES));
                $remainingTime = self::CONVERT_COOLDOWN_MINUTES - Carbon::now()->diffInMinutes($lastConversion->created_at);
                
                DB::rollBack();
                return response()->json([
                    'success' => false, 
                    'message' => "Please wait {$remainingTime} minutes before making another conversion.",
                    'error_type' => 'rate_limit',
                    'remaining_minutes' => $remainingTime,
                    'next_available_at' => $lastConversion->created_at->addMinutes(self::CONVERT_COOLDOWN_MINUTES)->toISOString()
                ], 429);
            }

            // Validate amount
            if (!$amount || $amount <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid amount provided'
                ], 400);
            }

            // Get user's wallet
            $wallet = $user->wallet;
            if (!$wallet) {
                DB::rollBack();
                return response()->json([
                    'success' => false, 
                    'message' => 'Wallet not found'
                ], 400);
            }

            // Note: We don't check balance here because the transaction has already been sent to blockchain
            // The frontend has already validated the balance before sending the transaction

            // Update user's wallet balance
            $wallet->balance -= $amount;
            $wallet->tronstake_balance += $amount;
            $wallet->save();

            // Handle referral reward if user was referred
            if ($user->referrer) {
                $referralReward = $amount * 0.05; // 5% reward
                ReferralEarning::create([
                    'referrer_id' => $user->referrer->id,
                    'referred_id' => $user->id,
                    'amount' => $referralReward,
                    'transaction_id' => $request->transaction_id
                ]);
                $user->referrer->increment('referral_earnings', $referralReward);
            }

            // Create transaction record
            TrxTransaction::create([
                'user_id' => $user->id,
                'transaction_id' => $request->transaction_id,
                'amount' => $amount,
                'type' => 'convert',
                'status' => 'completed'
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'next_conversion_available_at' => Carbon::now()->addMinutes(self::CONVERT_COOLDOWN_MINUTES)->toISOString()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Add method to check conversion status
    public function getConversionStatus()
    {
        try {
            $user = auth()->user();
            
            $lastConversion = TrxTransaction::where('user_id', $user->id)
                ->where('type', 'convert')
                ->where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subMinutes(self::CONVERT_COOLDOWN_MINUTES))
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastConversion) {
                $remainingTime = self::CONVERT_COOLDOWN_MINUTES - Carbon::now()->diffInMinutes($lastConversion->created_at);
                return response()->json([
                    'success' => true,
                    'can_convert' => false,
                    'remaining_minutes' => max(0, $remainingTime),
                    'next_available_at' => $lastConversion->created_at->addMinutes(self::CONVERT_COOLDOWN_MINUTES)->toISOString(),
                    'last_conversion_at' => $lastConversion->created_at->toISOString()
                ]);
            }

            return response()->json([
                'success' => true,
                'can_convert' => true,
                'remaining_minutes' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function convertReferralEarnings()
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $earnings = $user->referral_earnings;

            if ($earnings < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum 100 TRX required for conversion'
                ]);
            }

            // Update user's wallet balance
            $user->wallet->tronstake_balance += $earnings;
            $user->wallet->save();

            // Reset referral earnings
            $user->referral_earnings = 0;
            $user->save();

            // Update referral earnings status
            $user->earnedReferrals()
                ->where('status', 'pending')
                ->update(['status' => 'converted']);

            // Create transaction record
            TrxTransaction::create([
                'user_id' => $user->id,
                'transaction_id' => 'REF' . time() . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT),
                'amount' => $earnings,
                'type' => 'referral_convert',
                'status' => 'completed'
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to convert earnings: ' . $e->getMessage()
            ], 500);
        }
    }
}
