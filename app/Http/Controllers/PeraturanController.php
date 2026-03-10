<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PeraturanController extends Controller
{
    /**
     * TAMPILAN INDEX PUBLIC (TANPA LOGIN)
     * Untuk halaman publik, log bisa dicatat tanpa user_id
     */
    public function index_public(Request $request)
    {
        $query = Peraturan::query();

        $searchTerm = $request->search;
        $jenisFilter = $request->jenis;

        if ($searchTerm) {
            $query->where('tentang', 'like', "%{$searchTerm}%");
        }
        if ($jenisFilter) {
            $query->where('jenis_peraturan', $jenisFilter);
        }

        $data = $query->orderBy('tahun', 'desc')->paginate(10);

        // ✅ LOG AKSES PUBLIC (tanpa user_id)
        $logMessage = "Akses publik JDIH";
        if ($searchTerm || $jenisFilter) {
            $logMessage .= " dengan filter";
            if ($searchTerm) $logMessage .= " - search: '{$searchTerm}'";
            if ($jenisFilter) $logMessage .= " - jenis: '{$jenisFilter}'";
        }

        ActivityLog::create([
            'user_id' => null, // Public access, no user
            'activity' => 'Akses Public JDIH',
            'description' => $logMessage,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

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

        // ✅ LOG INDEX dengan detail filter
        $logMessage = "Membuka daftar himpunan peraturan";
        if ($request->filled('search')) {
            $logMessage .= " - Pencarian: '{$request->search}'";
        }
        if ($request->filled('jenis')) {
            $logMessage .= " - Jenis: '{$request->jenis}'";
        }
        if ($request->filled('tahun')) {
            $logMessage .= " - Tahun: {$request->tahun}";
        }

        ActivityLog::record('Akses JDIH', 'Regulasi', $logMessage);

        return view('peraturan.index', compact('data'));
    }

    /**
     * FORM TAMBAH (CREATE)
     */
    public function create()
    {
        // ✅ LOG AKSES FORM TAMBAH
        ActivityLog::record('Akses Form Tambah Peraturan', 'Regulasi', 'Membuka form input peraturan baru');

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

        // ✅ LOG STORE
        ActivityLog::record('Tambah Peraturan', 'Regulasi', "Input {$request->jenis_peraturan} No: {$request->no_peraturan} Tahun {$request->tahun}");

        return redirect()->route('peraturan.index')->with('success', 'Dokumen hukum berhasil ditambahkan!');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $item = Peraturan::findOrFail($id);

        // ✅ LOG AKSES FORM EDIT
        ActivityLog::record('Akses Form Edit Peraturan', 'Regulasi', "Membuka form edit ID: {$id} - {$item->jenis_peraturan} No: {$item->no_peraturan}");

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

        // ✅ LOG UPDATE
        $logMessage = "Mengubah data ID: {$id} - {$peraturan->jenis_peraturan} No: {$peraturan->no_peraturan}";
        if ($request->hasFile('dokumen')) {
            $logMessage .= " (dengan upload dokumen baru)";
        }

        ActivityLog::record('Update Peraturan', 'Regulasi', $logMessage);

        return redirect()->route('peraturan.index')->with('success', 'Dokumen berhasil diperbarui!');
    }

    /**
     * HAPUS DATA (DESTROY)
     */
    public function destroy($id)
    {
        $peraturan = Peraturan::findOrFail($id);

        $info = "{$peraturan->jenis_peraturan} No: {$peraturan->no_peraturan} Tahun {$peraturan->tahun}";

        // Hapus file fisiknya
        if ($peraturan->dokumen) {
            $filePath = storage_path('app/public/peraturan/' . $peraturan->dokumen);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $peraturan->delete();

        // ✅ LOG DELETE
        ActivityLog::record('Hapus Peraturan', 'Regulasi', "Menghapus ID: {$id} - {$info}");

        return redirect()->route('peraturan.index')->with('success', 'Dokumen telah dihapus dari sistem!');
    }
}
