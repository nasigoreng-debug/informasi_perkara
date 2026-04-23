<?php

namespace App\Http\Controllers;

use App\Services\LaporanKasasiServiceL10;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\KasasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ActivityLog;

class LaporanKasasiController extends Controller
{
    protected LaporanKasasiServiceL10 $kasasiService;

    public function __construct(LaporanKasasiServiceL10 $kasasiService)
    {
        $this->kasasiService = $kasasiService;
    }

    /**
     * Menampilkan daftar laporan kasasi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tahun = (int) $request->input('tahun', date('Y'));
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput !== null && $bulanInput !== '') ? (int) $bulanInput : null;

        // 1. Ambil seluruh data dari Service SIPP
        $dataRaw = $this->kasasiService->getLaporanKasasi($tahun, $bulan);

        // --- FILTER OTOMATIS BERDASARKAN SATKER ---
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $user->satker) {
            $keyword = strtolower($user->satker->tabel);
            $data = $dataRaw->filter(function ($item) use ($keyword) {
                return str_contains(strtolower($item->nama_db), $keyword);
            });
        } else {
            $data = $dataRaw;
        }

        $grandTotal = $this->kasasiService->getGrandTotal($tahun, $bulan);
        $years = $this->kasasiService->getAvailableYears();

        // 2. Gabungkan data dengan file PDF
        $allDocs = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->get();

        $data = $data->map(function ($item) use ($allDocs) {
            $doc = $allDocs->where('perkara_id', $item->perkara_id)
                ->where('nama_db', $item->nama_db)
                ->first();

            $item->file_pdf = $doc ? $doc->file_pdf : null;
            return $item;
        });

        // Hitung statistik untuk log
        $totalData = $data->count();
        $totalDokumen = $data->filter(fn($item) => !is_null($item->file_pdf))->count();
        $totalKosong = $totalData - $totalDokumen;

        // ✅ LOG AKSES INDEX (PAKAI MODEL)
        $satkerInfo = $user->satker ? $user->satker->nama : 'Semua Satker';
        $bulanInfo = $bulan ? Carbon::create()->month($bulan)->format('F') : 'Semua Bulan';

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Akses Monitoring Kasasi',
            'description' => "Melihat laporan Kasasi tahun {$tahun}, bulan: {$bulanInfo}, filter: {$satkerInfo} | Total Data: {$totalData} | Dokumen Tersedia: {$totalDokumen} | Kosong: {$totalKosong}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('kasasi.index', compact('data', 'years', 'tahun', 'grandTotal', 'bulan'));
    }

    /**
     * Upload berkas PDF dengan pencatatan Log
     */
    public function uploadPdf(Request $request, $perkara_id)
    {
        $user = Auth::user();

        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:10240',
            'nama_db'  => 'required'
        ]);

        // Proteksi satker
        if (!$user->isAdmin() && $user->satker) {
            $keyword = strtolower($user->satker->tabel);
            if (!str_contains(strtolower($request->nama_db), $keyword)) {

                // ✅ LOG AKSES DITOLAK (PAKAI MODEL)
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Upload PDF Kasasi DITOLAK',
                    'description' => "Akses ditolak! User (" . ($user->username ?? $user->name ?? 'Unknown') . ") mencoba upload ke satker lain: {$request->nama_db}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return back()->with('error', 'Akses Ditolak! Anda hanya boleh mengunggah dokumen satker Anda sendiri.');
            }
        }

        try {
            if (!$request->hasFile('file_pdf')) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            $file = $request->file('file_pdf');
            $nama_db = $request->nama_db;
            $cleanDbName = preg_replace('/[^A-Za-z0-9\_]/', '', $nama_db);
            $filename = "kasasi_" . $cleanDbName . "_" . $perkara_id . "_" . time() . ".pdf";
            $targetPath = storage_path('app/public/putusan_pdf');

            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0775, true);
            }

            // Hapus file lama jika ada
            $oldData = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')
                ->where('perkara_id', $perkara_id)
                ->where('nama_db', $nama_db)
                ->first();

            if ($oldData && !empty($oldData->file_pdf)) {
                $oldFilePath = storage_path('app/public/' . $oldData->file_pdf);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            if ($file->move($targetPath, $filename)) {
                DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->updateOrInsert(
                    ['perkara_id' => $perkara_id, 'nama_db' => $nama_db],
                    [
                        'file_pdf' => 'putusan_pdf/' . $filename,
                        'updated_at' => now(),
                        'created_at' => $oldData ? $oldData->created_at : now()
                    ]
                );

                // ✅ LOG BERHASIL UPLOAD (PAKAI MODEL)
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Upload PDF Kasasi',
                    'description' => "Berhasil upload PDF untuk Perkara ID: {$perkara_id} di database: {$nama_db}, file: {$filename}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return back()->with('success', 'Dokumen berhasil diunggah.');
            }

            return back()->with('error', 'Gagal memindahkan file.');
        } catch (\Exception $e) {

            // ✅ LOG ERROR UPLOAD (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Upload PDF Kasasi',
                'description' => "Gagal upload PDF Perkara ID: {$perkara_id}, Error: " . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus file PDF Kasasi
     */
    public function deletePdf(Request $request, $perkara_id, $nama_db)
    {
        $user = Auth::user();

        // Proteksi satker
        if (!$user->isAdmin() && $user->satker) {
            $keyword = strtolower($user->satker->tabel);
            if (!str_contains(strtolower($nama_db), $keyword)) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Hapus PDF Kasasi DITOLAK',
                    'description' => "Akses ditolak! User mencoba hapus file dari satker lain: {$nama_db}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return back()->with('error', 'Akses Ditolak!');
            }
        }

        try {
            $data = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')
                ->where('perkara_id', $perkara_id)
                ->where('nama_db', $nama_db)
                ->first();

            if (!$data) {
                return back()->with('error', 'Data tidak ditemukan');
            }

            // Hapus file fisik
            if ($data->file_pdf) {
                $filePath = storage_path('app/public/' . $data->file_pdf);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus record dari database
            DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')
                ->where('perkara_id', $perkara_id)
                ->where('nama_db', $nama_db)
                ->delete();

            // ✅ LOG BERHASIL HAPUS
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Hapus PDF Kasasi',
                'description' => "Berhasil hapus PDF untuk Perkara ID: {$perkara_id} di database: {$nama_db}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('success', 'File PDF berhasil dihapus');
        } catch (\Exception $e) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Hapus PDF Kasasi',
                'description' => "Gagal hapus PDF Perkara ID: {$perkara_id}, Error: " . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    /**
     * Download file PDF Kasasi
     */
    public function downloadPdf(Request $request, $perkara_id, $nama_db)
    {
        $user = Auth::user();

        try {
            $data = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')
                ->where('perkara_id', $perkara_id)
                ->where('nama_db', $nama_db)
                ->first();

            if (!$data || !$data->file_pdf) {
                return back()->with('error', 'File tidak ditemukan');
            }

            $filePath = storage_path('app/public/' . $data->file_pdf);
            if (!file_exists($filePath)) {
                return back()->with('error', 'File fisik tidak ditemukan');
            }

            // ✅ LOG DOWNLOAD
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Download PDF Kasasi',
                'description' => "Download PDF untuk Perkara ID: {$perkara_id} di database: {$nama_db}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->download($filePath, basename($data->file_pdf));
        } catch (\Exception $e) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Download PDF Kasasi',
                'description' => "Gagal download PDF Perkara ID: {$perkara_id}, Error: " . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('error', 'Gagal download file: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel dengan pencatatan Log
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $tahun = (int) $request->input('tahun', date('Y'));
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput !== null && $bulanInput !== '') ? (int) $bulanInput : null;

        $dataRaw = $this->kasasiService->getLaporanKasasi($tahun, $bulan);

        if (!$user->isAdmin() && $user->satker) {
            $keyword = strtolower($user->satker->tabel);
            $data = $dataRaw->filter(fn($item) => str_contains(strtolower($item->nama_db), $keyword));
        } else {
            $data = $dataRaw;
        }

        $allDocs = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->get();
        $data = $data->map(function ($item) use ($allDocs) {
            $doc = $allDocs->where('perkara_id', $item->perkara_id)->where('nama_db', $item->nama_db)->first();
            $item->status_pdf_label = $doc ? 'Tersedia' : 'Kosong';
            $item->hasil_putusan_ma = $item->status_putusan_kasasi_text ?? '-';
            return $item;
        });

        // ✅ LOG EXPORT EXCEL (PAKAI MODEL)
        $satkerName = $user->satker ? $user->satker->nama : 'Seluruh Satker';
        $bulanInfo = $bulan ? Carbon::create()->month($bulan)->format('F') : 'Semua Bulan';

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Export Excel Kasasi',
            'description' => "Mendownload laporan Kasasi tahun {$tahun}, bulan: {$bulanInfo}, filter: {$satkerName}, total data: " . $data->count(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $suffix = $user->satker ? $user->satker->tabel : 'semua';
        $fileName = "kasasi_" . $suffix . "_" . $tahun . "_" . date('Ymd_His') . ".xlsx";

        return Excel::download(new KasasiExport($data), $fileName);
    }

    /**
     * Statistik Kasasi
     */
    public function statistics(Request $request)
    {
        $tahun = (int) $request->input('tahun', date('Y'));
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput !== null && $bulanInput !== '') ? (int) $bulanInput : null;

        $dataRaw = $this->kasasiService->getLaporanKasasi($tahun, $bulan);

        $totalPerkara = $dataRaw->count();
        $totalDiterima = $dataRaw->where('status_putusan_kasasi_text', 'Diterima')->count();
        $totalDitolak = $dataRaw->where('status_putusan_kasasi_text', 'Ditolak')->count();

        $allDocs = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->get();
        $totalDokumen = 0;

        foreach ($dataRaw as $item) {
            $doc = $allDocs->where('perkara_id', $item->perkara_id)
                ->where('nama_db', $item->nama_db)
                ->first();
            if ($doc) $totalDokumen++;
        }

        // ✅ LOG STATISTICS
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Statistik Kasasi',
            'description' => "Periode: Tahun {$tahun}" . ($bulan ? ", Bulan: " . Carbon::create()->month($bulan)->format('F') : "") . " | Total Perkara: {$totalPerkara} | Diterima: {$totalDiterima} | Ditolak: {$totalDitolak} | Dokumen Upload: {$totalDokumen}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'total_perkara' => $totalPerkara,
                'diterima' => $totalDiterima,
                'ditolak' => $totalDitolak,
                'persen_diterima' => $totalPerkara > 0 ? round(($totalDiterima / $totalPerkara) * 100, 2) : 0,
                'total_dokumen' => $totalDokumen,
                'persen_dokumen' => $totalPerkara > 0 ? round(($totalDokumen / $totalPerkara) * 100, 2) : 0
            ]
        ]);
    }
}
