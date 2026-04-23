<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\ActivityLog; // ✅ PAKAI MODEL ACTIVITYLOG
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratKeluarExport;

class SuratKeluarController extends Controller
{
    /**
     * DASHBOARD - Ringkasan Data
     */
    public function dashboard(Request $request)
    {
        // 1. Total Seluruh Arsip Keluar
        $totalKeluar = SuratKeluar::count();

        // 2. Keluar Tahun Ini (Januari s/d Desember tahun berjalan)
        $keluarTahunIni = SuratKeluar::whereYear('tgl_surat', date('Y'))->count();

        // 3. Input Bulan Ini (Berdasarkan tgl_surat di bulan berjalan)
        $inputBulanIni = SuratKeluar::whereMonth('tgl_surat', date('m'))
            ->whereYear('tgl_surat', date('Y'))
            ->count();

        // Ambil 5 Arsip Terbaru untuk Tabel
        $recentSurat = SuratKeluar::latest()->take(5)->get();

        // ✅ LOG DASHBOARD (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Dashboard Surat Keluar',
            'description' => "Total Surat: {$totalKeluar} | Keluar Tahun Ini: {$keluarTahunIni} | Input Bulan Ini: {$inputBulanIni}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('surat.surat_keluar.dashboard', compact(
            'totalKeluar',
            'keluarTahunIni',
            'inputBulanIni',
            'recentSurat'
        ));
    }

    /**
     * INDEX SULTAN - List Data dengan Filter Otomatis
     */
    public function index(Request $request)
    {
        $query = SuratKeluar::query();

        // Default Filter: Awal Bulan s/d Hari Ini (Sesuai Request Bapak)
        if (!$request->filled('from_date')) {
            $request->merge(['from_date' => date('Y-01-01')]);
        }
        if (!$request->filled('to_date')) {
            $request->merge(['to_date' => date('Y-m-d')]);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_surat', 'like', "%{$request->search}%")
                    ->orWhere('tujuan_surat', 'like', "%{$request->search}%")
                    ->orWhere('perihal', 'like', "%{$request->search}%");
            });
        }

        $query->whereBetween('tgl_surat', [$request->from_date, $request->to_date]);

        $perPage = $request->get('per_page', 10);
        $data = $query->latest('tgl_surat')->paginate($perPage)->withQueryString();

        // ✅ LOG INDEX (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Index Surat Keluar',
            'description' => "Periode: {$request->from_date} s.d {$request->to_date} | Search: " . ($request->search ?: '-') . " | Total Data: " . $data->total(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('surat.surat_keluar.index', compact('data'));
    }

    public function create(Request $request)
    {
        // ✅ LOG CREATE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Form Tambah Surat Keluar',
            'description' => 'Membuka form tambah surat keluar',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('surat.surat_keluar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'tgl_surat' => 'required|date',
            'tujuan_surat' => 'required',
            'surat_pta' => 'nullable|mimes:pdf|max:10240',
            'konsep_surat' => 'nullable|mimes:docx,doc,rtf|max:10240'
        ]);

        $input = $request->all();
        $uploadedFiles = [];

        if ($request->hasFile('surat_pta')) {
            $file = $request->file('surat_pta');
            $name = time() . '_RESMI_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/resmi'), $name);
            $input['surat_pta'] = $name;
            $uploadedFiles[] = 'Surat PTA';
        }

        if ($request->hasFile('konsep_surat')) {
            $file = $request->file('konsep_surat');
            $name = time() . '_KONSEP_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/konsep'), $name);
            $input['konsep_surat'] = $name;
            $uploadedFiles[] = 'Konsep Surat';
        }

        SuratKeluar::create($input);

        // ✅ LOG STORE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Tambah Surat Keluar',
            'description' => "Nomor Surat: {$request->no_surat} | Tujuan: {$request->tujuan_surat} | Upload: " . (count($uploadedFiles) > 0 ? implode(', ', $uploadedFiles) : 'Tidak ada file'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('surat.keluar.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        // ✅ LOG EDIT (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Form Edit Surat Keluar',
            'description' => "Membuka form edit surat: {$surat->no_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('surat.surat_keluar.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $input = $request->all();
        $uploadedFiles = [];

        if ($request->hasFile('surat_pta')) {
            if ($surat->surat_pta) {
                @unlink(storage_path('app/public/surat_keluar/resmi/' . $surat->surat_pta));
            }
            $file = $request->file('surat_pta');
            $name = time() . '_RESMI_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/resmi'), $name);
            $input['surat_pta'] = $name;
            $uploadedFiles[] = 'Surat PTA';
        }

        if ($request->hasFile('konsep_surat')) {
            if ($surat->konsep_surat) {
                @unlink(storage_path('app/public/surat_keluar/konsep/' . $surat->konsep_surat));
            }
            $file = $request->file('konsep_surat');
            $name = time() . '_KONSEP_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/konsep'), $name);
            $input['konsep_surat'] = $name;
            $uploadedFiles[] = 'Konsep Surat';
        }

        $surat->update($input);

        // ✅ LOG UPDATE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Update Surat Keluar',
            'description' => "Nomor Surat: {$surat->no_surat} | Tujuan: {$surat->tujuan_surat} | Update File: " . (count($uploadedFiles) > 0 ? implode(', ', $uploadedFiles) : 'Tidak ada perubahan file'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('surat.keluar.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function download(Request $request, $id, $type)
    {
        $surat = SuratKeluar::findOrFail($id);
        $folder = ($type == 'konsep') ? 'konsep' : 'resmi';
        $file = ($type == 'konsep') ? $surat->konsep_surat : $surat->surat_pta;
        $path = storage_path('app/public/surat_keluar/' . $folder . '/' . $file);

        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ada!');
        }

        // ✅ LOG DOWNLOAD (PAKAI MODEL)
        $jenisFile = ($type == 'konsep') ? 'Konsep Surat' : 'Surat PTA';
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Download Surat Keluar',
            'description' => "Download {$jenisFile} | Nomor Surat: {$surat->no_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->download($path);
    }

    public function destroy(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $noSurat = $surat->no_surat;

        if ($surat->surat_pta) {
            @unlink(storage_path('app/public/surat_keluar/resmi/' . $surat->surat_pta));
        }
        if ($surat->konsep_surat) {
            @unlink(storage_path('app/public/surat_keluar/konsep/' . $surat->konsep_surat));
        }

        $surat->delete();

        // ✅ LOG DELETE (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus Surat Keluar',
            'description' => "Menghapus surat keluar: {$noSurat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Data dihapus!');
    }

    public function exportExcel(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-01-01'));
        $toDate = $request->get('to_date', date('Y-m-d'));

        $totalData = SuratKeluar::whereBetween('tgl_surat', [$fromDate, $toDate])->count();

        // ✅ LOG EXPORT (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Export Excel Surat Keluar',
            'description' => "Export Excel | Periode: {$fromDate} s.d {$toDate} | Total Data: {$totalData}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return Excel::download(new SuratKeluarExport($request), 'Arsip_Surat_Keluar_' . date('YmdHis') . '.xlsx');
    }
}
