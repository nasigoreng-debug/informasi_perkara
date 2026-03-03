<?php

namespace App\Http\Controllers;

use App\Models\SuratKeputusan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Exports\SkExport;
use Maatwebsite\Excel\Facades\Excel;

class SuratKeputusanController extends Controller
{
    /**
     * DASHBOARD SK
     */
    public function dashboard()
    {
        $totalSK = SuratKeputusan::count();
        $skTahunIni = SuratKeputusan::where('tahun', date('Y'))->count();
        $inputBulanIni = SuratKeputusan::whereMonth('tgl_sk', date('m'))
            ->whereYear('tgl_sk', date('Y'))->count();

        $recentSK = SuratKeputusan::latest('tgl_sk')->take(5)->get();

        return view('surat.sk.dashboard', compact('totalSK', 'skTahunIni', 'inputBulanIni', 'recentSK'));
    }

    /**
     * HALAMAN INDEX (LIST DATA)
     */
    public function index(Request $request)
    {
        $query = SuratKeputusan::query();

        // Logika Default: 1 Januari s/d Hari Ini
        $startDate = $request->input('from_date', date('Y-01-01'));
        $endDate = $request->input('to_date', date('Y-m-d'));

        // Filter Pencarian No SK / Tentang
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_sk', 'like', "%{$request->search}%")
                    ->orWhere('tentang', 'like', "%{$request->search}%");
            });
        }

        // Eksekusi Filter Tanggal (Default atau Input User)
        $query->whereBetween('tgl_sk', [$startDate, $endDate]);

        $data = $query->latest('tgl_sk')->paginate(10)->withQueryString();

        // Tambahkan variabel flag untuk alert info di view
        $isDefault = !$request->filled('from_date') && !$request->filled('to_date') && !$request->filled('search');

        ActivityLog::record('Akses SK', 'SuratKeputusan', 'Membuka daftar arsip SK');

        return view('surat.sk.index', compact('data', 'startDate', 'endDate', 'isDefault'));
    }

    /**
     * HALAMAN TAMBAH (Fungsi yang tadi hilang/error)
     */
    public function create()
    {
        return view('surat.sk.create');
    }

    /**
     * PROSES SIMPAN DATA (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_sk' => 'required',
            'tgl_sk' => 'required|date',
            'tentang' => 'required',
            'dokumen' => 'nullable|mimes:pdf|max:10240',
            'konsep_sk' => 'nullable|mimes:docx,doc|max:10240'
        ]);

        $input = $request->all();
        $input['tahun'] = Carbon::parse($request->tgl_sk)->year;

        // Upload PDF Resmi
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $name = time() . '_RESMI_' . Str::slug($request->no_sk) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_sk/resmi'), $name);
            $input['dokumen'] = $name;
        }

        // Upload Word Konsep
        if ($request->hasFile('konsep_sk')) {
            $file = $request->file('konsep_sk');
            $name = time() . '_KONSEP_' . Str::slug($request->no_sk) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_sk/konsep'), $name);
            $input['konsep_sk'] = $name;
        }

        SuratKeputusan::create($input);

        ActivityLog::record('Tambah SK', 'SuratKeputusan', "Menambah arsip SK No: {$request->no_sk}");

        return redirect()->route('sk.index')->with('success', 'Arsip SK berhasil disimpan!');
    }

    /**
     * HALAMAN EDIT
     */
    public function edit($id)
    {
        $sk = SuratKeputusan::findOrFail($id);
        return view('surat.sk.edit', compact('sk'));
    }

    /**
     * PROSES UPDATE
     */
    public function update(Request $request, $id)
    {
        $sk = SuratKeputusan::findOrFail($id);

        $request->validate([
            'no_sk' => 'required',
            'tgl_sk' => 'required|date',
            'tentang' => 'required'
        ]);

        $input = $request->all();
        $input['tahun'] = Carbon::parse($request->tgl_sk)->year;

        if ($request->hasFile('dokumen')) {
            if ($sk->dokumen) @unlink(storage_path('app/public/surat_sk/resmi/' . $sk->dokumen));
            $file = $request->file('dokumen');
            $name = time() . '_RESMI_' . Str::slug($request->no_sk) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_sk/resmi'), $name);
            $input['dokumen'] = $name;
        }

        if ($request->hasFile('konsep_sk')) {
            if ($sk->konsep_sk) @unlink(storage_path('app/public/surat_sk/konsep/' . $sk->konsep_sk));
            $file = $request->file('konsep_sk');
            $name = time() . '_KONSEP_' . Str::slug($request->no_sk) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_sk/konsep'), $name);
            $input['konsep_sk'] = $name;
        }

        $sk->update($input);

        ActivityLog::record('Update SK', 'SuratKeputusan', "Mengubah data SK No: {$sk->no_sk}");

        return redirect()->route('sk.index')->with('success', 'Arsip SK berhasil diperbarui!');
    }

    /**
     * DOWNLOAD FILE
     */
    public function download($id, $type)
    {
        $sk = SuratKeputusan::findOrFail($id);
        $folder = ($type == 'resmi') ? 'resmi' : 'konsep';
        $fileName = ($type == 'resmi') ? $sk->dokumen : $sk->konsep_sk;
        $path = storage_path("app/public/surat_sk/{$folder}/" . $fileName);

        if ($fileName && file_exists($path)) {
            ActivityLog::record("Download SK ({$type})", 'SuratKeputusan', "File No: {$sk->no_sk}");
            return response()->download($path);
        }

        return back()->with('error', 'File tidak ditemukan di server.');
    }

    /**
     * HAPUS (DESTROY)
     */
    public function destroy($id)
    {
        $sk = SuratKeputusan::findOrFail($id);

        if ($sk->dokumen) @unlink(storage_path('app/public/surat_sk/resmi/' . $sk->dokumen));
        if ($sk->konsep_sk) @unlink(storage_path('app/public/surat_sk/konsep/' . $sk->konsep_sk));

        $sk->delete();

        ActivityLog::record('Hapus SK', 'SuratKeputusan', "Menghapus arsip SK No: {$sk->no_sk}");

        return redirect()->route('sk.index')->with('success', 'Arsip SK telah dihapus!');
    }

    public function exportExcel(Request $request)
    {
        // Catat log aktivitas (Identik Sultan)
        ActivityLog::record('Export Excel SK', 'SuratKeputusan', 'Mendownload laporan excel arsip SK');

        // Nama file otomatis: Arsip_SK_020326.xlsx
        $nama_file = 'Arsip_SK_' . date('dmy') . '.xlsx';

        return Excel::download(new SkExport($request), $nama_file);
    }
}
