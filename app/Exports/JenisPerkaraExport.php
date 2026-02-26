<?php

namespace App\Exports;

use App\Services\BandingService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JenisPerkaraExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $tglAwal;
    protected $tglAkhir;
    protected $bandingService;

    public function __construct($tglAwal, $tglAkhir)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->bandingService = new BandingService();
    }

    public function collection()
    {
        $data = $this->bandingService->getRekapJenisPerkara($this->tglAwal, $this->tglAkhir);

        return collect($data)->map(function ($row, $index) {
            return [
                'No' => ($row->kategori === 'TOTAL SELURUH JENIS PERKARA') ? '' : $index + 1,
                'Jenis Perkara' => $row->kategori,
                'Total' => $row->total,
                'E-Court' => $row->ecourt,
                'Manual' => $row->manual,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['LAPORAN REKAPITULASI JENIS PERKARA BANDING'],
            ['PERIODE: ' . $this->tglAwal . ' S/D ' . $this->tglAkhir],
            [], // Baris kosong
            ['NO', 'JENIS PERKARA', 'TOTAL', 'E-COURT', 'MANUAL']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul Besar
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
            4 => [
                'font' => ['bold' => true],
                'borders' => [
                    'outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'startColor' => ['argb' => 'F2F2F2']
                ]
            ],
        ];
    }
}
