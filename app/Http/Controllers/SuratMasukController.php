<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\SuratMasukExport;
use Maatwebsite\Excel\Facades\Excel;

class SuratMasukController extends Controller
{
    /**
     * DASHBOARD - Statistik Real-time
     */
    public function dashboard()
    {
        // 1. Total Seluruh Arsip Surat Masuk
        $totalSurat = SuratMasuk::count();

        // 2. Surat Masuk Tahun Ini (Januari s/d Desember tahun berjalan)
        $suratTahunIni = SuratMasuk::whereYear('tgl_surat', date('Y'))->count();

        // 3. Input Bulan Ini (Berdasarkan tgl_surat di bulan berjalan)
        $inputBulanIni = SuratMasuk::whereMonth('tgl_surat', date('m'))
            ->whereYear('tgl_surat', date('Y'))
            ->count();

        // Ambil 5 Arsip Terbaru untuk Tabel
        $recentSurat = SuratMasuk::latest('tgl_surat')->take(5)->get();

        return view('surat.surat_masuk.dashboard', compact(
            'totalSurat',
            'suratTahunIni',
            'inputBulanIni',
            'recentSurat'
        ));
    }

    public function index(Request $request)
    {
        $query = SuratMasuk::query();
        $isFiltering = $request->filled('search') || ($request->filled('from_date') && $request->filled('to_date'));

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_surat', 'like', '%' . $request->search . '%')
                    ->orWhere('perihal', 'like', '%' . $request->search . '%')
                    ->orWhere('asal_surat', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tgl_surat', [$request->from_date, $request->to_date]);
        }

        $showAll = $request->get('all') == 'true';
        $isDefault = false;
        if (!$isFiltering && !$showAll) {
            $query->whereYear('tgl_surat', date('Y'));
            $isDefault = true;
        }

        $data_surat = $query->latest('tgl_surat')->paginate(10)->withQueryString();

        ActivityLog::record('Akses Surat Masuk', 'SuratMasuk', 'Membuka daftar arsip surat masuk');

        return view('surat.surat_masuk.index', compact('data_surat', 'isDefault'));
    }

    public function create()
    {
        $lastSurat = SuratMasuk::orderBy('no_indeks', 'desc')->first();
        $nextIndeks = $lastSurat ? $lastSurat->no_indeks + 1 : 1;

        return view('surat.surat_masuk.create', compact('nextIndeks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_indeks' => 'required|unique:db_pm_hukum.tb_surat_masuk,no_indeks',
            'no_surat' => 'required',
            'tgl_surat' => 'required|date',
            'asal_surat' => 'required',
            'perihal' => 'required',
            'lampiran' => 'nullable|mimes:pdf,docx,jpg,png|max:10240'
        ]);

        $data = $request->all();

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            // Gunakan nama file yang bersih
            $filename = time() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();

            // CARA BARU: Langsung pindahkan ke folder storage public
            $file->move(storage_path('app/public/surat_masuk'), $filename);

            $data['lampiran'] = $filename;
        }

        $surat = SuratMasuk::create($data);
        ActivityLog::record('Tambah Surat Masuk', 'SuratMasuk', "Menambah surat No: {$surat->no_surat} (Indeks: {$surat->no_indeks})");

        return redirect()->route('surat.index')->with('success', 'Arsip surat berhasil disimpan!');
    }

    public function edit($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surat.surat_masuk.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $request->validate(['no_indeks' => 'required|unique:db_pm_hukum.tb_surat_masuk,no_indeks,' . $id]);

        $data = $request->all();

        if ($request->hasFile('lampiran')) {
            // Hapus file lama jika ada
            if ($surat->lampiran) {
                $oldPath = storage_path('app/public/surat_masuk/' . $surat->lampiran);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('lampiran');
            $filename = time() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();

            // Pindahkan file baru
            $file->move(storage_path('app/public/surat_masuk'), $filename);

            $data['lampiran'] = $filename;
        }

        $surat->update($data);
        ActivityLog::record('Update Surat Masuk', 'SuratMasuk', "Mengubah data surat No: {$surat->no_surat}");

        return redirect()->route('surat.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        if ($surat->lampiran) {
            Storage::delete('public/surat_masuk/' . $surat->lampiran);
        }
        $surat->delete();
        ActivityLog::record('Hapus Surat Masuk', 'SuratMasuk', "Menghapus arsip surat No: {$surat->no_surat}");

        return redirect()->route('surat.index')->with('success', 'Arsip telah dihapus!');
    }

    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $path = storage_path('app/public/surat_masuk/' . $surat->lampiran);

        if ($surat->lampiran && file_exists($path)) {
            ActivityLog::record('Download Lampiran', 'SuratMasuk', "Mengunduh file surat No: {$surat->no_surat}");
            return response()->download($path);
        }

        return back()->with('error', 'File tidak ditemukan di server.');
    }

    public function printPDF(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $query = SuratMasuk::query();
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tgl_surat', [$request->from_date, $request->to_date]);
        }

        $data_surat = $query->orderBy('no_indeks', 'asc')->get();
        $pdf = Pdf::loadView('surat.surat_masuk.print', compact('data_surat'))->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_Arsip.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SuratMasukExport($request->from_date, $request->to_date), 'Laporan_Surat_Masuk.xlsx');
    }
}
