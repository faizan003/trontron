<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ApiConfig extends Model
{
    protected $fillable = [
        'key_name',
        'encrypted_value',
        'network',
        'api_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the decrypted API key value
     */
    public function getDecryptedValueAttribute()
    {
        try {
            return Crypt::decryptString($this->encrypted_value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Set the encrypted API key value
     */
    public function setApiValue($value)
    {
        $this->encrypted_value = Crypt::encryptString($value);
        return $this;
    }

    /**
     * Get active TronGrid configuration
     */
    public static function getTronGridConfig()
    {
        $config = self::where('key_name', 'trongrid_api_key')
                     ->where('is_active', true)
                     ->first();

        if (!$config) {
            return null;
        }

        return [
            'trongrid_api_key' => $config->decrypted_value,
            'network' => $config->network,
            'api_url' => $config->api_url
        ];
    }

    /**
     * Store or update TronGrid API configuration
     */
    public static function setTronGridConfig($apiKey, $network = 'testnet', $apiUrl = null)
    {
        $defaultUrls = [
            'testnet' => 'https://nile.trongrid.io',
            'mainnet' => 'https://api.trongrid.io'
        ];

        $apiUrl = $apiUrl ?: $defaultUrls[$network];

        return self::updateOrCreate(
            ['key_name' => 'trongrid_api_key'],
            [
                'encrypted_value' => Crypt::encryptString($apiKey),
                'network' => $network,
                'api_url' => $apiUrl,
                'is_active' => true
            ]
        );
    }
}
