<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard with case monitoring data
     */
    public function index(Request $request)
    {
        // 1. Setup Filter dengan Nama Variabel Sesuai Blade (tgl_awal & tgl_akhir)
        $tgl_awal = $request->input('tgl_awal', date('Y') . '-01-01');
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));
        $tahun = date('Y', strtotime($tgl_akhir));

        $db = DB::connection('siappta');

        // 2. Query Kartu Statistik Utama
        $cardData = $this->getCardStatistics($db, $tgl_awal, $tgl_akhir);
        $beban = ($cardData->sisa_lalu ?? 0) + ($cardData->diterima ?? 0);

        // 3. Query Putusan Sela
        $putusanSela = $db->table('perkara')
            ->whereNotNull('tgl_register')
            ->whereBetween('tgl_putusan_sela', [$tgl_awal, $tgl_akhir])
            ->count();

        // 4. Query Rekap E-Court vs Manual
        $rekapEcourt = $this->getEcourtStatistics($db, $tgl_awal, $tgl_akhir);

        // 5. Query Zona Warna (Kecepatan Putusan)
        $zonaWarna = $this->getZoneStatistics($db, $tgl_awal, $tgl_akhir);
        $totalPutus = $this->calculateTotalPutus($zonaWarna);

        // 6. Query Rekap Jenis Perkara & Hakim
        $rekapJenis = $this->getCaseTypeStatistics($db, $tgl_awal, $tgl_akhir);

        return view('dashboard.index', compact(
            'cardData',
            'beban',
            'putusanSela',
            'rekapEcourt',
            'zonaWarna',
            'totalPutus',
            'tgl_awal',   // <--- Sudah sinkron dengan Blade
            'tgl_akhir',  // <--- Sudah sinkron dengan Blade
            'tahun',
            'rekapJenis'
        ));
    }

    /**
     * Get main card statistics
     */
    private function getCardStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')
            ->whereNotNull('tgl_register')
            ->selectRaw("
                SUM(CASE WHEN tgl_register < ? AND (tgl_putusan IS NULL OR tgl_putusan >= ?) THEN 1 ELSE 0 END) AS sisa_lalu,
                SUM(CASE WHEN tgl_register BETWEEN ? AND ? THEN 1 ELSE 0 END) AS diterima,
                SUM(CASE WHEN tgl_putusan BETWEEN ? AND ? THEN 1 ELSE 0 END) AS selesai,
                SUM(CASE WHEN tgl_putusan IS NULL OR tgl_putusan > ? THEN 1 ELSE 0 END) AS sisa
            ", [$tglAwal, $tglAwal, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir, $tglAkhir])
            ->first();
    }

    /**
     * Get E-Court vs Manual statistics
     */
    private function getEcourtStatistics($db, $tglAwal, $tglAkhir)
    {
        $satkers = [
            'bandung',
            'indramayu',
            'majalengka',
            'sumber',
            'ciamis',
            'tasikmalaya',
            'karawang',
            'cimahi',
            'subang',
            'sumedang',
            'purwakarta',
            'sukabumi',
            'cianjur',
            'kuningan',
            'cibadak',
            'cirebon',
            'garut',
            'bogor',
            'bekasi',
            'cibinong',
            'cikarang',
            'depok',
            'tasikkota',
            'banjar',
            'soreang',
            'ngamprah'
        ];

        $unionQuery = null;
        foreach ($satkers as $satker) {
            $query = $db->table("{$satker}.ecourt_banding")->select('nomor_perkara');
            $unionQuery = $unionQuery ? $unionQuery->unionAll($query) : $query;
        }

        return $db->table('perkara as p')
            ->leftJoinSub($unionQuery, 'ec', function ($join) {
                $join->on(DB::raw('TRIM(p.nomor_perkara_pa)'), '=', DB::raw('TRIM(ec.nomor_perkara)'));
            })
            ->whereNotNull('p.tgl_register')
            ->whereBetween('p.tgl_register', [$tglAwal, $tglAkhir])
            ->selectRaw("
                SUM(CASE WHEN ec.nomor_perkara IS NOT NULL THEN 1 ELSE 0 END) as total_ecourt,
                SUM(CASE WHEN ec.nomor_perkara IS NULL THEN 1 ELSE 0 END) as total_manual
            ")
            ->first();
    }

    /**
     * Get zone color statistics (decision speed)
     */
    private function getZoneStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')
            ->whereNotNull('tgl_register')
            ->whereBetween('tgl_putusan', [$tglAwal, $tglAkhir])
            ->selectRaw("
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) <= 30 THEN 1 ELSE 0 END) as hijau_tua,
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) BETWEEN 31 AND 60 THEN 1 ELSE 0 END) as hijau_muda,
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) BETWEEN 61 AND 90 THEN 1 ELSE 0 END) as kuning,
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) > 90 THEN 1 ELSE 0 END) as merah
            ")
            ->first();
    }

    /**
     * Calculate total putus from zone statistics
     */
    private function calculateTotalPutus($zonaWarna)
    {
        return ($zonaWarna->hijau_tua ?? 0) +
            ($zonaWarna->hijau_muda ?? 0) +
            ($zonaWarna->kuning ?? 0) +
            ($zonaWarna->merah ?? 0);
    }

    /**
     * Get case type and judge statistics
     */
    private function getCaseTypeStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')
            ->whereNotNull('tgl_register')
            ->whereBetween('tgl_register', [$tglAwal, $tglAkhir])
            ->selectRaw("
                jenis_perkara as jenis,
                COUNT(*) as total,
                GROUP_CONCAT(DISTINCT nama_km ORDER BY nama_km SEPARATOR '; ') as hakim_penangani
            ")
            ->groupBy('jenis_perkara')
            ->orderBy('total', 'desc')
            ->get();
    }
}
