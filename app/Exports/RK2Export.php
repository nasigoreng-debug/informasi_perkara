<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RK2Export implements FromCollection, WithHeadings, WithStyles, WithMapping, WithCustomStartCell
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

    // Menentukan sel mana tabel dimulai (agar ada ruang untuk judul di atas)
    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        $collection = $this->data->map(function ($row) {
            return (object) [
                'satker' => $row->satker_key == 'TASIKKOTA' ? 'TASIKMALAYA KOTA' : $row->satker_key,
                'sisa_lalu' => $row->sisa_lalu,
                'diterima' => $row->diterima,
                'beban' => $row->beban,
                'selesai' => $row->selesai,
                'sisa_ini' => $row->sisa_ini,
            ];
        });

        // HITUNG GRAND TOTAL
        $totalSisaLalu = $collection->sum('sisa_lalu');
        $totalDiterima = $collection->sum('diterima');
        $totalBeban    = $collection->sum('beban');
        $totalSelesai  = $collection->sum('selesai');
        $totalSisaAkhir = $collection->sum('sisa_ini');

        // TAMBAHKAN BARIS TOTAL KE KOLEKSI
        $collection->push((object) [
            'satker' => 'TOTAL SELURUH WILAYAH',
            'sisa_lalu' => $totalSisaLalu,
            'diterima' => $totalDiterima,
            'beban' => $totalBeban,
            'selesai' => $totalSelesai,
            'sisa_ini' => $totalSisaAkhir,
        ]);

        return $collection;
    }

    public function map($row): array
    {
        return [
            $row->satker,
            $row->sisa_lalu,
            $row->diterima,
            $row->beban,
            $row->selesai,
            $row->sisa_ini,
        ];
    }

    public function headings(): array
    {
        return ["SATUAN KERJA", "SISA LALU", "DITERIMA", "BEBAN", "PUTUS", "SISA AKHIR"];
    }

    public function styles(Worksheet $sheet)
    {
        // MENAMBAHKAN JUDUL LAPORAN DI BARIS ATAS
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN KEADAAN PERKARA BANDING (RK2)');

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'PERIODE: ' . date('d-m-Y', strtotime($this->tglAwal)) . ' S/D ' . date('d-m-Y', strtotime($this->tglAkhir)));

        // STYLE JUDUL
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

        // STYLE HEADER TABEL (BARIS 4)
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // STYLE BARIS TERAKHIR (GRAND TOTAL)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . $lastRow . ':F' . $lastRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $lastRow . ':F' . $lastRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F1F5F9');

        return [];
    }
}
