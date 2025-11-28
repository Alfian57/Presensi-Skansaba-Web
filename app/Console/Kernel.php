<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Refresh QR codes daily at midnight
        $schedule->command('qr:refresh')
            ->dailyAt('00:00')
            ->timezone('Asia/Jakarta')
            ->onSuccess(function () {
                \Log::info('QR codes refreshed successfully via scheduler');
            })
            ->onFailure(function () {
                \Log::error('Failed to refresh QR codes via scheduler');
            });

        // Create daily attendance records at 1 AM on working days
        $schedule->command('attendance:create')
            ->dailyAt('01:00')
            ->timezone('Asia/Jakarta')
            ->weekdays() // Monday to Friday
            ->when(function () {
                // Skip if today is a holiday
                return ! \App\Models\Holiday::whereDate('date', today())->exists();
            })
            ->onSuccess(function () {
                \Log::info('Daily attendance created successfully via scheduler');
            })
            ->onFailure(function () {
                \Log::error('Failed to create daily attendance via scheduler');
            });

        // Backup database weekly (optional - comment out if not needed)
        // $schedule->command('backup:run')->weekly()->sundays()->at('02:00');
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
