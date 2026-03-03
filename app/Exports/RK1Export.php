<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RK1Export implements FromView, ShouldAutoSize, WithStyles
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

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->results) + 6;

        // Atur border untuk semua tabel (A5 sampai AI[lastRow])
        $sheet->getStyle('A5:AI' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [
            5 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 9],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'textRotation' => 90, // Muter teks 90 derajat (Vertikal)
                    'wrapText' => true
                ],
            ],
        ];
    }
}
