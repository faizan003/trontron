<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class SecureApiController extends Controller
{
    /**
     * Get API configuration for authenticated users
     */
    public function getApiConfig(Request $request)
    {
        // Rate limiting for API config access
        $key = 'api-config-access:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many requests. Please wait {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key);

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'config' => [
                'trongrid_api_key' => env('TRONGRID_API_KEY'),
                'network' => 'testnet',
                'api_url' => 'https://nile.trongrid.io'
            ]
        ]);
    }

    /**
     * Get public API configuration for registration (no auth required)
     */
    public function getPublicApiConfig(Request $request)
    {
        // Rate limiting for public API config access
        $key = 'public-api-config:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many requests. Please wait {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key);

        $trongridApiKey = env('TRONGRID_API_KEY');
        
        // Check if API key is configured
        if (!$trongridApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'TronGrid API key not configured on server',
                'debug' => [
                    'env' => app()->environment(),
                    'config_cached' => app()->configurationIsCached(),
                ]
            ], 500);
        }

        return response()->json([
            'success' => true,
            'hasKey' => true,
            'config' => [
                'trongrid_api_key' => $trongridApiKey,
                'network' => 'testnet',
                'api_url' => 'https://nile.trongrid.io'
            ]
        ]);
    }
} 