<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiConfig;

class SetupApiConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:setup {--key= : TronGrid API Key} {--network=testnet : Network (testnet/mainnet)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup encrypted API configuration in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = $this->option('key') ?: env('TRONGRID_API_KEY');
        $network = $this->option('network');

        if (!$apiKey) {
            $apiKey = $this->ask('Enter your TronGrid API Key');
        }

        if (!$apiKey) {
            $this->error('API Key is required!');
            return 1;
        }

        try {
            $config = ApiConfig::setTronGridConfig($apiKey, $network);
            
            $this->info('✅ API Configuration saved successfully!');
            $this->table(['Setting', 'Value'], [
                ['Key Name', 'trongrid_api_key'],
                ['Network', $config->network],
                ['API URL', $config->api_url],
                ['Status', $config->is_active ? 'Active' : 'Inactive'],
                ['Encrypted', 'Yes'],
                ['Created', $config->created_at->format('Y-m-d H:i:s')]
            ]);

            // Test the configuration
            $testConfig = ApiConfig::getTronGridConfig();
            if ($testConfig && $testConfig['trongrid_api_key']) {
                $this->info('✅ Configuration test passed - API key can be retrieved and decrypted');
            } else {
                $this->error('❌ Configuration test failed - Unable to retrieve API key');
            }

        } catch (\Exception $e) {
            $this->error('❌ Failed to save API configuration: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
