<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuratMasukExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function query()
    {
        // Menggunakan query dasar seperti di Controller Anda
        $query = SuratMasuk::query();

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('tgl_surat', [$this->fromDate, $this->toDate]);
        }

        return $query->orderBy('no_indeks', 'asc');
    }

    public function headings(): array
    {
        return [
            'NO. INDEKS',
            'NO. SURAT',
            'TANGGAL SURAT',
            'ASAL SURAT',
            'PERIHAL',
            'DISPOSISI',
            'KETERANGAN'
        ];
    }

    public function map($surat): array
    {
        return [
            $surat->no_indeks,
            $surat->no_surat,
            $surat->tgl_surat,
            $surat->asal_surat,
            $surat->perihal,
            $surat->disposisi,
            $surat->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Menebalkan Header
        ];
    }
}
