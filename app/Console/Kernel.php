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
        // Sync Perkara Diterima - Jam 01:00, 09:00, 12:00, 15:00
        $schedule->command('sync:perkara-diterima')
            ->cron('0 1,9,12,15 * * *')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_perkara_diterima.log'));

        // Sync Perkara Diputus - Jam 01:30, 09:30, 12:30, 15:30
        $schedule->command('sync:perkara-diputus')
            ->cron('30 1,9,12,15 * * *')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_perkara_diputus.log'));

        // Sync Sisa Panjar - Jam 02:00, 10:00, 13:00, 16:00
        $schedule->command('sync:sisa-panjar')
            ->cron('0 2,10,13,16 * * *')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_sisa_panjar.log'));

        // Sync Saldo Minus - Jam 02:30, 10:30, 13:30, 16:30
        $schedule->command('sync:saldo-minus')
            ->cron('30 2,10,13,16 * * *')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_saldo_minus.log'));

        // Sync Non-Mediasi - Jam 03:00, 11:00, 14:00, 17:00
        $schedule->command('sync:non-mediasi')
            ->cron('0 3,11,14,17 * * *')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_non_mediasi.log'));

        // Sync BHT Akta - Jam 03:30, 11:30, 14:30, 17:30
        $schedule->command('sync:bht-akta')
            ->cron('30 3,11,14,17 * * *')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync_bht_akta.log'));
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
