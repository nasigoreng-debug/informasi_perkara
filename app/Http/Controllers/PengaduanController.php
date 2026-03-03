<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PengaduanController extends Controller
{
    /**
     * DASHBOARD PENGADUAN - Menampilkan Semua yang BELUM SELESAI
     */
    public function dashboard()
    {
        // Statistik Umum
        $total = \App\Models\Pengaduan::count();
        $selesai = \App\Models\Pengaduan::whereNotNull('tgl_selesai_pgd')->count();

        // 1. Hitung yang MASIH PROSES (tgl_selesai_pgd kosong)
        $proses = \App\Models\Pengaduan::whereNull('tgl_selesai_pgd')->count();

        // 2. Ambil SEMUA data yang belum selesai (tanpa batasan 14 hari)
        // Agar ke-10 data Bapak muncul semua di list
        $notif_deadline = \App\Models\Pengaduan::whereNull('tgl_selesai_pgd')
            ->latest('tgl_terima_pgd')
            ->get();

        \App\Models\ActivityLog::record('Akses Dashboard Pengaduan', 'Pengaduan', 'Memantau seluruh pengaduan aktif');

        return view('pengaduan.dashboard', compact('total', 'selesai', 'proses', 'notif_deadline'));
    }

    /**
     * INDEX - Daftar Pengaduan dengan Filter Tanggal Default
     */
    public function index(Request $request)
    {
        // Default Tanggal: Awal Tahun s.d Hari Ini
        $startDate = $request->input('from_date', date('Y-01-01'));
        $endDate = $request->input('to_date', date('Y-m-d'));

        $query = Pengaduan::query();

        // Pencarian No Pengaduan, Pelapor, atau Terlapor
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_pgd', 'like', "%{$request->search}%")
                    ->orWhere('pelapor', 'like', "%{$request->search}%")
                    ->orWhere('terlapor', 'like', "%{$request->search}%");
            });
        }

        $query->whereBetween('tgl_terima_pgd', [$startDate, $endDate]);
        $data = $query->latest('tgl_terima_pgd')->paginate(10)->withQueryString();

        ActivityLog::record('Akses PENGADUAN', 'Pengaduan', 'Membuka daftar pengaduan masyarakat');

        return view('pengaduan.index', compact('data', 'startDate', 'endDate'));
    }

    /**
     * CREATE - Form Tambah Pengaduan
     */
    public function create()
    {
        return view('pengaduan.create');
    }

    /**
     * STORE - Simpan Pengaduan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_pgd' => 'required',
            'tgl_terima_pgd' => 'required|date',
            'pelapor' => 'required',
            'terlapor' => 'required',
            'surat_pgd' => 'nullable|mimes:pdf|max:10240',
            'lampiran' => 'nullable|max:20480'
        ]);

        $input = $request->all();

        // Upload Surat Pengaduan (PDF)
        if ($request->hasFile('surat_pgd')) {
            $file = $request->file('surat_pgd');
            $name = time() . '_PENGADUAN_' . Str::slug($request->no_pgd) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/pengaduan/surat'), $name);
            $input['surat_pgd'] = $name;
        }

        // Upload Lampiran
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
     * EDIT - Form Ubah Data
     */
    public function edit($id)
    {
        $pgd = Pengaduan::findOrFail($id);
        return view('pengaduan.edit', compact('pgd'));
    }

    /**
     * UPDATE - Perbarui Data & Tracking Disposisi
     */
    public function update(Request $request, $id)
    {
        $pgd = Pengaduan::findOrFail($id);
        $input = $request->all();

        // 1. Update File Surat Pengaduan
        if ($request->hasFile('surat_pgd')) {
            // Hapus file lama jika ada
            if ($pgd->surat_pgd) @unlink(storage_path('app/public/pengaduan/surat/' . $pgd->surat_pgd));

            $file = $request->file('surat_pgd');
            $name = time() . '_PENGADUAN_' . Str::slug($request->no_pgd) . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/pengaduan/surat'), $name);

            $input['surat_pgd'] = $name;
        }

        // 2. Update File Lampiran (TAMBAHKAN BAGIAN INI)
        if ($request->hasFile('lampiran')) {
            // Hapus file lampiran lama jika ada
            if ($pgd->lampiran) @unlink(storage_path('app/public/pengaduan/lampiran/' . $pgd->lampiran));

            $file_lamp = $request->file('lampiran');
            $name_lamp = time() . '_LAMPIRAN_' . Str::slug($request->no_pgd) . '.' . $file_lamp->getClientOriginalExtension();
            $file_lamp->move(storage_path('app/public/pengaduan/lampiran'), $name_lamp);

            $input['lampiran'] = $name_lamp;
        }

        // Simpan semua perubahan
        $pgd->update($input);

        // Catat aktivitas
        ActivityLog::record('Update Pengaduan', 'Pengaduan', "Ubah Data No: {$pgd->no_pgd}");

        return redirect()->route('pengaduan.index')->with('success', 'Data pengaduan berhasil diperbarui!');
    }

    public function detail($id)
    {
        $pgd = \App\Models\Pengaduan::findOrFail($id);

        \App\Models\ActivityLog::record('Lihat Detail PENGADUAN', 'Pengaduan', "Melihat detail pengaduan No: {$pgd->no_pgd}");

        return view('pengaduan.detail', compact('pgd'));
    }

    /**
     * DOWNLOAD - Download Berkas PENGADUAN
     */
    public function download($id, $type)
    {
        $pgd = Pengaduan::findOrFail($id);
        $folder = ($type == 'surat') ? 'surat' : 'lampiran';
        $fileName = ($type == 'surat') ? $pgd->surat_pgd : $pgd->lampiran;
        $path = storage_path("app/public/pengaduan/{$folder}/" . $fileName);

        if ($fileName && file_exists($path)) {
            ActivityLog::record("Download Berkas PENGADUAN", 'Pengaduan', "File No: {$pgd->no_pgd}");
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

        if ($pgd->surat_pgd) @unlink(storage_path('app/public/pengaduan/surat/' . $pgd->surat_pgd));
        if ($pgd->lampiran) @unlink(storage_path('app/public/pengaduan/lampiran/' . $pgd->lampiran));

        $pgd->delete();
        ActivityLog::record('Hapus Pengaduan', 'Pengaduan', "Hapus No: {$pgd->no_pgd}");

        return redirect()->route('pengaduan.index')->with('success', 'Data pengaduan telah dihapus!');
    }

    public function modalDetail($id)
    {
        $pgd = \App\Models\Pengaduan::findOrFail($id);
        // Mengembalikan view tanpa layout (hanya isinya)
        return view('pengaduan.modal_detail', compact('pgd'));
    }

    public function exportExcel(Request $request)
    {
        $query = Pengaduan::query();

        // Filter Search
        if ($request->search) {
            $query->where('no_pgd', 'like', "%{$request->search}%")
                ->orWhere('pelapor', 'like', "%{$request->search}%")
                ->orWhere('terlapor', 'like', "%{$request->search}%");
        }

        // Filter Tanggal
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('tgl_terima_pgd', [$request->from_date, $request->to_date]);
        }

        $data = $query->get();

        // Buat file excel sederhana tanpa perlu class export tambahan (Fast Mode)
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }
            public function collection()
            {
                return $this->data->map(function ($item, $key) {
                    return [
                        $key + 1,
                        $item->no_pgd,
                        $item->tgl_terima_pgd,
                        $item->pelapor,
                        $item->terlapor,
                        $item->uraian_pgd,
                        $item->status_berkas,
                        $item->status_pgd,
                    ];
                });
            }
            public function headings(): array
            {
                return ['No', 'No. Pengaduan', 'Tgl Terima', 'Pelapor', 'Terlapor', 'Uraian', 'Posisi Berkas', 'Status'];
            }
        }, 'Register_Pengaduan_SIWAS_' . date('Ymd') . '.xlsx');
    }
}
