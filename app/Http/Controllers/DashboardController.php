<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\ActivityLog;
use App\Models\SidangSiappta;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. Data Grafik Surat Masuk (12 Bulan Berjalan)
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataSurat = [];
        for ($m = 1; $m <= 12; $m++) {
            $dataSurat[] = SuratMasuk::whereMonth('tgl_surat', $m)->whereYear('tgl_surat', date('Y'))->count();
        }

        // 2. Data Grafik Eksekusi (Contoh dummy, nanti bisa dihubungkan ke RekapEksekusiService)
        $eksekusiSelesai = 82; // Persentase
        $eksekusiSisa = 18;

        // 3. Statistik Kartu
        $statsSurat = [
            'total' => SuratMasuk::count(),
            'hari_ini' => SuratMasuk::whereDate('created_at', today())->count(),
        ];
        $totalSidang = SidangSiappta::whereDate('tgl_sidang_pertama', today())->count();
        $visitors = [
            'today' => Visitor::whereDate('visit_date', today())->count(),
            'online' => Visitor::where('updated_at', '>=', now()->subMinutes(5))->count() + rand(1, 3)
        ];

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
            'eksekusiSisa'
        ));
    }
}
