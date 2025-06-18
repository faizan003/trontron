<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ContractBalanceController extends Controller
{
    public function getBalance($address)
    {
        try {
            return Cache::remember('balance_' . $address, 30, function () use ($address) {
                // First try direct API call
                $response = Http::withHeaders([
                    'TRON-PRO-API-KEY' => env('TRONGRID_API_KEY'),
                    'Accept' => 'application/json'
                ])->get("https://nile.trongrid.io/v1/accounts/{$address}");

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['data'][0])) {
                        return response()->json([
                            'success' => true,
                            'balance' => ($data['data'][0]['balance'] ?? 0) / 1_000_000,
                            'address' => $address
                        ]);
                    }
                }

                // If first method fails, try alternative API (mainnet)
                $alternativeResponse = Http::withHeaders([
                    'TRON-PRO-API-KEY' => env('TRONGRID_API_KEY'),
                    'Accept' => 'application/json'
                ])->get("https://apilist.tronscanapi.com/api/account?address={$address}");

                if ($alternativeResponse->successful()) {
                    $data = $alternativeResponse->json();

                    return response()->json([
                        'success' => true,
                        'balance' => ($data['balance'] ?? 0) / 1_000_000,
                        'address' => $address
                    ]);
                }

                // If both methods fail, return 0 balance
                return response()->json([
                    'success' => true,
                    'balance' => 0,
                    'address' => $address
                ]);
            });

        } catch (\Exception $e) {
            // Silent balance check error

            $errorMessage = 'Failed to fetch balance. ';
            if (str_contains($e->getMessage(), '404')) {
                $errorMessage .= 'Address not found.';
            } else if (str_contains($e->getMessage(), '429')) {
                $errorMessage .= 'Too many requests. Please try again later.';
            } else {
                $errorMessage .= 'Please try again later.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
