<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RK1Export implements FromCollection, WithHeadings, WithStyles, WithMapping, WithCustomStartCell
{
    protected $data;
    protected $tglAwal;
    protected $tglAkhir;

    public function __construct($data, $tglAwal, $tglAkhir)
    {
        $this->data = collect($data);
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        $collection = $this->data->map(function ($row) {
            return (object) [
                'satker' => $row->satker,
                'total' => $row->total_perkara,
                'ecourt' => $row->jumlah_ecourt,
                'manual' => $row->jumlah_manual,
            ];
        });

        // Tambah Grand Total
        $collection->push((object) [
            'satker' => 'TOTAL SELURUH WILAYAH',
            'total' => $collection->sum('total'),
            'ecourt' => $collection->sum('ecourt'),
            'manual' => $collection->sum('manual'),
        ]);

        return $collection;
    }

    public function map($row): array
    {
        return [
            $row->satker,
            $row->total,
            $row->ecourt,
            $row->manual,
        ];
    }

    public function headings(): array
    {
        return ["SATUAN KERJA", "TOTAL PERKARA", "E-COURT", "MANUAL"];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'LAPORAN PENERIMAAN PERKARA BANDING (RK1)');
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'PERIODE: ' . date('d-m-Y', strtotime($this->tglAwal)) . ' S/D ' . date('d-m-Y', strtotime($this->tglAkhir)));

        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . $lastRow . ':D' . $lastRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $lastRow . ':D' . $lastRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F1F5F9');

        return [];
    }
}
