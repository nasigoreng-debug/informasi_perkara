<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $satkers = [
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

    /**
     * DASHBOARD INTERNAL (LOGIN)
     */
    public function index(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y') . '-01-01');
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));
        $tahun = date('Y', strtotime($tgl_akhir));

        $db = DB::connection('siappta');

        $cardData = $this->getCardStatistics($db, $tgl_awal, $tgl_akhir);
        $beban = ($cardData->sisa_lalu ?? 0) + ($cardData->diterima ?? 0);
        $putusanSela = $this->getPutusanSelaCount($db, $tgl_awal, $tgl_akhir);
        $rekapEcourt = $this->getEcourtStatistics($db, $tgl_awal, $tgl_akhir);
        $zonaWarna = $this->getZoneStatistics($db, $tgl_awal, $tgl_akhir);
        $totalPutus = $this->calculateTotalPutus($zonaWarna);
        $rekapJenis = $this->getCaseTypeStatistics($db, $tgl_awal, $tgl_akhir);
        $jenisPutus = $this->getJenisPutusStatistics($db, $tgl_awal, $tgl_akhir);

        // --- SIMPAN LOG KE DATABASE ---
        ActivityLog::create([
            'user_id' => auth()->id() ?? null, // Diperbaiki agar tidak error jika session habis
            'activity' => "Mengakses Dashboard Internal",
            'description' => "Periode: $tgl_awal s.d $tgl_akhir",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('dashboard.index', compact(
            'cardData',
            'beban',
            'putusanSela',
            'rekapEcourt',
            'zonaWarna',
            'totalPutus',
            'tgl_awal',
            'tgl_akhir',
            'tahun',
            'rekapJenis',
            'jenisPutus'
        ));
    }

    /**
     * DASHBOARD PUBLIC (COMMAND CENTER TV)
     */
    public function index_public(Request $request)
    {
        $tgl_awal = date('Y') . '-01-01';
        $tgl_akhir = date('Y-m-d');
        $tahun = date('Y');

        $db = DB::connection('siappta');

        $cardData = $this->getCardStatistics($db, $tgl_awal, $tgl_akhir);
        $beban = ($cardData->sisa_lalu ?? 0) + ($cardData->diterima ?? 0);
        $putusanSela = $this->getPutusanSelaCount($db, $tgl_awal, $tgl_akhir);
        $rekapEcourt = $this->getEcourtStatistics($db, $tgl_awal, $tgl_akhir);
        $zonaWarna = $this->getZoneStatistics($db, $tgl_awal, $tgl_akhir);
        $rekapJenis = $this->getCaseTypeStatistics($db, $tgl_awal, $tgl_akhir);
        $jenisPutus = $this->getJenisPutusStatistics($db, $tgl_awal, $tgl_akhir);

        $ratio = $beban > 0 ? round(($cardData->selesai / $beban) * 100, 1) : 0;


        return view('dashboard.index_public', compact(
            'cardData',
            'beban',
            'putusanSela',
            'jenisPutus',
            'rekapEcourt',
            'zonaWarna',
            'rekapJenis',
            'tahun',
            'ratio',
            'tgl_awal',
            'tgl_akhir'
        ));
    }

    /**
     * DETAIL PERKARA
     */
    public function detail(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $type = $request->input('type');
        $jenis = $request->input('jenis');

        $typeLabels = [
            'sisa_lalu' => 'Sisa Lalu',
            'diterima' => 'Diterima',
            'beban_kerja' => 'Beban Kerja',
            'selesai' => 'Selesai',
            'sisa' => 'Sisa',
            'hijau_tua' => 'Zona Hijau Tua (≤30 hari)',
            'hijau_muda' => 'Zona Hijau Muda (31-60 hari)',
            'kuning' => 'Zona Kuning (61-90 hari)',
            'merah' => 'Zona Merah (>90 hari)',
            'dikuatkan' => 'Putusan Dikuatkan',
            'dibatalkan' => 'Putusan Dibatalkan',
            'n_o' => 'Putusan Tidak Dapat Diterima',
            'dicabut' => 'Putusan Dicabut',
            'per_jenis' => "Per Jenis Perkara: $jenis"
        ];

        $db = DB::connection('siappta');
        $query = $db->table('perkara')->whereNotNull('tgl_register');

        switch ($type) {
            case 'sisa_lalu':
                $query->where('tgl_register', '<', $tgl_awal)
                    ->where(function ($q) use ($tgl_awal) {
                        $q->whereNull('tgl_putusan')->orWhere('tgl_putusan', '>=', $tgl_awal);
                    });
                break;
            case 'diterima':
                $query->whereBetween('tgl_register', [$tgl_awal, $tgl_akhir]);
                break;
            case 'beban_kerja':
                $query->where(function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->where('tgl_register', '<', $tgl_awal)
                        ->where(function ($sq) use ($tgl_awal) {
                            $sq->whereNull('tgl_putusan')->orWhere('tgl_putusan', '>=', $tgl_awal);
                        })->orWhereBetween('tgl_register', [$tgl_awal, $tgl_akhir]);
                });
                break;
            case 'selesai':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir]);
                break;
            case 'sisa':
                $query->where(function ($q) use ($tgl_akhir) {
                    $q->whereNull('tgl_putusan')->orWhere('tgl_putusan', '>', $tgl_akhir);
                });
                break;
            case 'hijau_tua':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->whereRaw('DATEDIFF(tgl_putusan, tgl_register) <= 30');
                break;
            case 'hijau_muda':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->whereRaw('DATEDIFF(tgl_putusan, tgl_register) BETWEEN 31 AND 60');
                break;
            case 'kuning':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->whereRaw('DATEDIFF(tgl_putusan, tgl_register) BETWEEN 61 AND 90');
                break;
            case 'merah':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->whereRaw('DATEDIFF(tgl_putusan, tgl_register) > 90');
                break;
            case 'dikuatkan':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->where('jenis_putus_text', 'Dikuatkan');
                break;
            case 'dibatalkan':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->where('jenis_putus_text', 'Dibatalkan');
                break;
            case 'n_o':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->where('jenis_putus_text', 'Tidak dapat diterima');
                break;
            case 'dicabut':
                $query->whereBetween('tgl_putusan', [$tgl_awal, $tgl_akhir])->where('jenis_putus_text', 'Dicabut');
                break;
            case 'per_jenis':
                $query->whereBetween('tgl_register', [$tgl_awal, $tgl_akhir])->where('jenis_perkara', $jenis);
                break;
        }

        $data = $query->select(
            'nomor_perkara_banding',
            'nomor_perkara_pa',
            'jenis_perkara',
            'nama_satker',
            'nama_pembanding',
            'nama_terbanding',
            'tgl_register',
            'tgl_putusan',
            'jenis_putus_text',
            'nama_km',
            'nama_pp',
            'tgl_minutasi',
            'tgl_kirim_pa',
            'tgl_upload'
        )->get();

        // --- SIMPAN LOG KE DATABASE (DIPERBAIKI) ---
        $typeLabel = $typeLabels[$type] ?? $type;
        $description = "Type: $typeLabel, Periode: $tgl_awal s.d $tgl_akhir";
        if ($type === 'per_jenis') {
            $description .= ", Jenis Perkara: $jenis";
        }

        ActivityLog::create([
            'user_id' => auth()->id() ?? null,
            'activity' => "Melihat Detail Perkara Dashboard",
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('dashboard.detail', compact('data', 'type', 'tgl_awal', 'tgl_akhir', 'jenis'));
    }

    // --- PRIVATE HELPERS ---

    private function getCardStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')->whereNotNull('tgl_register')
            ->selectRaw("
                SUM(CASE WHEN tgl_register < ? AND (tgl_putusan IS NULL OR tgl_putusan >= ?) THEN 1 ELSE 0 END) AS sisa_lalu,
                SUM(CASE WHEN tgl_register BETWEEN ? AND ? THEN 1 ELSE 0 END) AS diterima,
                SUM(CASE WHEN tgl_putusan BETWEEN ? AND ? THEN 1 ELSE 0 END) AS selesai,
                SUM(CASE WHEN tgl_putusan IS NULL OR tgl_putusan > ? THEN 1 ELSE 0 END) AS sisa
            ", [$tglAwal, $tglAwal, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir, $tglAkhir])->first();
    }

    private function getPutusanSelaCount($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')->whereNotNull('tgl_register')->whereBetween('tgl_putusan_sela', [$tglAwal, $tglAkhir])->count();
    }

    private function getJenisPutusStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')->whereBetween('tgl_putusan', [$tglAwal, $tglAkhir])
            ->selectRaw("
                SUM(CASE WHEN jenis_putus_text = 'Dikuatkan' THEN 1 ELSE 0 END) as dikuatkan,
                SUM(CASE WHEN jenis_putus_text = 'Dibatalkan' THEN 1 ELSE 0 END) as dibatalkan,
                SUM(CASE WHEN jenis_putus_text = 'Tidak dapat diterima' THEN 1 ELSE 0 END) as n_o,
                SUM(CASE WHEN jenis_putus_text = 'Dicabut' THEN 1 ELSE 0 END) as dicabut
            ")->first();
    }

    private function getEcourtStatistics($db, $tglAwal, $tglAkhir)
    {
        $unionQuery = $this->getEcourtUnion($db);
        if (!$unionQuery) return (object)['total_ecourt' => 0, 'total_manual' => 0];
        return $db->table('perkara as p')
            ->leftJoinSub($unionQuery, 'ec', function ($join) {
                $join->on(DB::raw('TRIM(p.nomor_perkara_pa)'), '=', DB::raw('TRIM(ec.nomor_perkara)'));
            })
            ->whereNotNull('p.tgl_register')->whereBetween('p.tgl_register', [$tglAwal, $tglAkhir])
            ->selectRaw("SUM(CASE WHEN ec.nomor_perkara IS NOT NULL THEN 1 ELSE 0 END) as total_ecourt, SUM(CASE WHEN ec.nomor_perkara IS NULL THEN 1 ELSE 0 END) as total_manual")->first();
    }

    private function getEcourtUnion($db)
    {
        $unionQuery = null;
        foreach ($this->satkers as $satker) {
            $q = $db->table("{$satker}.ecourt_banding")->select('nomor_perkara');
            $unionQuery = $unionQuery ? $unionQuery->unionAll($q) : $q;
        }
        return $unionQuery;
    }

    private function getZoneStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')->whereNotNull('tgl_register')->whereBetween('tgl_putusan', [$tglAwal, $tglAkhir])
            ->selectRaw("
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) <= 30 THEN 1 ELSE 0 END) as hijau_tua,
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) BETWEEN 31 AND 60 THEN 1 ELSE 0 END) as hijau_muda,
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) BETWEEN 61 AND 90 THEN 1 ELSE 0 END) as kuning,
                SUM(CASE WHEN DATEDIFF(tgl_putusan, tgl_register) > 90 THEN 1 ELSE 0 END) as merah
            ")->first();
    }

    private function calculateTotalPutus($zonaWarna)
    {
        return ($zonaWarna->hijau_tua ?? 0) + ($zonaWarna->hijau_muda ?? 0) + ($zonaWarna->kuning ?? 0) + ($zonaWarna->merah ?? 0);
    }

    private function getCaseTypeStatistics($db, $tglAwal, $tglAkhir)
    {
        return $db->table('perkara')->whereNotNull('tgl_register')->whereBetween('tgl_register', [$tglAwal, $tglAkhir])
            ->selectRaw("jenis_perkara as jenis, COUNT(*) as total")->groupBy('jenis_perkara')->orderBy('total', 'desc')->get();
    }
}
