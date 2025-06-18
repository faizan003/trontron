<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SecureWalletController extends Controller
{
    /**
     * Get wallet address (safe to expose)
     */
    public function getWalletAddress()
    {
        $user = auth()->user();
        
        if (!$user->wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'address' => $user->wallet->address
        ]);
    }

    /**
     * Get private key with strict security verification
     */
    public function getPrivateKey(Request $request)
    {
        $user = auth()->user();
        
        // Rate limiting for private key access
        $key = 'private-key-access:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many attempts. Please wait {$seconds} seconds."
            ], 429);
        }

        $request->validate([
            'password' => 'required|string',
            'two_fa_code' => 'required_if:google2fa_enabled,true|string|size:6'
        ]);

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key);
            Log::warning('Private key access attempt with wrong password', [
                'user_hash' => hash('sha256', $user->id . env('APP_KEY')),
                'timestamp' => now()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid password'
            ], 401);
        }

        // Verify 2FA if enabled
        if ($user->google2fa_enabled) {
            $google2fa = app(\PragmaRX\Google2FA\Google2FA::class);
            
            if (!$google2fa->verifyKey($user->google2fa_secret, $request->two_fa_code)) {
                RateLimiter::hit($key);
                Log::warning('Private key access attempt with wrong 2FA', [
                    'user_hash' => hash('sha256', $user->id . env('APP_KEY')),
                    'timestamp' => now()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid 2FA code'
                ], 401);
            }
        }

        if (!$user->wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        // Log successful private key access (anonymized)
        Log::info('Private key accessed successfully', [
            'user_hash' => hash('sha256', $user->id . env('APP_KEY')),
            'timestamp' => now()
        ]);

        // Clear rate limiting on successful access
        RateLimiter::clear($key);

        return response()->json([
            'success' => true,
            'private_key' => decrypt($user->wallet->private_key),
            'expires_in' => 300 // 5 minutes
        ]);
    }

    /**
     * Update wallet configuration
     */
    public function updateWallet(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'password' => 'required|string',
            'address' => 'required|string|regex:/^T[A-Za-z0-9]{33}$/',
            'private_key' => 'required|string'
        ]);

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password'
            ], 401);
        }

        // Update wallet
        $user->wallet()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $request->address,
                'private_key' => encrypt($request->private_key)
            ]
        );

        Log::info('Wallet updated', [
            'user_id' => $user->id,
            'new_address' => $request->address
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet updated successfully'
        ]);
    }

    /**
     * Generate new wallet (admin only)
     */
    public function generateWallet(Request $request)
    {
        // Only allow admin to generate wallets
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        try {
            $walletService = app(\App\Services\TronWalletService::class);
            $wallet = $walletService->createWallet();

            return response()->json([
                'success' => true,
                'wallet' => $wallet
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate wallet', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate wallet'
            ], 500);
        }
    }
} 