<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuratKeluarExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = SuratKeluar::query();

        // Filter agar hasil Excel sama dengan yang tampil di layar
        if ($this->request->filled('search')) {
            $query->where('no_surat', 'like', '%' . $this->request->search . '%')
                ->orWhere('tujuan_surat', 'like', '%' . $this->request->search . '%')
                ->orWhere('perihal', 'like', '%' . $this->request->search . '%');
        }

        if ($this->request->filled('from_date') && $this->request->filled('to_date')) {
            $query->whereBetween('tgl_surat', [$this->request->from_date, $this->request->to_date]);
        }

        return $query->latest('tgl_surat');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Surat',
            'Tanggal Surat',
            'Tujuan Surat',
            'Perihal',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        return [
            ++$no,
            $item->no_surat,
            \Carbon\Carbon::parse($item->tgl_surat)->format('d/m/Y'),
            $item->tujuan_surat,
            $item->perihal,
            $item->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D1D5DB']
                ]
            ],
        ];
    }
}
