<?php

namespace App\Http\Controllers;

use App\Services\LaporanKasasiServiceL10;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LaporanKasasiController extends Controller
{
    protected LaporanKasasiServiceL10 $kasasiService;

    public function __construct(LaporanKasasiServiceL10 $kasasiService)
    {
        $this->kasasiService = $kasasiService;
    }

    public function index(Request $request)
    {
        $tahun = (int) $request->input('tahun', date('Y'));
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput !== null && $bulanInput !== '') ? (int) $bulanInput : null;

        // 1. Ambil data SIPP dari 26 Satker
        $data = $this->kasasiService->getLaporanKasasi($tahun, $bulan);
        $grandTotal = $this->kasasiService->getGrandTotal($tahun, $bulan);
        $years = $this->kasasiService->getAvailableYears();

        // 2. Ambil data "selipan" PDF dari database lokal db_informasi
        $allDocs = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->get();

        // 3. Gabungkan data (Merge)
        $data = $data->map(function ($item) use ($allDocs) {
            $doc = $allDocs->where('perkara_id', $item->perkara_id)
                ->where('nama_db', $item->nama_db)
                ->first();

            $item->file_pdf = $doc ? $doc->file_pdf : null;
            return $item;
        });

        return view('laporan.kasasi.index', compact('data', 'years', 'tahun', 'grandTotal', 'bulan'));
    }

    public function uploadPdf(Request $request, $perkara_id)
    {
        // 1. Validasi
        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:10240',
            'nama_db'  => 'required'
        ]);

        try {
            if (!$request->hasFile('file_pdf')) {
                return back()->with('error', 'File tidak diterima oleh server. Cek limit upload PHP.');
            }

            $file = $request->file('file_pdf');

            if ($file->isValid()) {
                $nama_db = $request->nama_db;

                // Bersihkan nama database agar aman untuk Windows
                $cleanDbName = preg_replace('/[^A-Za-z0-9\_]/', '', $nama_db) ?: 'satker';
                $filename = "kasasi_" . $cleanDbName . "_" . $perkara_id . "_" . time() . ".pdf";

                // 2. Tentukan Path Absolut Folder Tujuan
                $targetPath = storage_path('app/public/putusan_pdf');

                // 3. Buat folder jika belum ada
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0775, true);
                }

                // 4. CEK DAN HAPUS FILE LAMA (JIKA ADA)
                $oldData = \DB::connection('db_pm_hukum')
                    ->table('monitoring_kasasi_docs')
                    ->where('perkara_id', $perkara_id)
                    ->where('nama_db', $nama_db)
                    ->first();

                if ($oldData && !empty($oldData->file_pdf)) {
                    $oldFilePath = storage_path('app/public/' . $oldData->file_pdf);

                    // Hapus file lama jika ada
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath); // Hapus file fisik
                    }
                }

                // 5. PINDAHKAN FILE BARU SECARA PAKSA
                $success = $file->move($targetPath, $filename);

                if ($success) {
                    // Path relatif untuk disimpan di database
                    $dbFilePath = 'putusan_pdf/' . $filename;

                    // 6. Update Database Lokal (db_informasi)
                    \DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->updateOrInsert(
                        ['perkara_id' => $perkara_id, 'nama_db' => $nama_db],
                        [
                            'file_pdf' => $dbFilePath,
                            'updated_at' => now()
                        ]
                    );

                    $message = 'Dokumen berhasil diupload ke: ' . $dbFilePath;
                    if ($oldData && !empty($oldData->file_pdf)) {
                        $message .= ' (File lama telah dihapus)';
                    }

                    return back()->with('success', $message);
                }
            }

            return back()->with('error', 'File tidak valid atau gagal dipindahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }
}
