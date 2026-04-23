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
    public function dashboard(Request $request)
    {
        $totalSurat = SuratMasuk::count();
        $suratTahunIni = SuratMasuk::whereYear('tgl_masuk_pan', date('Y'))->count();
        $inputBulanIni = SuratMasuk::whereMonth('tgl_masuk_pan', date('m'))
            ->whereYear('tgl_masuk_pan', date('Y'))
            ->count();

        $recentSurat = SuratMasuk::latest('tgl_masuk_pan')->take(5)->get();

        // ✅ LOG DASHBOARD (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Dashboard Surat Masuk',
            'description' => "Total Surat: {$totalSurat} | Tahun Ini: {$suratTahunIni} | Bulan Ini: {$inputBulanIni}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

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

        // ✅ LOG INDEX (PAKAI MODEL)
        $filterInfo = "";
        if ($request->filled('search')) {
            $filterInfo .= " Search: {$request->search}";
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $filterInfo .= " Periode: {$request->from_date} s.d {$request->to_date}";
        }
        if ($isDefault) {
            $filterInfo .= " (Default: Tahun Ini)";
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Akses Surat Masuk',
            'description' => "Membuka daftar arsip surat masuk | Total Data: {$data_surat->total()}{$filterInfo}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('surat.surat_masuk.index', compact('data_surat', 'isDefault'));
    }

    public function create(Request $request)
    {
        $lastSurat = SuratMasuk::orderBy('id', 'desc')->first();
        $nextIndeks = $lastSurat ? (int)$lastSurat->no_indeks + 1 : 1;
        $users = User::orderBy('name')->get();

        // ✅ LOG CREATE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Form Tambah Surat Masuk',
            'description' => "Membuka form tambah surat masuk | Next Indeks: {$nextIndeks}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

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

        $uploadedFile = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = uniqid() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat_masuk', $filename, 'public');
            $validated['lampiran'] = $filename;
            $uploadedFile = $filename;
        }

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $surat = SuratMasuk::create($validated);

        // ✅ LOG STORE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Tambah Surat Masuk',
            'description' => "Menambah surat No: {$surat->no_surat} (Indeks: {$surat->no_indeks}) | Asal: {$surat->asal_surat}" . ($uploadedFile ? " | Upload lampiran" : ""),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('surat.masuk.index')->with('success', 'Arsip surat berhasil disimpan!');
    }

    public function edit(Request $request, $id)
    {
        $surat = SuratMasuk::with(['creator', 'updater'])->findOrFail($id);
        $users = User::orderBy('name')->get();

        // ✅ LOG EDIT (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Form Edit Surat Masuk',
            'description' => "Membuka form edit surat No: {$surat->no_surat} (Indeks: {$surat->no_indeks})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

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

        $uploadedFile = null;
        if ($request->hasFile('lampiran')) {
            if ($surat->lampiran) {
                Storage::disk('public')->delete('surat_masuk/' . $surat->lampiran);
            }
            $file = $request->file('lampiran');
            $filename = uniqid() . '_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat_masuk', $filename, 'public');
            $validated['lampiran'] = $filename;
            $uploadedFile = $filename;
        }

        $validated['updated_by'] = auth()->id();
        $surat->update($validated);

        // ✅ LOG UPDATE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Update Surat Masuk',
            'description' => "Mengubah data surat No: {$surat->no_surat}" . ($uploadedFile ? " | Upload lampiran baru" : ""),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('surat.masuk.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        if ($surat->lampiran) {
            Storage::disk('public')->delete('surat_masuk/' . $surat->lampiran);
        }

        $surat->delete();

        // ✅ LOG DESTROY (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus Surat Masuk',
            'description' => "Menghapus arsip surat No: {$surat->no_surat} (Indeks: {$surat->no_indeks})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('surat.masuk.index')->with('success', 'Arsip telah dihapus!');
    }

    public function download(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        if ($surat->lampiran && Storage::disk('public')->exists('surat_masuk/' . $surat->lampiran)) {
            // ✅ LOG DOWNLOAD (PAKAI MODEL)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Download Lampiran Surat Masuk',
                'description' => "Mengunduh file surat No: {$surat->no_surat}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

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

        // ✅ LOG PRINT PDF (PAKAI MODEL)
        $periodeInfo = "";
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $periodeInfo = " Periode: {$request->from_date} s.d {$request->to_date}";
        }
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Print PDF Surat Masuk',
            'description' => "Mencetak laporan arsip surat masuk | Total Data: {$data_surat->count()}{$periodeInfo}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $pdf = Pdf::loadView('surat.surat_masuk.print', compact('data_surat'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Arsip_Surat_Masuk.pdf');
    }

    public function exportExcel(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $query = SuratMasuk::query();
        if ($fromDate && $toDate) {
            $query->whereBetween('tgl_surat', [$fromDate, $toDate]);
        }
        $totalData = $query->count();

        // ✅ LOG EXPORT EXCEL (PAKAI MODEL)
        $periodeInfo = "";
        if ($fromDate && $toDate) {
            $periodeInfo = " Periode: {$fromDate} s.d {$toDate}";
        }
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Export Excel Surat Masuk',
            'description' => "Export laporan surat masuk ke Excel | Total Data: {$totalData}{$periodeInfo}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return Excel::download(new SuratMasukExport($fromDate, $toDate), 'Laporan_Surat_Masuk.xlsx');
    }
}
