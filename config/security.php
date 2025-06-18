<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for sensitive operations
    |
    */

    'rate_limits' => [
        'login_attempts' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
        ],
        'password_reset' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
        ],
        'private_key_access' => [
            'max_attempts' => 3,
            'decay_minutes' => 30,
        ],
        'api_requests' => [
            'max_attempts' => 100,
            'decay_minutes' => 1,
        ],
        'staking_operations' => [
            'max_attempts' => 10,
            'decay_minutes' => 5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure security headers for better protection
    |
    */

    'headers' => [
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'strict_transport_security' => 'max-age=31536000; includeSubDomains',
        'content_security_policy' => [
            'default-src' => "'self'",
            'script-src' => "'self' 'unsafe-inline'",
            'style-src' => "'self' 'unsafe-inline'",
            'img-src' => "'self' data:",
            'font-src' => "'self'",
            'connect-src' => "'self'",
            'frame-ancestors' => "'none'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Configure session security settings
    |
    */

    'session' => [
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'strict',
        'lifetime' => 120, // 2 hours
        'idle_timeout' => 30, // 30 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    |
    | Configure input validation rules
    |
    */

    'validation' => [
        'wallet_address_regex' => '/^T[A-Za-z0-9]{33}$/',
        'max_amount' => 999999999,
        'min_amount' => 0.000001,
        'max_string_length' => 255,
        'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf'],
        'max_file_size' => 2048, // KB
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Settings
    |--------------------------------------------------------------------------
    |
    | Configure encryption for sensitive data
    |
    */

    'encryption' => [
        'algorithm' => 'aes-256-gcm',
        'key_rotation_days' => 90,
        'backup_keys_count' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Configure audit logging for sensitive operations
    |
    */

    'audit' => [
        'enabled' => true,
        'log_channel' => 'audit',
        'events' => [
            'login',
            'logout', 
            'password_change',
            'private_key_access',
            'staking_create',
            'staking_withdraw',
            'wallet_update',
            'admin_actions',
        ],
        'retention_days' => 365,
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Security
    |--------------------------------------------------------------------------
    |
    | Configure IP-based security measures
    |
    */

    'ip_security' => [
        'enabled' => true,
        'whitelist' => env('IP_WHITELIST', ''),
        'blacklist' => env('IP_BLACKLIST', ''),
        'geo_blocking' => [
            'enabled' => false,
            'allowed_countries' => [],
            'blocked_countries' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Configure 2FA settings
    |
    */

    'two_factor' => [
        'required_for' => [
            'private_key_access' => true,
            'large_withdrawals' => true,
            'admin_actions' => true,
        ],
        'backup_codes_count' => 8,
        'window' => 1, // Time window for TOTP
    ],

]; 