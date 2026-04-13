<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\ActivityLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
        $totalSurat = SuratMasuk::count();
        $suratTahunIni = SuratMasuk::whereYear('tgl_masuk_pan', date('Y'))->count();
        $inputBulanIni = SuratMasuk::whereMonth('tgl_masuk_pan', date('m'))
            ->whereYear('tgl_masuk_pan', date('Y'))
            ->count();

        $recentSurat = SuratMasuk::latest('tgl_masuk_pan')->take(5)->get();

        return view('surat.surat_masuk.dashboard', compact(
            'totalSurat',
            'suratTahunIni',
            'inputBulanIni',
            'recentSurat'
        ));
    }

    /**
     * INDEX - Daftar Surat dengan Filter
     */
    public function index(Request $request)
    {
        $query = SuratMasuk::with(['creator', 'updater']);
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
        $lastSurat = SuratMasuk::orderBy('id', 'desc')->first();
        $nextIndeks = $lastSurat ? (int)$lastSurat->no_indeks + 1 : 1;
        $users = User::orderBy('name')->get();

        return view('surat.surat_masuk.create', compact('nextIndeks', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_indeks'  => 'required',
            'no_surat'   => 'required',
            'tgl_masuk_pan'   => 'required|date',
            'tgl_masuk_umum'   => 'required|date',
            'tgl_surat'  => 'required|date',
            'asal_surat' => 'required',
            'perihal'    => 'required',
            'disposisi'    => 'required',
            'keterangan'    => 'required',
            'lampiran'   => 'nullable|mimes:pdf,docx,jpg,png|max:10240'
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = uniqid() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat_masuk', $filename, 'public');
            $validated['lampiran'] = $filename;
        }

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $surat = SuratMasuk::create($validated);

        ActivityLog::record('Tambah Surat Masuk', 'SuratMasuk', "Menambah surat No: {$surat->no_surat} (Indeks: {$surat->no_indeks})");

        return redirect()->route('surat.masuk.index')->with('success', 'Arsip surat berhasil disimpan!');
    }

    public function edit($id)
    {
        $surat = SuratMasuk::with(['creator', 'updater'])->findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('surat.surat_masuk.edit', compact('surat', 'users'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'no_indeks'  => 'required',
            'no_surat'   => 'required',
            'tgl_masuk_pan'   => 'required|date',
            'tgl_masuk_umum'   => 'required|date',
            'tgl_surat'  => 'required|date',
            'asal_surat' => 'required',
            'perihal'    => 'required',
            'disposisi'    => 'required',
            'keterangan'    => 'required',
            'lampiran'   => 'nullable|mimes:pdf,docx,jpg,png|max:10240'
        ]);

        if ($request->hasFile('lampiran')) {
            if ($surat->lampiran) {
                Storage::disk('public')->delete('surat_masuk/' . $surat->lampiran);
            }
            $file = $request->file('lampiran');
            $filename = uniqid() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat_masuk', $filename, 'public');
            $validated['lampiran'] = $filename;
        }

        $validated['updated_by'] = auth()->id();
        $surat->update($validated);

        ActivityLog::record('Update Surat Masuk', 'SuratMasuk', "Mengubah data surat No: {$surat->no_surat}");

        return redirect()->route('surat.masuk.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        if ($surat->lampiran) {
            Storage::disk('public')->delete('surat_masuk/' . $surat->lampiran);
        }

        $surat->delete();

        ActivityLog::record('Hapus Surat Masuk', 'SuratMasuk', "Menghapus arsip surat No: {$surat->no_surat}");

        return redirect()->route('surat.masuk.index')->with('success', 'Arsip telah dihapus!');
    }

    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        if ($surat->lampiran && Storage::disk('public')->exists('surat_masuk/' . $surat->lampiran)) {
            ActivityLog::record('Download Lampiran', 'SuratMasuk', "Mengunduh file surat No: {$surat->no_surat}");
            return Storage::disk('public')->download('surat_masuk/' . $surat->lampiran);
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

        $pdf = Pdf::loadView('surat.surat_masuk.print', compact('data_surat'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Arsip_Surat_Masuk.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SuratMasukExport($request->from_date, $request->to_date), 'Laporan_Surat_Masuk.xlsx');
    }
}
