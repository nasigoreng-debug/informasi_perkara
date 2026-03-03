<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PengaduanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            ['REGISTER PENGADUAN - PTA BANDUNG'],
            ['Dicetak pada: ' . date('d/m/Y H:i')],
            [''],
            ['NO', 'NO. PENGADUAN', 'TGL TERIMA', 'PELAPOR', 'TERLAPOR (PA)', 'URAIAN', 'POSISI', 'STATUS']
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        return [
            ++$no,
            $item->no_pgd,
            $item->tgl_terima_pgd ? \Carbon\Carbon::parse($item->tgl_terima_pgd)->format('d/m/Y') : '-',
            $item->pelapor,
            'PA ' . $item->terlapor,
            $item->uraian_pgd,
            $item->status_berkas ?? 'PTSP',
            $item->tgl_selesai_pgd ? 'SELESAI' : 'PROSES'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = $this->data->count();
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        return [
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            5    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B0000']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A5:H' . ($count + 5) => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ],
        ];
    }
}
