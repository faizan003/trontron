<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\ApiConfig;

class ConfigController extends Controller
{
    /**
     * Get TronGrid configuration for public use (registration)
     * This method bypasses /api/ routing issues on Hostinger
     */
    public function getTronConfig(Request $request)
    {
        // Rate limiting for public config access
        $key = 'tron-config:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many requests. Please wait {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key);

        try {
            // Get configuration from encrypted database storage
            $config = ApiConfig::getTronGridConfig();
            
            if (!$config || !$config['trongrid_api_key']) {
                return response()->json([
                    'success' => false,
                    'message' => 'TronGrid configuration not found. Please run: php artisan api:setup',
                    'debug' => [
                        'config_exists' => ApiConfig::where('key_name', 'trongrid_api_key')->exists(),
                        'timestamp' => now()
                    ]
                ], 500);
            }

            return response()->json([
                'success' => true,
                'hasKey' => true,
                'config' => $config,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve configuration: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'timestamp' => now()
                ]
            ], 500);
        }
    }

    /**
     * Simple test endpoint to verify the controller is working
     */
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Config controller is working',
            'timestamp' => now(),
            'server' => $_SERVER['HTTP_HOST'] ?? 'unknown'
        ]);
    }
} 