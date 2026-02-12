<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan commands di sini
     */
    protected $commands = [
        \App\Console\Commands\GenerateSatkerModelsLaravel10::class,
        \App\Console\Commands\TestDatabaseConnections::class,
        \App\Console\Commands\TestKasasiQuery::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('kasasi:test')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
