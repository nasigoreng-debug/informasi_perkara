<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PeraturanController extends Controller
{
    /**
     * TAMPILAN INDEX PUBLIC (TANPA LOGIN)
     */
    public function index_public(Request $request)
    {
        $query = Peraturan::query();
        if ($request->search) {
            $query->where('tentang', 'like', "%{$request->search}%");
        }
        if ($request->jenis) {
            $query->where('jenis_peraturan', $request->jenis);
        }
        $data = $query->orderBy('tahun', 'desc')->paginate(10);
        return view('peraturan_public', compact('data'));
    }

    /**
     * TAMPILAN INDEX 
     */
    public function index(Request $request)
    {
        $query = Peraturan::query();

        // Filter Pencarian Text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tentang', 'like', "%{$search}%")
                    ->orWhere('no_peraturan', 'like', "%{$search}%");
            });
        }

        // Filter Jenis Peraturan
        if ($request->filled('jenis')) {
            $query->where('jenis_peraturan', $request->jenis);
        }

        // Filter Tahun (Dari Kotak-kotak Tahun)
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $data = $query->orderBy('tahun', 'desc')->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Catat Log
        ActivityLog::record('Akses JDIH', 'Regulasi', 'Membuka daftar himpunan peraturan');

        return view('peraturan.index', compact('data'));
    }

    /**
     * FORM TAMBAH (CREATE)
     */
    public function create()
    {
        return view('peraturan.create');
    }

    /**
     * SIMPAN DATA (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_peraturan' => 'required',
            'jenis_peraturan' => 'required',
            'tahun' => 'required|numeric',
            'tentang' => 'required',
            'dokumen' => 'nullable|mimes:pdf|max:20480' // Max 20MB
        ]);

        $input = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            // Penamaan file yang rapi
            $name = time() . '_REGULASI_' . Str::slug($request->no_peraturan) . '.pdf';
            $file->move(storage_path('app/public/peraturan'), $name);
            $input['dokumen'] = $name;
        }

        Peraturan::create($input);

        ActivityLog::record('Tambah Peraturan', 'Regulasi', "Input {$request->jenis_peraturan} No: {$request->no_peraturan}");

        return redirect()->route('peraturan.index')->with('success', 'Dokumen hukum berhasil ditambahkan!');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $item = Peraturan::findOrFail($id);
        return view('peraturan.edit', compact('item'));
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        $peraturan = Peraturan::findOrFail($id);

        $request->validate([
            'no_peraturan' => 'required',
            'jenis_peraturan' => 'required',
            'tahun' => 'required|numeric',
            'tentang' => 'required',
            'dokumen' => 'nullable|mimes:pdf|max:20480'
        ]);

        $input = $request->all();

        if ($request->hasFile('dokumen')) {
            // Hapus file lama jika ada
            if ($peraturan->dokumen) {
                @unlink(storage_path('app/public/peraturan/' . $peraturan->dokumen));
            }

            $file = $request->file('dokumen');
            $name = time() . '_UPDATE_' . Str::slug($request->no_peraturan) . '.pdf';
            $file->move(storage_path('app/public/peraturan'), $name);
            $input['dokumen'] = $name;
        }

        $peraturan->update($input);

        ActivityLog::record('Update Peraturan', 'Regulasi', "Mengubah data No: {$peraturan->no_peraturan}");

        return redirect()->route('peraturan.index')->with('success', 'Dokumen berhasil diperbarui!');
    }

    /**
     * HAPUS DATA (DESTROY)
     */
    public function destroy($id)
    {
        $peraturan = Peraturan::findOrFail($id);

        // Hapus file fisiknya
        if ($peraturan->dokumen) {
            @unlink(storage_path('app/public/peraturan/' . $peraturan->dokumen));
        }

        $peraturan->delete();

        ActivityLog::record('Hapus Peraturan', 'Regulasi', "Menghapus ID: {$id}");

        return redirect()->route('peraturan.index')->with('success', 'Dokumen telah dihapus dari sistem!');
    }
}
