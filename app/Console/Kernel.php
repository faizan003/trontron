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
