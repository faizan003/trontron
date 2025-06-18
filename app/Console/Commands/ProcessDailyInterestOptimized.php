<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessDailyInterestOptimized extends Command
{
    protected $signature = 'staking:process-daily-interest-optimized {--chunk=1000 : Number of records to process at once}';
    protected $description = 'Optimized daily interest processing for high-volume users';

    public function handle()
    {
        $startTime = microtime(true);
        $chunkSize = (int) $this->option('chunk');
        
        $this->info("Starting optimized daily interest processing with chunk size: {$chunkSize}");

        try {
            // Use batch processing with proper indexing
            $processedCount = 0;
            $errorCount = 0;

            // Process in chunks to avoid memory issues
            Staking::where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('last_reward_at')
                        ->orWhere('last_reward_at', '<=', now()->subHours(24));
                })
                ->with(['plan', 'user']) // Eager load to reduce queries
                ->chunk($chunkSize, function ($stakings) use (&$processedCount, &$errorCount) {
                    
                    $batchUpdates = [];
                    $userEarningsUpdates = [];
                    
                    foreach ($stakings as $staking) {
                        try {
                            $dailyEarnings = ($staking->amount * $staking->plan->interest_rate) / 100;
                            $daysElapsed = $staking->staked_at->diffInDays(now());
                            $isCompleted = $daysElapsed >= $staking->plan->duration;

                            // Prepare batch update data
                            $updateData = [
                                'earned_amount' => DB::raw("earned_amount + {$dailyEarnings}"),
                                'last_reward_at' => now(),
                                'updated_at' => now()
                            ];

                            if ($isCompleted) {
                                $updateData['status'] = 'completed';
                                $updateData['end_at'] = now();
                            }

                            $batchUpdates[] = [
                                'id' => $staking->id,
                                'update' => $updateData,
                                'earnings' => $dailyEarnings
                            ];

                            // Track user earnings for batch update
                            if (!isset($userEarningsUpdates[$staking->user_id])) {
                                $userEarningsUpdates[$staking->user_id] = 0;
                            }
                            $userEarningsUpdates[$staking->user_id] += $dailyEarnings;

                            $processedCount++;
                        } catch (\Exception $e) {
                            $errorCount++;
                            Log::error("Error processing staking {$staking->id}: " . $e->getMessage());
                        }
                    }

                    // Execute batch updates
                    if (!empty($batchUpdates)) {
                        $this->executeBatchUpdates($batchUpdates, $userEarningsUpdates);
                    }
                    
                    $this->info("Processed chunk: {$processedCount} stakings");
                });

            $executionTime = round(microtime(true) - $startTime, 2);
            
            $this->info("✅ Processing completed successfully!");
            $this->info("📊 Statistics:");
            $this->info("   - Processed: {$processedCount} stakings");
            $this->info("   - Errors: {$errorCount}");
            $this->info("   - Execution time: {$executionTime} seconds");
            $this->info("   - Average per record: " . round($executionTime / max(1, $processedCount), 4) . " seconds");

            // Cache the last run time for monitoring
            Cache::put('last_interest_processing', [
                'timestamp' => now()->toISOString(),
                'processed_count' => $processedCount,
                'execution_time' => $executionTime,
                'errors' => $errorCount
            ], now()->addHours(25));

        } catch (\Exception $e) {
            $this->error("❌ Processing failed: " . $e->getMessage());
            Log::error('Daily interest processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function executeBatchUpdates(array $batchUpdates, array $userEarningsUpdates)
    {
        DB::beginTransaction();
        
        try {
            // Update stakings in batch
            foreach ($batchUpdates as $update) {
                DB::table('stakings')
                    ->where('id', $update['id'])
                    ->update($update['update']);
            }

            // Update user earnings in batch
            foreach ($userEarningsUpdates as $userId => $totalEarnings) {
                DB::table('users')
                    ->where('id', $userId)
                    ->increment('total_earnings', $totalEarnings);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 