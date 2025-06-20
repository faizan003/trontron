<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ProcessDailyInterest::class,
        Commands\CheckSchedulerStatus::class,
        Commands\UpdateStakingProgress::class,
        Commands\ProcessDailyInterestOptimized::class,
        Commands\PerformanceMonitor::class,
        Commands\RotateAdminWallet::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * These jobs run at specific intervals or at a specific time.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('staking:process-daily-interest')
                ->everyMinute()
                ->withoutOverlapping(5)
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/staking-interest.log'))
                ->before(function () {
                    file_put_contents(
                        storage_path('logs/cron-debug.log'),
                        "[" . date('Y-m-d H:i:s') . "] Scheduler starting\n",
                        FILE_APPEND
                    );
                })
                ->after(function () {
                    file_put_contents(
                        storage_path('logs/cron-debug.log'),
                        "[" . date('Y-m-d H:i:s') . "] Scheduler finished\n",
                        FILE_APPEND
                    );
                })
                ->evenInMaintenanceMode()
                ->skip(function () {
                    return false;
                });

        $schedule->command('staking:update-progress')
                ->everyMinute()
                ->withoutOverlapping(5)
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/staking-progress.log'))
                ->before(function () {
                    file_put_contents(
                        storage_path('logs/cron-debug.log'),
                        "[" . date('Y-m-d H:i:s') . "] Progress update starting\n",
                        FILE_APPEND
                    );
                })
                ->after(function () {
                    file_put_contents(
                        storage_path('logs/cron-debug.log'),
                        "[" . date('Y-m-d H:i:s') . "] Progress update finished\n",
                        FILE_APPEND
                    );
                })
                ->evenInMaintenanceMode();

        // Wallet rotation - monthly on 1st day at 3 AM
        $schedule->command('wallet:rotate-admin')
                ->monthlyOn(1, '03:00')
                ->withoutOverlapping(60)
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/wallet-rotation.log'))
                ->before(function () {
                    Log::info('Monthly wallet rotation starting');
                })
                ->after(function () {
                    Log::info('Monthly wallet rotation completed');
                })
                ->evenInMaintenanceMode();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
