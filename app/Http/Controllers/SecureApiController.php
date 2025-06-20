<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\ApiConfig;

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

        // Get configuration from encrypted database storage
        $config = ApiConfig::getTronGridConfig();
        
        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'API configuration not found. Please contact administrator.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'config' => $config
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

        // Get configuration from encrypted database storage
        $config = ApiConfig::getTronGridConfig();
        
        if (!$config || !$config['trongrid_api_key']) {
            return response()->json([
                'success' => false,
                'message' => 'TronGrid API key not configured on server. Please run: php artisan api:setup',
                'debug' => [
                    'env' => app()->environment(),
                    'config_in_db' => ApiConfig::where('key_name', 'trongrid_api_key')->exists(),
                ]
            ], 500);
        }

        return response()->json([
            'success' => true,
            'hasKey' => true,
            'config' => $config
        ]);
    }
} 