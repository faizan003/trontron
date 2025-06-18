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
                $this->warn('⚠️  Admin wallet already exists in secure storage');
                
                if (!$this->confirm('Do you want to rotate to new credentials?')) {
                    return 0;
                }
                
                // Deactivate existing
                $existing->update(['is_active' => false]);
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

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
            return 1;
        }
    }
} 