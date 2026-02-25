<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class SuratMasukController extends Controller
{

    public function dashboard()
    {
        // Statistik sederhana
        $totalSurat = SuratMasuk::count();
        $suratBulanIni = SuratMasuk::whereMonth('tgl_surat', date('m'))->whereYear('tgl_surat', date('Y'))->count();
        $suratHariIni = SuratMasuk::whereDate('tgl_surat', date('Y-m-d'))->count();

        // Ambil 5 surat terbaru untuk tabel di dashboard
        $recentSurat = SuratMasuk::latest('tgl_surat')->take(5)->get();

        return view('surat.surat_masuk.dashboard', compact('totalSurat', 'suratBulanIni', 'suratHariIni', 'recentSurat'));
    }

    public function index(Request $request)
    {
        $query = \App\Models\SuratMasuk::query();

        // 1. Cek apakah ada filter aktif dari user
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

        // 2. Jika user klik "Lihat Semua", kita bypass filter tahun
        $showAll = $request->get('all') == 'true';

        // 3. LOGIKA DEFAULT: Jika tidak sedang filter & tidak klik "Lihat Semua", tampilkan Tahun Berjalan
        $isDefault = false;
        if (!$isFiltering && !$showAll) {
            $query->whereYear('tgl_surat', date('Y'));
            $isDefault = true;
        }

        $data_surat = $query->latest('tgl_surat')->paginate(10)->withQueryString();

        return view('surat.surat_masuk.index', compact('data_surat', 'isDefault'));
    }

    public function create()
    {
        $lastIndeks = SuratMasuk::max('no_indeks');
        $nextIndeks = $lastIndeks ? $lastIndeks + 1 : 1;
        return view('surat.surat_masuk.create', compact('nextIndeks'));
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
        SuratMasuk::create($data);
        return redirect()->route('surat.index')->with('success', 'Arsip surat berhasil disimpan!');
    }

    public function edit($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surat.surat_masuk.edit', compact('surat'));
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
        return redirect()->route('surat.index')->with('success', 'Data & Lampiran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        if ($surat->lampiran) {
            $filePath = public_path('storage/surat_masuk/' . $surat->lampiran);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Arsip telah dihapus!');
    }

    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $path = public_path('storage/surat_masuk/' . $surat->lampiran);
        if ($surat->lampiran && File::exists($path)) {
            return response()->download($path);
        }
        return back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * FUNGSI CETAK PDF (SULTAN VERSION)
     */
    public function printPDF(Request $request)
    {
        try {
            // 1. Naikkan limit memori & waktu (Laragon sering butuh ini untuk PDF)
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            // 2. Ambil data dengan koneksi eksplisit
            $query = \App\Models\SuratMasuk::on('db_pm_hukum');

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('tgl_surat', [$request->from_date, $request->to_date]);
            }

            $data_surat = $query->orderBy('no_indeks', 'asc')->get();

            if ($data_surat->isEmpty()) {
                return back()->with('error', 'Data tidak ditemukan untuk dicetak.');
            }

            // 3. Render View ke PDF
            $pdf = Pdf::loadView('surat.surat_masuk.print', compact('data_surat'))
                ->setPaper('a4', 'landscape');

            return $pdf->stream('Laporan_Arsip.pdf');
        } catch (\Exception $e) {
            // Tampilkan error aslinya agar kita tidak menebak-nebak lagi
            return response()->json([
                'status' => 'Error 500 - Masalah Internal',
                'pesan' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * MENAMPILKAN FORM CREATE DALAM MODAL
     */
    public function createModal()
    {
        $lastIndeks = SuratMasuk::max('no_indeks');
        $nextIndeks = $lastIndeks ? $lastIndeks + 1 : 1;

        return view('surat.surat_masuk.modal-create', compact('nextIndeks'));
    }

    /**
     * MENAMPILKAN FORM EDIT DALAM MODAL
     */
    public function editModal($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surat.surat_masuk.modal-edit', compact('surat'));
    }

    /**
     * MENYIMPAN DATA DARI MODAL (AJAX)
     */
    public function storeModal(Request $request)
    {
        try {
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

            SuratMasuk::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Arsip surat berhasil disimpan!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * UPDATE DATA DARI MODAL (AJAX)
     */
    public function updateModal(Request $request, $id)
    {
        try {
            $surat = SuratMasuk::findOrFail($id);

            $request->validate([
                'no_indeks' => 'required|unique:db_pm_hukum.tb_surat_masuk,no_indeks,' . $id,
                'no_surat' => 'required',
                'tgl_surat' => 'required|date',
                'asal_surat' => 'required',
                'perihal' => 'required',
                'lampiran' => 'nullable|mimes:pdf,docx,jpg,png|max:10240'
            ]);

            $data = $request->all();

            if ($request->hasFile('lampiran')) {
                // Hapus file lama
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

            return response()->json([
                'success' => true,
                'message' => 'Arsip surat berhasil diperbarui!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }
}
