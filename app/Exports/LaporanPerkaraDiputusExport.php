<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LaporanPerkaraDiputusExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $laporan;
    protected $jenisPerkara;
    protected $tahun;
    protected $bulan;
    protected $triwulan;
    protected $totalRow;

    public function __construct($laporan, $jenisPerkara, $tahun, $bulan = null, $triwulan = null)
    {
        $this->laporan = $laporan;
        $this->jenisPerkara = $jenisPerkara;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->triwulan = $triwulan;

        // Cari baris TOTAL
        foreach ($laporan as $row) {
            if ($row->satker == 'JUMLAH KESELURUHAN' || $row->satker == 'TOTAL') {
                $this->totalRow = $row;
                break;
            }
        }
    }

    public function collection()
    {
        // Filter hanya data satker (bukan total)
        $data = collect($this->laporan)->filter(function ($item) {
            return $item->satker != 'JUMLAH KESELURUHAN' && $item->satker != 'TOTAL';
        })->values();

        // Tambahkan baris total di akhir koleksi
        if ($this->totalRow) {
            $data->push($this->totalRow);
        }

        return $data;
    }

    public function headings(): array
    {
        $bulanText = '';
        if ($this->bulan) {
            $namaBulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $bulanText = 'Bulan ' . $namaBulan[(int)$this->bulan] . ' ';
        } elseif ($this->triwulan) {
            $bulanText = 'Triwulan ' . $this->triwulan . ' ';
        }

        $judul = "LAPORAN PERKARA DIPUTUS " . $bulanText . "TAHUN " . $this->tahun;

        return [
            [$judul],
            [],
            [
                'No',
                'Pengadilan Agama',
                'SISA TAHUN LALU',
                'DITERIMA',
                'BEBAN',
                // Jenis Perkara
                ...array_values($this->jenisPerkara),
                'TOTAL DIPUTUS',
                'DICABUT',
                'DITOLAK',
                'DIKABULKAN',
                'TIDAK DITERIMA',
                'GUGUR',
                'DICORET',
                'PERSENTASE (%)',
                'SISA AKHIR'
            ]
        ];
    }

    public function map($row): array
    {
        // Jika ini baris TOTAL
        if ($row->satker == 'JUMLAH KESELURUHAN' || $row->satker == 'TOTAL') {
            $data = [
                '',
                'TOTAL KESELURUHAN',
                $row->sisa_tahun_lalu,
                $row->diterima,
                $row->beban,
            ];

            // Tambahkan data jenis perkara
            foreach (array_keys($this->jenisPerkara) as $key) {
                $data[] = $row->$key ?? 0;
            }

            // Tambahkan data status putusan
            $data[] = $row->jml;
            $data[] = $row->dicabut;
            $data[] = $row->ditolak;
            $data[] = $row->dikabulkan;
            $data[] = $row->tidak_diterima;
            $data[] = $row->gugur;
            $data[] = $row->dicoret;
            $data[] = $row->persentase;
            $data[] = $row->sisa;

            return $data;
        }

        // Untuk baris biasa
        $data = [
            $row->no_urut,
            $row->satker,
            $row->sisa_tahun_lalu,
            $row->diterima,
            $row->beban,
        ];

        // Tambahkan data jenis perkara
        foreach (array_keys($this->jenisPerkara) as $key) {
            $data[] = $row->$key ?? 0;
        }

        // Tambahkan data status putusan
        $data[] = $row->jml;
        $data[] = $row->dicabut;
        $data[] = $row->ditolak;
        $data[] = $row->dikabulkan;
        $data[] = $row->tidak_diterima;
        $data[] = $row->gugur;
        $data[] = $row->dicoret;
        $data[] = $row->persentase;
        $data[] = $row->sisa;

        return $data;
    }

    public function title(): string
    {
        return 'Laporan Perkara Diputus';
    }

    public function styles(Worksheet $sheet)
    {
        // Hitung jumlah kolom
        $columnCount = 5 + count($this->jenisPerkara) + 7 + 2; // No+Satker+Sisa+Terima+Beban(5) + Jenis(n) + Total+Status(7) + Persen+Sisa(2)
        $lastColumn = $this->getColumnLetter($columnCount);
        $lastRow = $sheet->getHighestRow();

        // Merge sel untuk judul
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        // Style judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Style header (baris 3)
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFont()->setBold(true);
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF2C5364');
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A3:' . $lastColumn . '3')->getAlignment()->setHorizontal('center');

        // Style untuk baris TOTAL (jika ada)
        for ($i = 4; $i <= $lastRow; $i++) {
            $cellValue = $sheet->getCell('B' . $i)->getValue();
            if ($cellValue == 'TOTAL KESELURUHAN') {
                $sheet->getStyle('A' . $i . ':' . $lastColumn . $i)->getFont()->setBold(true);
                $sheet->getStyle('A' . $i . ':' . $lastColumn . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFC107'); // Warna kuning
                break;
            }
        }

        // Border untuk seluruh data
        $sheet->getStyle('A3:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Format angka (rata kanan untuk kolom angka)
        for ($col = 3; $col <= $columnCount; $col++) {
            $columnLetter = $this->getColumnLetter($col);
            $sheet->getStyle($columnLetter . '4:' . $columnLetter . $lastRow)
                ->getAlignment()->setHorizontal('right');
        }

        // Kolom Persentase (satu sebelum terakhir) - format persen
        $persenCol = $this->getColumnLetter($columnCount - 1);
        $sheet->getStyle($persenCol . '4:' . $persenCol . $lastRow)
            ->getNumberFormat()->setFormatCode('0.00"%"');

        return [];
    }

    private function getColumnLetter($index): string
    {
        $letters = '';
        while ($index > 0) {
            $index--;
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = floor($index / 26);
        }
        return $letters;
    }
}
