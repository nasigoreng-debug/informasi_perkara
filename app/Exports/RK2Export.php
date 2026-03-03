<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RK2Export implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $results;
    protected $tgl_awal;
    protected $tgl_akhir;
    protected $jenisPerkara;

    public function __construct($results, $tgl_awal, $tgl_akhir)
    {
        $this->results = $results;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->jenisPerkara = [
            'iz' => 'Izin Poligami',
            'pp' => 'Pencegahan Perkawinan',
            'p_ppn' => 'Penolakan PPN',
            'pb' => 'Pembatalan Perkawinan',
            'lks' => 'Kelalaian Kewajiban',
            'ct' => 'Cerai Talak',
            'cg' => 'Cerai Gugat',
            'hb' => 'Harta Bersama',
            'pa' => 'Penguasaan Anak',
            'nai' => 'Nafkah Anak',
            'hbi' => 'Hak Bekas Isteri',
            'psa' => 'Pengesahan Anak',
            'pkot' => 'Cabut Kuasa Ortu',
            'pw' => 'Perwalian',
            'phw' => 'Cabut Kuasa Wali',
            'pol' => 'Penunjukan Wali',
            'grw' => 'Ganti Rugi Wali',
            'aua' => 'Asal Usul Anak',
            'pkc' => 'Tolak Kawin Campur',
            'isbath' => 'Isbath Nikah',
            'ik' => 'Izin Kawin',
            'dk' => 'Dispensasi Kawin',
            'wa' => 'Wali Adhol',
            'es' => 'Ekonomi Syari',
            'kw' => 'Kewarisan',
            'wst' => 'Wasiat',
            'hb_h' => 'Hibah',
            'wkf' => 'Wakaf',
            'zkt_infq' => 'Zakat/Infaq',
            'p3hp' => 'P3HP/Ahli Waris',
            'll' => 'Lain-lain'
        ];
    }

    public function collection()
    {
        $data = [];
        $no = 1;

        foreach ($this->results as $row) {
            $isTotal = ($row->satker == 'JUMLAH KESELURUHAN');

            // Hitung total rincian dikabulkan menyamping
            $totalDikabulkan = 0;
            foreach (array_keys($this->jenisPerkara) as $k) {
                $totalDikabulkan += $row->$k ?? 0;
            }

            // Hitung Jumlah Putus (Sinkron dengan Web)
            $jmlPutus = ($row->dicabut ?? 0) + $totalDikabulkan + ($row->ditolak ?? 0) + ($row->tidak_diterima ?? 0) + ($row->gugur ?? 0) + ($row->dicoret ?? 0);
            $sisaAkhir = ($row->beban ?? 0) - $jmlPutus;

            $item = [
                'no' => $isTotal ? '' : $no++,
                'satker' => $row->satker,
                'sisa_lalu' => $row->sisa_lalu ?? 0,
                'diterima' => $row->diterima ?? 0,
                'beban' => $row->beban ?? 0,
                'dicabut' => $row->dicabut ?? 0,
            ];

            // Isi Rincian Jenis Perkara
            foreach (array_keys($this->jenisPerkara) as $k) {
                $item[$k] = $row->$k ?? 0;
            }

            $item['total_kabul'] = $totalDikabulkan;
            $item['ditolak'] = $row->ditolak ?? 0;
            $item['tidak_diterima'] = $row->tidak_diterima ?? 0;
            $item['gugur'] = $row->gugur ?? 0;
            $item['dicoret'] = $row->dicoret ?? 0;
            $item['jml_putus'] = $jmlPutus;
            $item['sisa_akhir'] = $sisaAkhir;

            $data[] = $item;
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PERKARA BANDING DIPUTUS (RK.2)'],
            ['PERIODE: ' . $this->tgl_awal . ' S/D ' . $this->tgl_akhir],
            [''],
            array_merge(
                ['NO', 'PENGADILAN AGAMA', 'SISA LALU', 'DITERIMA', 'JUMLAH (BEBAN)', 'DICABUT'],
                array_values($this->jenisPerkara),
                ['TOTAL DIKABULKAN', 'DITOLAK', 'TAK DITERIMA', 'GUGUR', 'DICORET', 'JUMLAH PUTUS', 'SISA AKHIR']
            )
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // Title Center
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Header Styling
        $sheet->getStyle("A4:{$lastCol}4")->getFont()->setBold(true);
        $sheet->getStyle("A4:{$lastCol}4")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E2E2');

        // Borders
        $sheet->getStyle("A4:{$lastCol}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Number Alignment
        $sheet->getStyle("C4:{$lastCol}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Total Row Bold
        $sheet->getStyle("A{$lastRow}:{$lastCol}{$lastRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$lastRow}:{$lastCol}{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F2F2F2');
    }
}
