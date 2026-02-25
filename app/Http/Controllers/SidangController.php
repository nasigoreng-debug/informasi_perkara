<?php

namespace App\Http\Controllers;

use App\Models\SidangSiappta; // Menggunakan model khusus server 121
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class SidangController extends Controller
{
    /**
     * TAMPILAN JADWAL SIDANG (Untuk Admin/Web Biasa - Dengan Pagination)
     */
    public function index(Request $request)
    {
        try {
            Carbon::setLocale('id');
            $hariIni = now()->toDateString();

            // Mengambil data dari server 121 menggunakan model SidangSiappta
            $query = SidangSiappta::select(
                'perkara.*',
                'hakim_tinggi.kode as kode_hakim',
                'hakim_tinggi.nama as nama_hakim_lengkap'
            )
                ->leftJoin('hakim_tinggi', 'perkara.km_id', '=', 'hakim_tinggi.id')
                ->whereNull('perkara.tgl_putusan')
                ->whereNotNull('perkara.tgl_sidang_pertama')
                ->whereDate('perkara.tgl_sidang_pertama', '>=', $hariIni)
                ->orderBy('perkara.tgl_sidang_pertama', 'asc');

            $allPerkaras = $query->get();

            $perkarasAkanDitampilkan = $allPerkaras->map(function ($perkara) use ($hariIni) {
                $tglSidang = Carbon::parse($perkara->tgl_sidang_pertama)->toDateString();
                $perkara->status_sidang = ($tglSidang == $hariIni) ? 'HARI_INI' : 'AKAN_DATANG';
                $perkara->tanggal_sidang_terdekat = $tglSidang;
                return $perkara;
            })->sortBy(function ($item) {
                return ($item->status_sidang == 'HARI_INI' ? '1' : '2') . $item->tanggal_sidang_terdekat;
            })->values();

            // Logika Pagination Manual
            $perPage = 20;
            $currentPage = LengthAwarePaginator::resolveCurrentPage() ?: 1;
            $perkaras = new LengthAwarePaginator(
                $perkarasAkanDitampilkan->forPage($currentPage, $perPage),
                $perkarasAkanDitampilkan->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );

            return view('perkara.index', [
                'perkaras' => $perkaras,
                'sidangHariIni' => $perkarasAkanDitampilkan->where('status_sidang', 'HARI_INI')->count(),
                'totalSidangAkanDatang' => $perkarasAkanDitampilkan->where('status_sidang', 'AKAN_DATANG')->count(),
                'totalDitampilkan' => $perkarasAkanDitampilkan->count(),
                'hariIni' => $hariIni,
                'hariIniFormatted' => Carbon::parse($hariIni)->translatedFormat('l, d F Y')
            ]);
        } catch (\Exception $e) {
            Log::error('Error di SidangController@index: ' . $e->getMessage());
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }

    /**
     * TAMPILAN VISUAL (HANYA HARI INI - TANPA PAGINATION)
     */
    public function index_visual(Request $request)
    {
        try {
            Carbon::setLocale('id');
            $hariIni = now()->toDateString();

            // 1. AMBIL DATA JADWAL SIDANG
            $perkarasHariIni = \App\Models\SidangSiappta::select(
                'perkara.*',
                'hakim_tinggi.kode as kode_hakim',
                'hakim_tinggi.nama as nama_hakim_lengkap'
            )
                ->leftJoin('hakim_tinggi', 'perkara.km_id', '=', 'hakim_tinggi.id')
                ->whereNull('perkara.tgl_putusan')
                ->whereNotNull('perkara.tgl_sidang_pertama')
                ->whereDate('perkara.tgl_sidang_pertama', '=', $hariIni)
                ->orderBy('perkara.tgl_sidang_pertama', 'asc')
                ->get();

            // 2. AMBIL DATA PENGUNJUNG DARI DATABASE
            $visitorStats = [
                // Hitung yang akses hari ini
                'today' => \App\Models\Visitor::whereDate('visit_date', $hariIni)->count(),

                // Hitung yang akses bulan ini
                'month' => \App\Models\Visitor::whereMonth('visit_date', now()->month)
                    ->whereYear('visit_date', now()->year)
                    ->count(),

                // Hitung total seluruh kunjungan
                'total' => \App\Models\Visitor::count(),

                // Simulasi Online (Visitor dalam 5 menit terakhir + angka acak sedikit agar dinamis)
                'online' => \App\Models\Visitor::where('updated_at', '>=', now()->subMinutes(5))->count() + rand(2, 5)
            ];

            // 3. KIRIM SEMUA DATA KE VIEW
            return view('perkara.index_visual', [
                'perkaras' => $perkarasHariIni,
                'sidangHariIni' => $perkarasHariIni->count(),
                'totalSidangAkanDatang' => 0,
                'totalDitampilkan' => $perkarasHariIni->count(),
                'hariIni' => $hariIni,
                'hariIniFormatted' => Carbon::parse($hariIni)->translatedFormat('l, d F Y'),
                'visitorStats' => $visitorStats
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error Sidang Visual: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data'], 500);
        }
    }
}
