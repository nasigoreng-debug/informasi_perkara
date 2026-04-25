<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengaduanExport;

class PengaduanController extends Controller
{
    /**
     * DASHBOARD PENGADUAN
     * Menampilkan ringkasan statistik dan monitoring deadline
     */
    public function dashboard()
    {
        $notif_deadline = Pengaduan::whereNull('tgl_selesai_pgd')
            ->orderBy('tgl_terima_pgd', 'asc')
            ->get();

        $total_semua = Pengaduan::count();
        $total_selesai = Pengaduan::whereNotNull('tgl_selesai_pgd')->count();
        $total_proses = Pengaduan::whereNull('tgl_selesai_pgd')->count();

        // LOG AKSES DASHBOARD
        ActivityLog::record('Akses Dashboard PENGADUAN', 'Pengaduan', 'Membuka ringkasan statistik dan monitoring deadline');

        return view('pengaduan.dashboard', compact(
            'notif_deadline',
            'total_semua',
            'total_selesai',
            'total_proses'
        ));
    }

    /**
     * INDEX - Daftar Pengaduan
     * Menampilkan list pengaduan dengan filter tanggal dan pencarian multi-kolom
     */
    public function index(Request $request)
    {
        $startDate = $request->input('from_date', date('Y-01-01'));
        $endDate = $request->input('to_date', date('Y-m-d'));

        $query = Pengaduan::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_pgd', 'like', "%{$request->search}%")
                    ->orWhere('no_surat_pgd', 'like', "%{$request->search}%")
                    ->orWhere('pelapor', 'like', "%{$request->search}%")
                    ->orWhere('terlapor', 'like', "%{$request->search}%")
                    ->orWhere('uraian_pgd', 'like', "%{$request->search}%");
            });
        }

        $query->whereBetween('tgl_terima_pgd', [$startDate, $endDate]);
        $data = $query->latest('tgl_terima_pgd')->paginate(10)->withQueryString();

        // LOG AKSES INDEX
        $logMessage = "Membuka daftar pengaduan";
        if ($request->filled('search')) $logMessage .= " - Pencarian: '{$request->search}'";
        if ($request->filled('from_date')) $logMessage .= " - Periode: {$startDate} s/d {$endDate}";

        ActivityLog::record('Akses PENGADUAN', 'Pengaduan', $logMessage);

        return view('pengaduan.index', compact('data', 'startDate', 'endDate'));
    }

    /**
     * CREATE - Form Tambah Pengaduan
     */
    public function create()
    {
        ActivityLog::record('Akses Form Tambah PENGADUAN', 'Pengaduan', 'Membuka form input pengaduan baru');
        return view('pengaduan.create');
    }

    /**
     * STORE - Simpan Pengaduan Baru
     * Menangani semua kolom sesuai struktur tb_pengaduan
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_pgd'            => 'required|unique:tb_pengaduan,no_pgd',
            'tgl_terima_pgd'    => 'required|date',
            'pelapor'           => 'required',
            'terlapor'          => 'required',
            'surat_pgd'         => 'nullable|mimes:pdf|max:10240',
            'lampiran'          => 'nullable|max:20480'
        ]);

        $input = $request->all();

        // Upload surat pengaduan
        if ($request->hasFile('surat_pgd')) {
            $file = $request->file('surat_pgd');
            $name = time() . '_PENGADUAN_' . Str::slug($request->no_pgd) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/pengaduan/surat'), $name);
            $input['surat_pgd'] = $name;
        }

        // Upload lampiran
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $name = time() . '_LAMP_PENGADUAN_' . Str::slug($request->no_pgd) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/pengaduan/lampiran'), $name);
            $input['lampiran'] = $name;
        }

        Pengaduan::create($input);

        ActivityLog::record('Tambah Pengaduan', 'Pengaduan', "Input Pengaduan No: {$request->no_pgd}");

        return redirect()->route('pengaduan.index')->with('success', 'Data pengaduan berhasil disimpan!');
    }

    /**
     * EDIT - Form Edit Pengaduan
     */
    public function edit($id)
    {
        $pgd = Pengaduan::findOrFail($id);
        ActivityLog::record('Akses Form Edit PENGADUAN', 'Pengaduan', "Membuka form edit No: {$pgd->no_pgd}");

        return view('pengaduan.edit', compact('pgd'));
    }

    /**
     * UPDATE - Perbarui Data Pengaduan
     */
    public function update(Request $request, $id)
    {
        $pgd = Pengaduan::findOrFail($id);

        $request->validate([
            'no_pgd'            => 'required|unique:tb_pengaduan,no_pgd,' . $id,
            'tgl_terima_pgd'    => 'required|date',
            'surat_pgd'         => 'nullable|mimes:pdf|max:10240',
        ]);

        $input = $request->all();

        // Update File Surat
        if ($request->hasFile('surat_pgd')) {
            if ($pgd->surat_pgd && file_exists(storage_path('app/public/pengaduan/surat/' . $pgd->surat_pgd))) {
                @unlink(storage_path('app/public/pengaduan/surat/' . $pgd->surat_pgd));
            }

            $file = $request->file('surat_pgd');
            $name = time() . '_PENGADUAN_' . Str::slug($request->no_pgd) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/pengaduan/surat'), $name);
            $input['surat_pgd'] = $name;
        }

        // Update File Lampiran
        if ($request->hasFile('lampiran')) {
            if ($pgd->lampiran && file_exists(storage_path('app/public/pengaduan/lampiran/' . $pgd->lampiran))) {
                @unlink(storage_path('app/public/pengaduan/lampiran/' . $pgd->lampiran));
            }

            $file_lamp = $request->file('lampiran');
            $name_lamp = time() . '_LAMPIRAN_' . Str::slug($request->no_pgd) . '.' . $file_lamp->getClientOriginalExtension();
            $file_lamp->move(storage_path('app/public/pengaduan/lampiran'), $name_lamp);
            $input['lampiran'] = $name_lamp;
        }

        $pgd->update($input);

        ActivityLog::record('Update Pengaduan', 'Pengaduan', "Ubah Data No: {$pgd->no_pgd}");

        return redirect()->route('pengaduan.index')->with('success', 'Data pengaduan berhasil diperbarui!');
    }

    /**
     * DETAIL - Lihat Detail Pengaduan
     */
    public function detail($id)
    {
        $pgd = Pengaduan::findOrFail($id);
        ActivityLog::record('Lihat Detail PENGADUAN', 'Pengaduan', "Melihat detail pengaduan No: {$pgd->no_pgd}");

        return view('pengaduan.detail', compact('pgd'));
    }

    /**
     * DOWNLOAD - Berkas
     */
    public function download($id, $type)
    {
        $pgd = Pengaduan::findOrFail($id);
        $folder = ($type == 'surat') ? 'surat' : 'lampiran';
        $fileName = ($type == 'surat') ? $pgd->surat_pgd : $pgd->lampiran;
        $path = storage_path("app/public/pengaduan/{$folder}/" . $fileName);

        if ($fileName && file_exists($path)) {
            ActivityLog::record("Download Berkas PENGADUAN", 'Pengaduan', "File No: {$pgd->no_pgd} ({$type})");
            return response()->download($path);
        }

        return back()->with('error', 'Berkas tidak ditemukan di server.');
    }

    /**
     * DESTROY - Hapus Pengaduan
     */
    public function destroy($id)
    {
        $pgd = Pengaduan::findOrFail($id);

        if ($pgd->surat_pgd) {
            @unlink(storage_path('app/public/pengaduan/surat/' . $pgd->surat_pgd));
        }

        if ($pgd->lampiran) {
            @unlink(storage_path('app/public/pengaduan/lampiran/' . $pgd->lampiran));
        }

        $pgd->delete();

        ActivityLog::record('Hapus Pengaduan', 'Pengaduan', "Hapus No: {$pgd->no_pgd}");

        return redirect()->route('pengaduan.index')->with('success', 'Data pengaduan telah dihapus!');
    }

    /**
     * MODAL DETAIL - Tracking
     */
    public function modalDetail($id)
    {
        $pgd = Pengaduan::findOrFail($id);
        ActivityLog::record('Lihat Tracking Modal', 'Pengaduan', "Melihat alur proses No: {$pgd->no_pgd}");

        return view('pengaduan.modal_detail', compact('pgd'));
    }

    /**
     * EXPORT EXCEL
     */
    public function exportExcel(Request $request)
    {
        $query = Pengaduan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pgd', 'like', "%{$search}%")
                    ->orWhere('pelapor', 'like', "%{$search}%")
                    ->orWhere('terlapor', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tgl_terima_pgd', [$request->from_date, $request->to_date]);
        }

        $data = $query->orderBy('tgl_terima_pgd', 'desc')->get();

        $logMessage = "Menarik laporan excel (Total: " . $data->count() . " data)";
        ActivityLog::record('Export Excel PENGADUAN', 'Pengaduan', $logMessage);

        return Excel::download(
            new PengaduanExport($data),
            'Register_Pengaduan_' . date('Ymd_His') . '.xlsx'
        );
    }
}
