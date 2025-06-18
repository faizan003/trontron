<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor extends Command
{
    protected $signature = 'monitor:performance';
    protected $description = 'Monitor application performance metrics';

    public function handle()
    {
        $metrics = [
            'timestamp' => now(),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'active_stakings' => DB::table('stakings')->where('status', 'active')->count(),
            'total_users' => DB::table('users')->count(),
            'cache_hits' => Cache::get('cache_hits', 0),
            'db_slow_queries' => $this->getSlowQueryCount(),
        ];

        // Log metrics
        \Log::info('Performance Metrics', $metrics);
        
        // Alert if memory usage is high
        if ($metrics['memory_usage'] > 200 * 1024 * 1024) { // 200MB
            \Log::warning('High memory usage detected', $metrics);
        }

        $this->info('Performance monitoring completed');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Memory Usage', $this->formatBytes($metrics['memory_usage'])],
                ['Peak Memory', $this->formatBytes($metrics['peak_memory'])],
                ['Active Stakings', $metrics['active_stakings']],
                ['Total Users', $metrics['total_users']],
                ['Slow Queries', $metrics['db_slow_queries']],
            ]
        );
    }

    private function getSlowQueryCount()
    {
        try {
            $result = DB::select("SHOW GLOBAL STATUS LIKE 'Slow_queries'");
            return $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes, 1024));
        return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
    }
} 