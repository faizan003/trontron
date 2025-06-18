<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TronWalletService
{
    private $apiKey;
    private $baseUrl = 'https://nile.trongrid.io';

    public function __construct()
    {
        $this->apiKey = env('TRONGRID_API_KEY');
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
