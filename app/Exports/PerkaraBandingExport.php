<?php

// app/Exports/PerkaraBandingExport.php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PerkaraBandingExport implements FromView, ShouldAutoSize
{
    protected $results;
    protected $tgl_awal;
    protected $tgl_akhir;

    public function __construct($results, $tgl_awal, $tgl_akhir)
    {
        $this->results = $results;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }

    public function view(): View
    {
        return view('exports.banding_diterima_excel', [
            'results' => $this->results,
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir
        ]);
    }
}
