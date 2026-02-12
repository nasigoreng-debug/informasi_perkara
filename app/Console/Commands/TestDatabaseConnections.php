<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Config\SatkerConfig;

class TestDatabaseConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:test-connections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all 26 database connections PTA Jawa Barat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('    TEST KONEKSI 26 DATABASE SATKER');
        $this->info('    PENGADILAN AGAMA SE-JAWA BARAT');
        $this->info('========================================');
        $this->newLine();

        $satkers = SatkerConfig::getConnections();
        $headers = ['No', 'Database', 'Satker', 'Status', 'Message'];
        $rows = [];
        $no = 1;
        $success = 0;
        $failed = 0;

        foreach ($satkers as $satker) {
            $namaSatker = SatkerConfig::getNamaSatker($satker);

            try {
                DB::connection($satker)->getPdo();
                $database = DB::connection($satker)->getDatabaseName();

                $rows[] = [
                    $no,
                    $database,
                    $namaSatker,
                    '✓ CONNECTED',
                    'OK'
                ];
                $success++;
            } catch (\Exception $e) {
                $rows[] = [
                    $no,
                    $satker,
                    $namaSatker,
                    '✗ FAILED',
                    $e->getMessage()
                ];
                $failed++;
            }

            $no++;
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("✅ Summary: {$success} Connected, {$failed} Failed");

        if ($failed > 0) {
            $this->warn('⚠ Ada ' . $failed . ' koneksi yang gagal. Periksa konfigurasi database Anda!');
            return Command::FAILURE;
        }

        $this->info('🎉 Semua koneksi database berhasil!');
        return Command::SUCCESS;
    }
}
