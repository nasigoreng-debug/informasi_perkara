<?php

namespace App\Http\Controllers;

use App\Models\SidangSiappta;
use App\Models\Visitor;
use App\Models\ActivityLog; // ✅ PAKAI MODEL ACTIVITYLOG
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

            // ✅ LOG: Akses Jadwal Sidang (Internal/TV) - PAKAI MODEL
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Akses Jadwal Sidang',
                'description' => "Memantau jadwal sidang hari ini ({$perkarasHariIni->count()} perkara)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return view('jadwal_sidang.index', [
                'perkaras' => $perkarasHariIni,
                'sidangHariIni' => $perkarasHariIni->count(),
                'hariIniFormatted' => Carbon::parse($hariIni)->translatedFormat('l, d F Y'),
                'visitorStats' => $visitorStats
            ]);
        } catch (\Exception $e) {
            // ✅ LOG ERROR (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Akses Jadwal Sidang',
                'description' => 'Error: ' . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            Log::error('Error Sidang Visual: ' . $e->getMessage());
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }

    /**
     * TAMPILAN UNTUK PUBLIK
     */
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

            // Mapping data
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

            // ✅ LOG: Akses Jadwal Sidang Public (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Akses Jadwal Sidang Public',
                'description' => "Memantau jadwal sidang publik hari ini ({$perkarasHariIni->count()} perkara)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return view('jadwal_sidang.index_public', [
                'perkaras' => $perkarasHariIni,
                'sidangHariIni' => $perkarasHariIni->count(),
                'hariIniFormatted' => Carbon::parse($hariIni)->translatedFormat('l, d F Y'),
                'visitorStats' => $visitorStats
            ]);
        } catch (\Exception $e) {
            // ✅ LOG ERROR PUBLIC (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Akses Jadwal Sidang Public',
                'description' => 'Error: ' . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            Log::error('Error Sidang Visual Public: ' . $e->getMessage());
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }

    /**
     * DETAIL JADWAL SIDANG PER PERKARA
     */
    public function detail(Request $request, $id)
    {
        try {
            $perkara = SidangSiappta::select(
                'perkara.*',
                'hakim_tinggi.kode as kode_hakim',
                'hakim_tinggi.nama as nama_hakim_lengkap'
            )
                ->leftJoin('hakim_tinggi', 'perkara.km_id', '=', 'hakim_tinggi.id')
                ->where('perkara.id', $id)
                ->first();

            if (!$perkara) {
                return response()->json(['success' => false, 'message' => 'Perkara tidak ditemukan'], 404);
            }

            // Format tanggal
            $dt = Carbon::parse($perkara->tgl_sidang_pertama);
            $perkara->jam_sidang_display = $dt->format('H:i');
            $perkara->tgl_sidang_display = $dt->translatedFormat('d F Y');

            // ✅ LOG DETAIL (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Detail Jadwal Sidang',
                'description' => "Melihat detail perkara: {$perkara->nomor_perkara} | Majelis Hakim: {$perkara->nama_hakim_lengkap}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json(['success' => true, 'data' => $perkara]);
        } catch (\Exception $e) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Detail Jadwal Sidang',
                'description' => 'Error: ' . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * JADWAL SIDANG MINGGU INI
     */
    public function mingguIni(Request $request)
    {
        try {
            Carbon::setLocale('id');
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $perkaras = SidangSiappta::select(
                'perkara.*',
                'hakim_tinggi.kode as kode_hakim',
                'hakim_tinggi.nama as nama_hakim_lengkap'
            )
                ->leftJoin('hakim_tinggi', 'perkara.km_id', '=', 'hakim_tinggi.id')
                ->whereNull('perkara.tgl_putusan')
                ->whereNotNull('perkara.tgl_sidang_pertama')
                ->whereDate('perkara.tgl_sidang_pertama', '>=', $startOfWeek)
                ->whereDate('perkara.tgl_sidang_pertama', '<=', $endOfWeek)
                ->orderBy('perkara.tgl_sidang_pertama', 'asc')
                ->get();

            $perkaras->transform(function ($item) {
                $dt = Carbon::parse($item->tgl_sidang_pertama);
                $item->jam_sidang_display = $dt->format('H:i');
                $item->tgl_sidang_display = $dt->translatedFormat('d F Y');
                $item->hari_sidang = $dt->translatedFormat('l');
                return $item;
            });

            // Group by tanggal
            $groupedByDate = $perkaras->groupBy(function ($item) {
                return Carbon::parse($item->tgl_sidang_pertama)->toDateString();
            });

            // ✅ LOG MINGGU INI (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Jadwal Sidang Minggu Ini',
                'description' => "Menampilkan jadwal sidang periode {$startOfWeek->translatedFormat('d F Y')} s.d {$endOfWeek->translatedFormat('d F Y')} ({$perkaras->count()} perkara)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'periode' => [
                        'start' => $startOfWeek->translatedFormat('d F Y'),
                        'end' => $endOfWeek->translatedFormat('d F Y')
                    ],
                    'total' => $perkaras->count(),
                    'grouped_by_date' => $groupedByDate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
