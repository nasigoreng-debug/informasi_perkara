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
        $tahun = $request->input('tahun', date('Y'));

        $data = $this->kasasiService->getLaporanKasasi((int) $tahun);
        $totals = $this->kasasiService->getTotalPerSatker((int) $tahun);
        $years = $this->kasasiService->getAvailableYears();
        $grandTotal = $this->kasasiService->getGrandTotal((int) $tahun);

        // FORMAT TANGGAL - FIXED
        $data = $data->map(function ($item) {
            if ($item->tgl_reg_kasasi != '-') {
                try {
                    // COBA PARSE DENGAN BEBERAPA FORMAT
                    $tanggal = $item->tgl_reg_kasasi;

                    // 1. COBA FORMAT 'd-m-Y'
                    if (strpos($tanggal, '-') !== false) {
                        $item->tgl_reg_kasasi = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
                    }
                    // 2. COBA FORMAT 'Y-m-d'
                    elseif (strpos($tanggal, '-') !== false && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
                        $item->tgl_reg_kasasi = $tanggal;
                    }
                    // 3. FORMAT INDONESIA 'dd MMMM yyyy'
                    else {
                        // KONVERSI BULAN INDONESIA KE INGGRIS
                        $bulan = [
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

                        $tanggal_en = str_replace(array_keys($bulan), array_values($bulan), $tanggal);
                        $item->tgl_reg_kasasi = Carbon::parse($tanggal_en)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // FALLBACK: GUNAKAN FORMAT APAPUN YANG ADA
                    Log::warning('Gagal parse tanggal: ' . $item->tgl_reg_kasasi);
                    $item->tgl_reg_kasasi = date('Y-m-d', strtotime($item->tgl_reg_kasasi)) ?: '-';
                }
            }
            return $item;
        });

        return view('laporan.kasasi.index', compact(
            'data',
            'totals',
            'years',
            'tahun',
            'grandTotal'
        ));
    }
}
