<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratKeluarExport;

class SuratKeluarController extends Controller
{
    /**
     * DASHBOARD - Ringkasan Data
     */
    public function dashboard()
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

        return view('surat.surat_keluar.index', compact('data'));
    }

    public function create()
    {
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
        if ($request->hasFile('surat_pta')) {
            $file = $request->file('surat_pta');
            $name = time() . '_RESMI_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/resmi'), $name);
            $input['surat_pta'] = $name;
        }
        if ($request->hasFile('konsep_surat')) {
            $file = $request->file('konsep_surat');
            $name = time() . '_KONSEP_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/konsep'), $name);
            $input['konsep_surat'] = $name;
        }

        SuratKeluar::create($input);
        return redirect()->route('surat.keluar.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('surat.surat_keluar.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $input = $request->all();

        if ($request->hasFile('surat_pta')) {
            if ($surat->surat_pta) {
                @unlink(storage_path('app/public/surat_keluar/resmi/' . $surat->surat_pta));
            }
            $file = $request->file('surat_pta');
            $name = time() . '_RESMI_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/resmi'), $name);
            $input['surat_pta'] = $name;
        }

        if ($request->hasFile('konsep_surat')) {
            if ($surat->konsep_surat) {
                @unlink(storage_path('app/public/surat_keluar/konsep/' . $surat->konsep_surat));
            }
            $file = $request->file('konsep_surat');
            $name = time() . '_KONSEP_' . Str::slug($request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/surat_keluar/konsep'), $name);
            $input['konsep_surat'] = $name;
        }

        $surat->update($input);
        return redirect()->route('surat.keluar.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function download($id, $type)
    {
        $surat = SuratKeluar::findOrFail($id);
        $folder = ($type == 'konsep') ? 'konsep' : 'resmi';
        $file = ($type == 'konsep') ? $surat->konsep_surat : $surat->surat_pta;
        $path = storage_path('app/public/surat_keluar/' . $folder . '/' . $file);
        return (file_exists($path)) ? response()->download($path) : back()->with('error', 'File tidak ada!');
    }

    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        if ($surat->surat_pta) @unlink(storage_path('app/public/surat_keluar/resmi/' . $surat->surat_pta));
        if ($surat->konsep_surat) @unlink(storage_path('app/public/surat_keluar/konsep/' . $surat->konsep_surat));
        $surat->delete();
        return back()->with('success', 'Data dihapus!');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SuratKeluarExport($request), 'Arsip_Surat_Keluar_' . date('YmdHis') . '.xlsx');
    }
}
