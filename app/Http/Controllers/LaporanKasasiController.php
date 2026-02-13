<?php

namespace App\Http\Controllers;

use App\Services\LaporanKasasiServiceL10;
use App\Config\SatkerConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanKasasiController extends Controller
{
    protected LaporanKasasiServiceL10 $kasasiService;

    public function __construct(LaporanKasasiServiceL10 $kasasiService)
    {
        $this->kasasiService = $kasasiService;
    }

    /**
     * Display laporan kasasi
     */
    public function index(Request $request): View
    {
        // 1. Ambil input Tahun & Bulan
        $tahun = (int) $request->input('tahun', date('Y'));
        $bulanInput = $request->input('bulan');
        $bulan = ($bulanInput !== null && $bulanInput !== '') ? (int) $bulanInput : null;

        // 2. Tarik data dari Service (Pastikan Service sudah menerima 2 parameter)
        $data = $this->kasasiService->getLaporanKasasi($tahun, $bulan);
        $totals = $this->kasasiService->getTotalPerSatker($tahun, $bulan);
        $grandTotal = $this->kasasiService->getGrandTotal($tahun, $bulan);
        $years = $this->kasasiService->getAvailableYears();

        // 3. FORMAT TANGGAL - Tetap dipertahankan sesuai logika kamu
        $data = $data->map(function ($item) {
            if ($item->tgl_reg_kasasi != '-') {
                try {
                    $tanggal = $item->tgl_reg_kasasi;

                    // Cek jika format sudah Y-m-d (ISO)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
                        $item->tgl_reg_kasasi = $tanggal;
                    } 
                    // Cek jika format d-m-Y
                    elseif (strpos($tanggal, '-') !== false && strlen($tanggal) <= 10) {
                        $item->tgl_reg_kasasi = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
                    }
                    // Format Indonesia 'dd MMMM yyyy'
                    else {
                        $konversiBulan = [
                            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
                            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
                            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
                            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December'
                        ];
                        $tanggal_en = str_replace(array_keys($konversiBulan), array_values($konversiBulan), $tanggal);
                        $item->tgl_reg_kasasi = Carbon::parse($tanggal_en)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::warning('Gagal parse tanggal: ' . $item->tgl_reg_kasasi);
                    $item->tgl_reg_kasasi = date('Y-m-d', strtotime($item->tgl_reg_kasasi)) ?: '-';
                }
            }
            return $item;
        });

        // 4. Masukkan 'bulan' ke compact agar View bisa membacanya
        return view('laporan.kasasi.index', compact(
            'data',
            'totals',
            'years',
            'tahun',
            'grandTotal',
            'bulan'
        ));
    }
}