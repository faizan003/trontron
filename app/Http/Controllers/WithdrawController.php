<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WithdrawController extends Controller
{
    public function withdraw(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.000001',
                'original_amount' => 'required|numeric|min:0.000001',
                'fee' => 'required|numeric|min:0',
                'address' => ['required', 'string', 'regex:/^T[A-Za-z0-9]{33}$/'],
                'txid' => 'required|string',
                'from_address' => ['required', 'string', 'regex:/^T[A-Za-z0-9]{33}$/']
            ]);

            // Get admin wallet address from secure storage
            $secureWallet = \App\Models\SecureWallet::getAdminWallet();
            $adminWallet = $secureWallet ? $secureWallet->getDecryptedAddress() : env('ADMIN_WALLET_ADDRESS', 'TL8548WGPxkUbneCLJwg1UgQvNeNMe7E96');

            // Verify from_address matches admin wallet
            if ($request->from_address !== $adminWallet) {
                            // Silent security check - no logging
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid withdrawal configuration'
                ], 400);
            }

            $user = auth()->user();

            if ($request->original_amount > $user->total_earnings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance'
                ], 400);
            }

            // Create withdrawal record
            $withdrawal = WithdrawalHistory::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'original_amount' => $request->original_amount,
                'fee' => $request->fee,
                'address' => $request->address,
                'transaction_id' => $request->txid,
                'status' => 'completed'
            ]);

            // Deduct from user's earnings
            $user->total_earnings -= $request->original_amount;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal processed successfully',
                'transaction' => [
                    'txid' => $request->txid,
                    'amount' => $request->amount,
                    'address' => $request->address
                ]
            ]);

        } catch (\Exception $e) {
            // Silent error handling - no detailed logging
            return response()->json([
                'success' => false,
                'message' => 'Failed to process withdrawal'
            ], 500);
        }
    }

    private function verifyTransaction($txid)
    {
        try {
            // Get API key from encrypted database storage
            $config = \App\Models\ApiConfig::getTronGridConfig();
            $apiKey = $config['trongrid_api_key'] ?? null;
            
            if (!$apiKey) {
                throw new \Exception('TronGrid API key not configured');
            }
            
            $response = Http::withHeaders([
                'TRON-PRO-API-KEY' => $apiKey
            ])->get("https://nile.trongrid.io/wallet/gettransactionbyid", [
                'value' => $txid
            ]);

            if ($response->successful()) {
                $transaction = $response->json();
                return isset($transaction['ret'][0]['contractRet']) &&
                       $transaction['ret'][0]['contractRet'] === 'SUCCESS';
            }
            return false;
        } catch (\Exception $e) {
            // Silent verification failure
            return false;
        }
    }
}
