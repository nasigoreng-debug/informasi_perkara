<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPerkaraExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data, $jenisPerkara;

    public function __construct($data, $jenisPerkara) {
        $this->data = $data;
        $this->jenisPerkara = $jenisPerkara;
    }

    public function collection() { return collect($this->data); }

    public function headings(): array {
        $headers = ['No', 'Satuan Kerja'];
        foreach ($this->jenisPerkara as $label) $headers[] = $label;
        $headers[] = 'TOTAL';
        return $headers;
    }

    public function map($row): array {
        $mapped = [$row->no_urut, $row->satker];
        foreach ($this->jenisPerkara as $a => $l) $mapped[] = $row->$a;
        $mapped[] = $row->jml;
        return $mapped;
    }

    public function styles(Worksheet $sheet) {
        $lastCol = $sheet->getHighestColumn();
        $sheet->getRowDimension(1)->setRowHeight(160);
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['textRotation' => 90, 'vertical' => Alignment::VERTICAL_BOTTOM, 'horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A1:'.$lastCol.$sheet->getHighestRow() => [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]
        ];
    }
}