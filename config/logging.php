<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel - GHOST MODE
    |--------------------------------------------------------------------------
    */

    'default' => env('LOG_CHANNEL', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel - GHOST MODE
    |--------------------------------------------------------------------------
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels - GHOST CONFIGURATION
    |--------------------------------------------------------------------------
    */

    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => ['null'],
            'ignore_exceptions' => true,
        ],

        'single' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'daily' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'slack' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'syslog' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'errorlog' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

    ],

];
