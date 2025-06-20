<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SecureWallet;
use App\Services\TronWalletService;
use Illuminate\Support\Facades\Log;

class RotateAdminWallet extends Command
{
    protected $signature = 'wallet:rotate-admin {--force : Force rotation even if not due}';
    protected $description = 'Rotate admin wallet for security (monthly)';

    public function handle()
    {
        $this->info('🔄 Admin Wallet Rotation Process Starting...');

        try {
            // Get current admin wallet
            $currentWallet = SecureWallet::getAdminWallet();
            
            if (!$currentWallet) {
                $this->error('❌ No active admin wallet found');
                return 1;
            }

            // Check if rotation is due (30 days)
            $daysSinceCreation = $currentWallet->created_at->diffInDays(now());
            $isRotationDue = $daysSinceCreation >= 30;
            
            if (!$isRotationDue && !$this->option('force')) {
                $this->info("✅ Rotation not due yet. Current wallet is {$daysSinceCreation} days old.");
                $this->info("💡 Use --force to rotate anyway, or wait " . (30 - $daysSinceCreation) . " more days.");
                return 0;
            }

            $this->warn("🚨 IMPORTANT: This will rotate your admin wallet!");
            $this->warn("📋 Current wallet age: {$daysSinceCreation} days");
            $this->warn("🔑 Current address: " . $currentWallet->getDecryptedAddress());

            if (!$this->option('force') && !$this->confirm('Do you want to continue with wallet rotation?')) {
                $this->info('Rotation cancelled by user.');
                return 0;
            }

            // Generate new wallet
            $this->info('🔐 Generating new admin wallet...');
            $walletService = new TronWalletService();
            $newWallet = $walletService->createWallet();

            $this->info('✅ New wallet generated:');
            $this->info("📍 New Address: {$newWallet['address']}");
            $this->info("🔑 Private Key: " . substr($newWallet['private_key'], 0, 8) . "...");

            // Get current balance
            $currentAddress = $currentWallet->getDecryptedAddress();
            $currentBalance = $walletService->getBalance($currentAddress);
            $balanceTRX = $currentBalance / 1_000_000;

            $this->info("💰 Current balance: {$balanceTRX} TRX");

            if ($balanceTRX > 0) {
                $this->warn("⚠️  MANUAL STEP REQUIRED:");
                $this->warn("1. Transfer {$balanceTRX} TRX from old wallet to new wallet");
                $this->warn("2. Old: {$currentAddress}");
                $this->warn("3. New: {$newWallet['address']}");
                $this->warn("4. Consider using mixing service for privacy");
                
                if (!$this->confirm('Have you transferred the funds to the new wallet?')) {
                    $this->error('❌ Please transfer funds before continuing rotation.');
                    return 1;
                }
            }

            // Rotate the wallet in database
            $this->info('💾 Updating database with new wallet...');
            $newSecureWallet = $currentWallet->rotateWallet(
                $newWallet['address'],
                $newWallet['private_key']
            );

            // Log the rotation (without sensitive data)
            Log::info('Admin wallet rotated successfully', [
                'old_wallet_id' => $currentWallet->id,
                'new_wallet_id' => $newSecureWallet->id,
                'old_address' => $currentAddress,
                'new_address' => $newWallet['address'],
                'rotation_reason' => $this->option('force') ? 'manual' : 'scheduled',
                'days_since_last' => $daysSinceCreation
            ]);

            $this->info('✅ Wallet rotation completed successfully!');
            $this->info("🆔 New wallet ID: {$newSecureWallet->id}");
            $this->info("📍 New address: {$newWallet['address']}");
            $this->info("🕐 Next rotation due: " . now()->addDays(30)->format('Y-m-d'));

            $this->warn('🔒 SECURITY REMINDER:');
            $this->warn('1. The old wallet is now deactivated');
            $this->warn('2. All future transactions will use the new wallet');
            $this->warn('3. Keep the old wallet keys secure for audit purposes');
            $this->warn('4. Consider using a mixing service for maximum privacy');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Wallet rotation failed: ' . $e->getMessage());
            Log::error('Admin wallet rotation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 