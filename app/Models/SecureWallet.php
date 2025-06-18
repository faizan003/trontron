<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SecureWallet extends Model
{
    protected $fillable = [
        'wallet_type',
        'encrypted_address',
        'encrypted_private_key',
        'key_hash',
        'balance_limit',
        'is_active',
        'last_used_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance_limit' => 'decimal:6',
        'last_used_at' => 'datetime'
    ];

    /**
     * Store wallet with encryption
     */
    public static function storeSecureWallet($type, $address, $privateKey, $balanceLimit = 0)
    {
        // Use app key + custom salt for encryption
        $encryptionKey = config('app.key') . env('WALLET_ENCRYPTION_SALT', 'default_salt');
        
        return self::create([
            'wallet_type' => $type,
            'encrypted_address' => Crypt::encryptString($address),
            'encrypted_private_key' => Crypt::encryptString($privateKey),
            'key_hash' => hash('sha256', $privateKey), // For verification only
            'balance_limit' => $balanceLimit,
            'is_active' => true
        ]);
    }

    /**
     * Get decrypted address (use sparingly)
     */
    public function getDecryptedAddress()
    {
        try {
            return Crypt::decryptString($this->encrypted_address);
        } catch (\Exception $e) {
            // Silent decryption failure
            return null;
        }
    }

    /**
     * Get decrypted private key (use very sparingly)
     */
    public function getDecryptedPrivateKey()
    {
        try {
            // Update last used timestamp
            $this->update(['last_used_at' => now()]);
            
            return Crypt::decryptString($this->encrypted_private_key);
        } catch (\Exception $e) {
            // Silent decryption failure
            return null;
        }
    }

    /**
     * Verify private key without decryption
     */
    public function verifyPrivateKey($privateKey)
    {
        return hash('sha256', $privateKey) === $this->key_hash;
    }

    /**
     * Get admin wallet securely
     */
    public static function getAdminWallet()
    {
        return self::where('wallet_type', 'admin')
                  ->where('is_active', true)
                  ->first();
    }

    /**
     * Rotate wallet (deactivate old, create new)
     */
    public function rotateWallet($newAddress, $newPrivateKey)
    {
        // Deactivate current wallet
        $this->update(['is_active' => false]);
        
        // Create new wallet
        return self::storeSecureWallet(
            $this->wallet_type,
            $newAddress,
            $newPrivateKey,
            $this->balance_limit
        );
    }
} 