<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PutusanSelaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $data;

    /**
     * Konstruktor untuk menerima data hasil filter dari Controller
     */
    public function __construct($data)
    {
        // Pastikan kita hanya mengambil collection data saja
        $this->data = $data;
    }

    /**
     * Mengambil koleksi data
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Menentukan Header Excel (Baris 1)
     */
    public function headings(): array
    {
        return [
            'NO',
            'SATUAN KERJA',
            'NOMOR PERKARA BANDING',
            'NOMOR PERKARA ASAL (PA)',
            'TANGGAL REGISTER',
            'TANGGAL PUTUSAN SELA',
            'KETUA MAJELIS',
        ];
    }

    /**
     * Memetakan data ke kolom Excel (Presisi)
     */
    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            strtoupper($row->nama_satker),
            $row->nomor_perkara_banding,
            $row->nomor_perkara_pa,
            date('d-m-Y', strtotime($row->tgl_register)),
            date('d-m-Y', strtotime($row->tgl_putusan_sela)),
            $row->ketua_majelis,
            $row->keterangan ?? '-',
        ];
    }

    /**
     * Styling Header (Indigo Theme)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'] // Warna Indigo sesuai View
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Event tambahan untuk Border dan Perataan
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $this->data->count() + 1; // +1 karena ada header
                $range = 'A1:H' . $lastRow;

                // 1. Tambahkan Border Tipis ke seluruh tabel
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 2. Center alignment untuk kolom tertentu (No, Tgl)
                $event->sheet->getDelegate()->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('E2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 3. Set tinggi baris header agar lebih lega
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
