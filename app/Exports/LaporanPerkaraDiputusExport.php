<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPerkaraDiputusExport implements FromView, ShouldAutoSize
{
    protected $laporan, $start, $end, $jenisPerkara;

    public function __construct($laporan, $start, $end, $jenisPerkara)
    {
        $this->laporan = $laporan;
        $this->start = $start;
        $this->end = $end;
        $this->jenisPerkara = $jenisPerkara;
    }

    public function view(): View
    {
        return view('exports.laporan_perkara_diputus_excel', [
            'laporan' => $this->laporan,
            'start' => $this->start,
            'end' => $this->end,
            'jenisPerkara' => $this->jenisPerkara
        ]);
    }
}
