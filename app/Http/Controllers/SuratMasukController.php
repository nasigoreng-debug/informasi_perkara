<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\ActivityLog; // Tambahkan pemanggilan Model Log
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Str;
use App\Exports\SuratMasukExport;
use Maatwebsite\Excel\Facades\Excel;

class SuratMasukController extends Controller
{
    public function dashboard()
    {
        $totalSurat = SuratMasuk::count();
        $suratBulanIni = SuratMasuk::whereMonth('tgl_surat', date('m'))->whereYear('tgl_surat', date('Y'))->count();
        $suratHariIni = SuratMasuk::whereDate('tgl_surat', date('Y-m-d'))->count();
        $recentSurat = SuratMasuk::latest('tgl_surat')->take(5)->get();

        return view('surat.surat_masuk.dashboard', compact('totalSurat', 'suratBulanIni', 'suratHariIni', 'recentSurat'));
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

        // LOG: Akses Daftar Surat
        ActivityLog::record('Akses Arsip', 'SuratMasuk', 'Membuka daftar arsip surat masuk');

        return view('surat.surat_masuk.index', compact('data_surat', 'isDefault'));
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
            $filename = time() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/surat_masuk'), $filename);
            $data['lampiran'] = $filename;
        }

        $surat = SuratMasuk::create($data);

        // LOG: Tambah Surat
        ActivityLog::record('Tambah Arsip', 'SuratMasuk', "Menambah surat baru No: {$surat->no_surat} (Indeks: {$surat->no_indeks})");

        return redirect()->route('surat.index')->with('success', 'Arsip surat berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $request->validate(['no_indeks' => 'required|unique:db_pm_hukum.tb_surat_masuk,no_indeks,' . $id]);

        $data = $request->all();
        if ($request->hasFile('lampiran')) {
            if ($surat->lampiran) {
                $oldPath = public_path('storage/surat_masuk/' . $surat->lampiran);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $file = $request->file('lampiran');
            $filename = time() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/surat_masuk'), $filename);
            $data['lampiran'] = $filename;
        }

        $surat->update($data);

        // LOG: Update Surat
        ActivityLog::record('Update Arsip', 'SuratMasuk', "Mengubah data surat No: {$surat->no_surat}");

        return redirect()->route('surat.index')->with('success', 'Data & Lampiran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $nomorLama = $surat->no_surat;

        if ($surat->lampiran) {
            $filePath = public_path('storage/surat_masuk/' . $surat->lampiran);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $surat->delete();

        // LOG: Hapus Surat
        ActivityLog::record('Hapus Arsip', 'SuratMasuk', "Menghapus arsip surat No: {$nomorLama}");

        return redirect()->route('surat.index')->with('success', 'Arsip telah dihapus!');
    }

    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $path = public_path('storage/surat_masuk/' . $surat->lampiran);

        if ($surat->lampiran && File::exists($path)) {
            // LOG: Download Lampiran
            ActivityLog::record('Download Lampiran', 'SuratMasuk', "Mengunduh file surat No: {$surat->no_surat}");
            return response()->download($path);
        }
        return back()->with('error', 'File tidak ditemukan.');
    }

    public function printPDF(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $query = SuratMasuk::on('db_pm_hukum');
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tgl_surat', [$request->from_date, $request->to_date]);
        }

        $data_surat = $query->orderBy('no_indeks', 'asc')->get();

        // LOG: Cetak PDF
        ActivityLog::record('Cetak PDF', 'SuratMasuk', "Mencetak laporan arsip PDF periode " . ($request->from_date ?? 'Semua'));

        $pdf = Pdf::loadView('surat.surat_masuk.print', compact('data_surat'))->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_Arsip.pdf');
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'Laporan_Arsip_Surat_' . date('Ymd_His') . '.xlsx';

        // LOG: Export Excel
        ActivityLog::record('Export Excel', 'SuratMasuk', "Mengekspor daftar arsip ke Excel");

        return Excel::download(new SuratMasukExport($request->from_date, $request->to_date), $fileName);
    }
}
