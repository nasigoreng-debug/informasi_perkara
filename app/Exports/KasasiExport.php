<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KasasiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $key) {
            return [
                'No'                => $key + 1,
                'Satker'            => $item->pengadilan_agama ?? '-',
                'Ketua Majelis'     => $item->kmh ?? '-',
                'No. PTA'           => $item->no_pta ?? '-',
                'No. Kasasi'        => $item->no_kasasi ?? '-',
                'Tgl Putus MA'      => $item->tgl_putusan ? date('d/m/Y', strtotime($item->tgl_putusan)) : 'Proses',

                // KOLOM TERSENDIRI YANG KAMU MINTA
                'Status Putusan MA' => $item->status_label ?? '-',

                'Status PDF'        => $item->status_pdf_label,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['MONITORING DATA KASASI SE-WILAYAH PTA BANDUNG'],
            ['Laporan Sinkronisasi Data SIPP Satker'],
            [],
            ['NO', 'SATKER', 'KETUA MAJELIS', 'NO. PTA', 'NO. KASASI', 'TGL PUTUS MA', 'status_label', 'STATUS PDF']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['italic' => true]],
            4 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'F2F2F2']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
