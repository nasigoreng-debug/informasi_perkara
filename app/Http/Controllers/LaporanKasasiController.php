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

        // 2. Tarik data dari Service
        $data = $this->kasasiService->getLaporanKasasi($tahun, $bulan);
        $totals = $this->kasasiService->getTotalPerSatker($tahun, $bulan);
        $grandTotal = $this->kasasiService->getGrandTotal($tahun, $bulan);
        $years = $this->kasasiService->getAvailableYears();

        // 3. FORMAT TANGGAL - Proteksi agar tidak muncul tanggal hari ini jika kosong
        $data = $data->map(function ($item) {
            $tanggalInput = trim($item->tgl_reg_kasasi ?? '');

            // Jika kosong, strip, atau nol database, set ke NULL
            if ($tanggalInput === '' || $tanggalInput === '-' || $tanggalInput === '0000-00-00') {
                $item->tgl_reg_kasasi = null;
            } else {
                try {
                    // Cek jika format sudah Y-m-d (ISO)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalInput)) {
                        $item->tgl_reg_kasasi = $tanggalInput;
                    }
                    // Cek jika format d-m-Y
                    elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $tanggalInput)) {
                        $item->tgl_reg_kasasi = Carbon::createFromFormat('d-m-Y', $tanggalInput)->format('Y-m-d');
                    }
                    // Format Indonesia 'dd MMMM yyyy'
                    else {
                        $konversiBulan = [
                            'Januari' => 'January',
                            'Februari' => 'February',
                            'Maret' => 'March',
                            'April' => 'April',
                            'Mei' => 'May',
                            'Juni' => 'June',
                            'Juli' => 'July',
                            'Agustus' => 'August',
                            'September' => 'September',
                            'Oktober' => 'October',
                            'November' => 'November',
                            'Desember' => 'December'
                        ];
                        $tanggal_en = str_replace(array_keys($konversiBulan), array_values($konversiBulan), $tanggalInput);
                        $item->tgl_reg_kasasi = Carbon::parse($tanggal_en)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::warning('Gagal parse tanggal: ' . $tanggalInput);
                    // Paksa NULL jika gagal parse agar tidak lari ke tanggal hari ini
                    $item->tgl_reg_kasasi = null;
                }
            }
            return $item;
        });

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
