<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\ActivityLog;
use App\Models\SidangSiappta;
use App\Models\Visitor;
use App\Services\RekapEksekusiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $rekapService;

    public function __construct(RekapEksekusiService $rekapService)
    {
        $this->middleware('auth');
        $this->rekapService = $rekapService;
    }

    public function index()
    {
        // 1. Data Grafik Surat (Lokal - Cepat)
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataSurat = [];
        for ($m = 1; $m <= 12; $m++) {
            $dataSurat[] = SuratMasuk::whereMonth('tgl_surat', $m)->whereYear('tgl_surat', date('Y'))->count();
        }

        // 2. Data Eksekusi (Real - Jika lambat akan diberi angka 0 agar dashboard tidak blank)
        try {
            $dataRaw = $this->rekapService->getRekap(date('Y-01-01'), date('Y-12-31'));
            $dataCollection = collect($dataRaw);
            $totalBeban = (int) $dataCollection->sum('BEBAN');
            $totalSelesai = (int) $dataCollection->sum('SELESAI');
            $eksekusiSelesai = $totalBeban > 0 ? round(($totalSelesai / $totalBeban) * 100, 1) : 0;
            $eksekusiSisa = 100 - $eksekusiSelesai;
        } catch (\Exception $e) {
            $totalBeban = 0;
            $totalSelesai = 0;
            $eksekusiSelesai = 0;
            $eksekusiSisa = 0;
        }

        // 3. Statistik Kartu
        $statsSurat = ['total' => SuratMasuk::count(), 'hari_ini' => SuratMasuk::whereDate('created_at', today())->count()];
        $totalSidang = SidangSiappta::whereDate('tgl_sidang_pertama', today())->count();
        $visitors = ['today' => Visitor::whereDate('visit_date', today())->count(), 'online' => Visitor::where('updated_at', '>=', now()->subMinutes(5))->count()];
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        $totalUser = User::count();

        return view('dashboard', compact(
            'statsSurat',
            'totalSidang',
            'visitors',
            'recentLogs',
            'totalUser',
            'labels',
            'dataSurat',
            'eksekusiSelesai',
            'eksekusiSisa',
            'totalBeban',
            'totalSelesai'
        ));
    }
}
