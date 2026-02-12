<?php

namespace App\Http\Controllers;

use App\Models\Perkara;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class SidangController extends Controller
{
    /**
     * TAMPILAN JADWAL SIDANG
     */
    public function index(Request $request)
    {
        try {
            Carbon::setLocale('id');
            $hariIni = now()->toDateString();

            // Query dasar menggunakan Join untuk performa
            $query = Perkara::select(
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

            // Proses status dan sorting (Hari Ini vs Akan Datang)
            $perkarasAkanDitampilkan = $allPerkaras->map(function ($perkara) use ($hariIni) {
                $tglSidang = Carbon::parse($perkara->tgl_sidang_pertama)->toDateString();
                $perkara->status_sidang = ($tglSidang == $hariIni) ? 'HARI_INI' : 'AKAN_DATANG';
                $perkara->tanggal_sidang_terdekat = $tglSidang;
                return $perkara;
            })->sortBy(function ($item) {
                // Urutkan HARI_INI dulu (1), lalu tanggal terdekat (2...)
                return ($item->status_sidang == 'HARI_INI' ? '1' : '2') . $item->tanggal_sidang_terdekat;
            })->values();

            // Paginate Manual
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
            Log::error('Error di PerkaraController@sidang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
