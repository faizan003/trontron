<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;

class UpdateCachedBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balances:update {--batch-size=50 : Number of wallets to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cached wallet balances from TRON blockchain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchSize = $this->option('batch-size');
        $totalWallets = Wallet::count();
        
        $this->info("Updating cached balances for {$totalWallets} wallets (batch size: {$batchSize})");
        
        $progressBar = $this->output->createProgressBar($totalWallets);
        $progressBar->start();
        
        $updated = 0;
        $errors = 0;
        
        Wallet::chunk($batchSize, function ($wallets) use (&$updated, &$errors, $progressBar) {
            foreach ($wallets as $wallet) {
                try {
                    $balance = $this->getWalletBalance($wallet->address);
                    
                    // Update the cached balance
                    $wallet->update(['trx_balance' => $balance]);
                    $updated++;
                    
                    // Small delay to avoid hitting API rate limits
                    usleep(100000); // 0.1 seconds
                    
                } catch (\Exception $e) {
                    $this->error("Failed to update balance for wallet {$wallet->address}: " . $e->getMessage());
                    $errors++;
                }
                
                $progressBar->advance();
            }
        });
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Balance update completed:");
        $this->info("- Updated: {$updated} wallets");
        $this->info("- Errors: {$errors} wallets");
        
        return Command::SUCCESS;
    }
    
    /**
     * Get wallet balance from TRON testnet
     */
    private function getWalletBalance($address)
    {
        try {
            $response = Http::timeout(10)->get("https://nile.trongrid.io/v1/accounts/{$address}");
            
            if ($response->successful()) {
                $data = $response->json();
                $balanceInSun = $data['data'][0]['balance'] ?? 0;
                // Convert SUN to TRX (1 TRX = 1,000,000 SUN)
                return $balanceInSun / 1000000;
            }
            
            return 0;
        } catch (\Exception $e) {
            throw new \Exception("API call failed: " . $e->getMessage());
        }
    }
}
