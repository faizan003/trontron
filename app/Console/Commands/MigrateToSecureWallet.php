<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SecureWallet;

class MigrateToSecureWallet extends Command
{
    protected $signature = 'wallet:migrate-secure';
    protected $description = 'Migrate wallet credentials from .env to encrypted database storage';

    public function handle()
    {
        $this->info('🔐 Migrating wallet credentials to secure encrypted storage...');

        try {
            // Get current credentials from .env
            $adminAddress = env('ADMIN_WALLET_ADDRESS');
            $adminPrivateKey = env('ADMIN_WALLET_PRIVATE_KEY');

            if (!$adminAddress || !$adminPrivateKey) {
                $this->error('❌ Admin wallet credentials not found in .env file');
                return 1;
            }

            // Check if already migrated
            $existing = SecureWallet::where('wallet_type', 'admin')->where('is_active', true)->first();
            if ($existing) {
                // Check if the credentials are the same
                if ($existing->verifyPrivateKey($adminPrivateKey)) {
                    $this->info('✅ Admin wallet with same credentials already exists and is active');
                    $this->info("📊 Wallet ID: {$existing->id}");
                    $this->info("🔑 Address: " . $existing->getDecryptedAddress());
                    return 0;
                }

                $this->warn('⚠️  Admin wallet already exists in secure storage with different credentials');
                
                if (!$this->confirm('Do you want to rotate to new credentials?')) {
                    return 0;
                }
                
                // Deactivate existing and clean up any old inactive wallets with same credentials
                $existing->update(['is_active' => false]);
                
                // Clean up any old wallets with same new credentials to avoid constraint violation
                $newKeyHash = hash('sha256', $adminPrivateKey);
                SecureWallet::where('wallet_type', 'admin')
                          ->where('key_hash', $newKeyHash)
                          ->delete();
                
                $this->info('🔄 Rotating to new wallet credentials...');
            }

            // Store in encrypted database
            $secureWallet = SecureWallet::storeSecureWallet(
                'admin',
                $adminAddress,
                $adminPrivateKey,
                10000 // 10,000 TRX limit for hot wallet
            );

            $this->info('✅ Admin wallet credentials successfully stored in encrypted database');
            $this->info("📊 Wallet ID: {$secureWallet->id}");
            $this->info("🔑 Key Hash: " . substr($secureWallet->key_hash, 0, 16) . "...");

            // Verify the storage
            if ($secureWallet->verifyPrivateKey($adminPrivateKey)) {
                $this->info('✅ Verification successful - credentials match');
            } else {
                $this->error('❌ Verification failed - credentials do not match');
                return 1;
            }

            $this->warn('🚨 SECURITY NOTICE:');
            $this->warn('1. Remove ADMIN_WALLET_* from your .env file');
            $this->warn('2. Update your application to use SecureWallet model');
            $this->warn('3. Restart your application');

            // Clean up old inactive wallets (keep only last 3 for audit trail)
            $oldWallets = SecureWallet::where('wallet_type', 'admin')
                                    ->where('is_active', false)
                                    ->orderBy('created_at', 'desc')
                                    ->skip(3)
                                    ->get();
            
            if ($oldWallets->count() > 0) {
                $oldWallets->each->delete();
                $this->info("🧹 Cleaned up {$oldWallets->count()} old wallet records");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
            return 1;
        }
    }
} 