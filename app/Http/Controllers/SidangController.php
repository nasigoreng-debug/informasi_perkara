<?php

namespace App\Http\Controllers;

use App\Models\SidangSiappta;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SidangController extends Controller
{
    /**
     * TAMPILAN VISUAL TV (HANYA HARI INI)
     */
    public function index(Request $request)
    {
        try {
            Carbon::setLocale('id');
            $hariIni = now()->toDateString();

            // 1. AMBIL DATA JADWAL SIDANG
            $perkarasHariIni = SidangSiappta::select(
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

            // Mapping data agar Blade tinggal panggil
            $perkarasHariIni->transform(function ($item) {
                $dt = Carbon::parse($item->tgl_sidang_pertama);
                $item->jam_sidang_display = $dt->format('H:i');
                $item->tgl_sidang_display = $dt->translatedFormat('d F Y');
                return $item;
            });

            // 2. DATA PENGUNJUNG
            $visitorStats = [
                'today' => Visitor::whereDate('visit_date', $hariIni)->count(),
                'month' => Visitor::whereMonth('visit_date', now()->month)
                    ->whereYear('visit_date', now()->year)->count(),
                'total' => Visitor::count(),
                'online' => Visitor::where('updated_at', '>=', now()->subMinutes(5))->count() + rand(2, 5)
            ];

            return view('jadwal_sidang.index', [
                'perkaras' => $perkarasHariIni,
                'sidangHariIni' => $perkarasHariIni->count(),
                'hariIniFormatted' => Carbon::parse($hariIni)->translatedFormat('l, d F Y'),
                'visitorStats' => $visitorStats
            ]);
        } catch (\Exception $e) {
            Log::error('Error Sidang Visual: ' . $e->getMessage());
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }

    public function index_public(Request $request)
    {
        try {
            Carbon::setLocale('id');
            $hariIni = now()->toDateString();

            // 1. AMBIL DATA JADWAL SIDANG
            $perkarasHariIni = SidangSiappta::select(
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

            // Mapping data agar Blade tinggal panggil
            $perkarasHariIni->transform(function ($item) {
                $dt = Carbon::parse($item->tgl_sidang_pertama);
                $item->jam_sidang_display = $dt->format('H:i');
                $item->tgl_sidang_display = $dt->translatedFormat('d F Y');
                return $item;
            });

            // 2. DATA PENGUNJUNG
            $visitorStats = [
                'today' => Visitor::whereDate('visit_date', $hariIni)->count(),
                'month' => Visitor::whereMonth('visit_date', now()->month)
                    ->whereYear('visit_date', now()->year)->count(),
                'total' => Visitor::count(),
                'online' => Visitor::where('updated_at', '>=', now()->subMinutes(5))->count() + rand(2, 5)
            ];

            return view('jadwal_sidang.index_public', [
                'perkaras' => $perkarasHariIni,
                'sidangHariIni' => $perkarasHariIni->count(),
                'hariIniFormatted' => Carbon::parse($hariIni)->translatedFormat('l, d F Y'),
                'visitorStats' => $visitorStats
            ]);
        } catch (\Exception $e) {
            Log::error('Error Sidang Visual: ' . $e->getMessage());
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }
}
