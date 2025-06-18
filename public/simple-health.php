<?php
// Ultra-simple health check
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'message' => 'TronLive is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
], JSON_PRETTY_PRINT);
?> 