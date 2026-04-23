<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\GenerateSatkerModelsLaravel10::class,
        \App\Console\Commands\TestDatabaseConnections::class,
        \App\Console\Commands\TestKasasiQuery::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Sync Perkara Diterima - Setiap hari jam 01:00
        $schedule->command('sync:perkara-diterima')
            ->dailyAt('01:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_perkara_diterima.log'));

        // Sync Perkara Diputus - Setiap hari jam 01:30
        $schedule->command('sync:perkara-diputus')
            ->dailyAt('01:30')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_perkara_diputus.log'));

        // Sync Sisa Panjar (5 Tahun Terakhir) - Setiap hari jam 02:00
        $schedule->command('sync:sisa-panjar')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_sisa_panjar.log'));

        // Sync Saldo Minus (2 Tahun Terakhir) - Setiap hari jam 02:30
        $schedule->command('sync:saldo-minus')
            ->dailyAt('02:30')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_saldo_minus.log'));

        // Sync Non-Mediasi - Jalankan setiap hari jam 3 pagi
        $schedule->command('sync:non-mediasi')->dailyAt('03:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
