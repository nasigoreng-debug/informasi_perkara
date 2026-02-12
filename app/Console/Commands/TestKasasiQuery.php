<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Config\SatkerConfig;
use App\Services\LaporanKasasiServiceL10;
use Illuminate\Support\Facades\DB;

class TestKasasiQuery extends Command
{
    protected $signature = 'kasasi:test {tahun?}';
    protected $description = 'Test query laporan kasasi berdasarkan TAHUN KASASI';

    public function handle(LaporanKasasiServiceL10 $kasasiService)
    {
        $tahun = $this->argument('tahun') ?? date('Y');

        $this->info("================================================");
        $this->info("    TEST LAPORAN KASASI TAHUN {$tahun}");
        $this->info("    BERDASARKAN TANGGAL PENDAFTARAN KASASI");
        $this->info("================================================");
        $this->newLine();

        $totals = $kasasiService->getTotalPerSatker($tahun);
        $grandTotal = $kasasiService->getGrandTotal($tahun);

        $headers = ['No', 'Pengadilan Agama', 'Total Kasasi'];
        $rows = [];

        foreach ($totals as $total) {
            $rows[] = [
                $total->nomor_urut,
                $total->pengadilan_agama,
                $total->total
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("📊 GRAND TOTAL TAHUN {$tahun}: {$grandTotal} perkara kasasi");

        if ($grandTotal > 0) {
            $this->newLine();
            $this->info("✅ Data tersedia untuk tahun {$tahun}");

            // TAMPILKAN SAMPLE DARI BANDUNG
            try {
                $sample = DB::connection('bandung')
                    ->select("
                        SELECT 
                            pb.nomor_perkara_pn,
                            pb.nomor_perkara_banding,
                            pk.nomor_perkara_kasasi,
                            pk.tanggal_pendaftaran_kasasi
                        FROM bandung.perkara_kasasi pk
                        INNER JOIN bandung.perkara_banding pb 
                            ON pk.perkara_id = pb.perkara_id
                        WHERE pk.nomor_perkara_kasasi IS NOT NULL
                            AND pb.nomor_perkara_banding IS NOT NULL
                            AND YEAR(pk.tanggal_pendaftaran_kasasi) = ?
                        LIMIT 3
                    ", [$tahun]);

                if (!empty($sample)) {
                    $this->newLine();
                    $this->info("📋 SAMPLE DATA DARI BANDUNG:");
                    foreach ($sample as $row) {
                        $this->line("  {$row->nomor_perkara_pn} → {$row->nomor_perkara_kasasi} ({$row->tanggal_pendaftaran_kasasi})");
                    }
                }
            } catch (\Exception $e) {
                // skip
            }
        } else {
            $this->warn("⚠️ Tidak ada data kasasi untuk tahun {$tahun}");
        }
    }
}
