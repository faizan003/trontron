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

        return response()->json([
            'success' => true,
            'config' => [
                'trongrid_api_key' => env('TRONGRID_API_KEY'),
                'network' => 'testnet',
                'api_url' => 'https://nile.trongrid.io'
            ]
        ]);
    }
} 