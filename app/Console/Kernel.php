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
        // Jam 01:00 - Perkara Diterima
        $schedule->command('sync:perkara-diterima')->dailyAt('01:00');

        // Jam 01:30 - Perkara Diputus
        $schedule->command('sync:perkara-diputus')->dailyAt('01:30');

        // Jam 02:00 - Sisa Panjar (5 Tahun Terakhir)
        $schedule->command('sync:sisa-panjar')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/sync_panjar.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
