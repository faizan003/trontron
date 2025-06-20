<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TronWalletService
{
    private $apiKey;
    private $baseUrl = 'https://nile.trongrid.io';

    public function __construct()
    {
        // Get API key from encrypted database storage
        $config = \App\Models\ApiConfig::getTronGridConfig();
        $this->apiKey = $config['trongrid_api_key'] ?? null;
        
        if (!$this->apiKey) {
            throw new \Exception('TronGrid API key not configured. Please run: php artisan api:setup');
        }
    }

    public function createWallet()
    {
        $response = Http::withHeaders([
            'TRON-PRO-API-KEY' => $this->apiKey,
            'Accept' => 'application/json'
        ])->post($this->baseUrl . '/wallet/createaccount');

        if ($response->successful()) {
            $data = $response->json();
            return [
                'address' => $data['address'],
                'private_key' => $data['privateKey']
            ];
        }

        throw new \Exception('Failed to create TRON wallet');
    }

    public function getBalance($address)
    {
        $response = Http::withHeaders([
            'TRON-PRO-API-KEY' => $this->apiKey,
            'Accept' => 'application/json'
        ])->get($this->baseUrl . "/v1/accounts/{$address}");

        if ($response->successful()) {
            $data = $response->json();
            return $data['balance'] ?? 0;
        }

        return 0;
    }
}
