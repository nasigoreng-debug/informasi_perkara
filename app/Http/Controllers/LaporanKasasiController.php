<?php

namespace App\Http\Controllers;

use App\Services\LaporanKasasiServiceL10;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\KasasiExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:10240',
            'nama_db'  => 'required'
        ]);

        try {
            if (!$request->hasFile('file_pdf')) {
                return back()->with('error', 'File tidak diterima oleh server.');
            }

            $file = $request->file('file_pdf');

            if ($file->isValid()) {
                $nama_db = $request->nama_db;
                $cleanDbName = preg_replace('/[^A-Za-z0-9\_]/', '', $nama_db) ?: 'satker';
                $filename = "kasasi_" . $cleanDbName . "_" . $perkara_id . "_" . time() . ".pdf";
                $targetPath = storage_path('app/public/putusan_pdf');

                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0775, true);
                }

                $oldData = DB::connection('db_pm_hukum')
                    ->table('monitoring_kasasi_docs')
                    ->where('perkara_id', $perkara_id)
                    ->where('nama_db', $nama_db)
                    ->first();

                if ($oldData && !empty($oldData->file_pdf)) {
                    $oldFilePath = storage_path('app/public/' . $oldData->file_pdf);
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $success = $file->move($targetPath, $filename);

                if ($success) {
                    $dbFilePath = 'putusan_pdf/' . $filename;
                    DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->updateOrInsert(
                        ['perkara_id' => $perkara_id, 'nama_db' => $nama_db],
                        [
                            'file_pdf' => $dbFilePath,
                            'updated_at' => now()
                        ]
                    );

                    return back()->with('success', 'Dokumen berhasil diperbarui.');
                }
            }
            return back()->with('error', 'Gagal memindahkan file.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $tahun = (int) $request->input('tahun', date('Y'));
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput !== null && $bulanInput !== '') ? (int) $bulanInput : null;

        // Ambil data dari Service
        $data = $this->kasasiService->getLaporanKasasi($tahun, $bulan);

        // Ambil data dokumen lokal
        $allDocs = DB::connection('db_pm_hukum')->table('monitoring_kasasi_docs')->get();

        // Merge Data khusus untuk Excel
        $data = $data->map(function ($item) use ($allDocs) {
            $doc = $allDocs->where('perkara_id', $item->perkara_id)
                ->where('nama_db', $item->nama_db)
                ->first();

            // Status PDF (Local DB)
            $item->status_pdf_label = $doc ? 'Tersedia' : 'Kosong';

            // Kolom Teks Putusan (SIPP DB)
            $item->hasil_putusan_ma = $item->status_putusan_kasasi_text ?? '-';

            return $item;
        });

        $fileName = "monitoring_kasasi_" . $tahun . "_" . ($bulan ?? 'semua_bulan') . ".xlsx";

        return Excel::download(new KasasiExport($data), $fileName);
    }
}
