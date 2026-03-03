<?php

namespace App\Exports;

use App\Models\SuratKeputusan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SkExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = SuratKeputusan::query();

        // Samakan filter dengan di Controller agar data konsisten
        if ($this->request->search) {
            $query->where('no_sk', 'like', '%' . $this->request->search . '%')
                ->orWhere('tentang', 'like', '%' . $this->request->search . '%');
        }

        $startDate = $this->request->from_date ?? date('Y-01-01');
        $endDate = $this->request->to_date ?? date('Y-m-d');
        $query->whereBetween('tgl_sk', [$startDate, $endDate]);

        return $query->latest('tgl_sk');
    }

    public function headings(): array
    {
        return ["NO", "NOMOR SK", "TANGGAL SK", "TAHUN", "TENTANG / PERIHAL"];
    }

    public function map($sk): array
    {
        static $no = 0;
        return [
            ++$no,
            $sk->no_sk,
            \Carbon\Carbon::parse($sk->tgl_sk)->format('d/m/Y'),
            $sk->tahun,
            $sk->tentang,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}
