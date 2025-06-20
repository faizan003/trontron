<?php
/**
 * Simple test script to verify TronGrid config endpoints
 * Usage: php test-config-endpoints.php [base_url]
 * Example: php test-config-endpoints.php https://tronxearn.site
 */

$baseUrl = $argv[1] ?? 'http://localhost:8000';

echo "🧪 Testing TronGrid Config Endpoints\n";
echo "Base URL: $baseUrl\n\n";

$endpoints = [
    '/config-test' => 'Test endpoint (should always work)',
    '/tron-config' => 'Primary config endpoint',
    '/get-tron-config' => 'Ultra-simple fallback endpoint'
];

foreach ($endpoints as $endpoint => $description) {
    echo "Testing: $endpoint ($description)\n";
    
    $url = $baseUrl . $endpoint;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'User-Agent: Config-Test-Script'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ CURL Error: $error\n";
    } elseif ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['success'])) {
            if ($data['success']) {
                echo "✅ SUCCESS: " . ($data['message'] ?? 'Config retrieved') . "\n";
                if (isset($data['config']['trongrid_api_key'])) {
                    $keyLength = strlen($data['config']['trongrid_api_key']);
                    echo "   API Key: Present (length: $keyLength)\n";
                    echo "   Network: " . ($data['config']['network'] ?? 'unknown') . "\n";
                }
            } else {
                echo "❌ FAILED: " . ($data['message'] ?? 'Unknown error') . "\n";
            }
        } else {
            echo "❌ Invalid JSON response\n";
        }
    } else {
        echo "❌ HTTP $httpCode: $response\n";
    }
    
    echo "\n";
}

echo "🏁 Test completed!\n"; 