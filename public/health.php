<?php
// Secure health check for TronLive - No sensitive data exposure
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Initialize health status
$health = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'server' => 'production',
    'checks' => [],
    'info' => []
];

// Add basic PHP info (safe)
$health['info']['php_version'] = PHP_VERSION;
$health['info']['memory_usage'] = round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB';

// Check file system basics (safe)
try {
    $health['checks']['public_writable'] = is_writable('.') ? 'ok' : 'warning';
} catch (Exception $e) {
    $health['checks']['filesystem'] = 'error';
}

// Check Laravel structure (safe)
$laravelFiles = [
    'autoloader' => '../vendor/autoload.php',
    'laravel_bootstrap' => '../bootstrap/app.php',
    'artisan' => '../artisan'
];

foreach ($laravelFiles as $check => $file) {
    try {
        $health['checks'][$check] = file_exists($file) ? 'ok' : 'missing';
    } catch (Exception $e) {
        $health['checks'][$check] = 'error';
    }
}

// Check storage directories (safe)
$storageDirectories = [
    'storage' => '../storage',
    'storage_logs' => '../storage/logs',
    'storage_cache' => '../storage/framework/cache',
    'bootstrap_cache' => '../bootstrap/cache'
];

foreach ($storageDirectories as $check => $dir) {
    try {
        if (is_dir($dir)) {
            $health['checks'][$check] = is_writable($dir) ? 'ok' : 'not_writable';
        } else {
            $health['checks'][$check] = 'missing';
        }
    } catch (Exception $e) {
        $health['checks'][$check] = 'error';
    }
}

// Basic environment check (secure - no content reading)
$health['checks']['env_file'] = file_exists('../.env') ? 'ok' : 'missing';

// Disk space check (safe)
try {
    $diskFree = disk_free_space('.');
    $diskTotal = disk_total_space('.');
    if ($diskTotal > 0) {
        $diskUsage = ($diskTotal - $diskFree) / $diskTotal * 100;
        $health['checks']['disk_space'] = $diskUsage < 90 ? 'ok' : 'warning';
        $health['info']['disk_usage'] = round($diskUsage, 1) . '%';
        $health['info']['disk_free'] = round($diskFree / 1024 / 1024 / 1024, 2) . 'GB';
    }
} catch (Exception $e) {
    $health['checks']['disk_space'] = 'unknown';
}

// Overall status determination
$errors = array_filter($health['checks'], function($status) {
    return in_array($status, ['error', 'missing']);
});

$warnings = array_filter($health['checks'], function($status) {
    return in_array($status, ['warning', 'not_writable']);
});

if (!empty($errors)) {
    $health['status'] = 'error';
} elseif (!empty($warnings)) {
    $health['status'] = 'warning';
}

// Add summary
$health['summary'] = [
    'total_checks' => count($health['checks']),
    'ok_checks' => count(array_filter($health['checks'], function($s) { return $s === 'ok'; })),
    'error_checks' => count($errors),
    'warning_checks' => count($warnings)
];

echo json_encode($health, JSON_PRETTY_PRINT);
?> 