<?php

namespace App\Http\Controllers;

use App\Services\LaporanKasasiServiceL10;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\KasasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ActivityLog; // Tambahkan pemanggilan Model Log

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
        if (!$user->isAdmin() && $user->satker) {
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

        return view('laporan.kasasi.index', compact('data', 'years', 'tahun', 'grandTotal', 'bulan'));
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
                        'updated_at' => now()
                    ]
                );

                // --- CATAT LOG: Upload PDF ---
                ActivityLog::record(
                    'Upload PDF Kasasi',
                    'MonitoringKasasi',
                    "Berhasil upload PDF untuk Perkara ID: {$perkara_id} di database: {$nama_db}"
                );

                return back()->with('success', 'Dokumen berhasil diunggah.');
            }

            return back()->with('error', 'Gagal memindahkan file.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
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

        // --- CATAT LOG: Export Excel ---
        $satkerName = $user->satker ? $user->satker->nama : 'Seluruh Satker';
        ActivityLog::record(
            'Export Excel Kasasi',
            'MonitoringKasasi',
            "Mendownload laporan Kasasi tahun {$tahun} untuk {$satkerName}"
        );

        $suffix = $user->satker ? $user->satker->tabel : 'semua';
        $fileName = "kasasi_" . $suffix . "_" . $tahun . ".xlsx";

        return Excel::download(new KasasiExport($data), $fileName);
    }
}
