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

            // Hitung total rincian dikabulkan (hanya rincian jenis)
            $totalDikabulkan = 0;
            foreach (array_keys($this->jenisPerkara) as $k) {
                $totalDikabulkan += $row->$k ?? 0;
            }

            // RUMUS FIX: Hanya menghitung Status Akhir (Dicabut + Kuat + Batal + Perbaiki + NO + Tolak + Gugur + Coret)
            // Tidak menjumlahkan rincian jenis perkara agar tidak double/minus
            $jmlPutus = ($row->dicabut ?? 0) +
                ($row->Dikuatkan ?? 0) +
                ($row->Dibatalkan ?? 0) +
                ($row->Diperbaiki ?? 0) +
                ($row->tidak_diterima ?? 0) +
                ($row->ditolak ?? 0) +
                ($row->gugur ?? 0) +
                ($row->dicoret ?? 0);

            $sisaAkhir = ($row->beban ?? 0) - $jmlPutus;

            $item = [
                'no' => $isTotal ? '' : $no++,
                'satker' => $row->satker,
                'sisa_lalu' => $row->sisa_lalu ?? 0,
                'diterima' => $row->diterima ?? 0,
                'beban' => $row->beban ?? 0,
                'dicabut' => $row->dicabut ?? 0,
            ];

            // Isi Rincian Jenis Perkara (Kolom 7-37)
            foreach (array_keys($this->jenisPerkara) as $k) {
                $item[$k] = $row->$k ?? 0;
            }

            // Tambahkan kolom status baru (38-41) & status lainnya
            $item['Dikuatkan'] = $row->Dikuatkan ?? 0;
            $item['Dibatalkan'] = $row->Dibatalkan ?? 0;
            $item['Diperbaiki'] = $row->Diperbaiki ?? 0;
            $item['tidak_diterima'] = $row->tidak_diterima ?? 0;
            $item['ditolak'] = $row->ditolak ?? 0;
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
            ['PENGADILAN AGAMA SE-JAWA BARAT'],
            ['PERIODE: ' . $this->tgl_awal . ' S/D ' . $this->tgl_akhir],
            [''],
            array_merge(
                ['NO', 'PENGADILAN AGAMA', 'SISA LALU', 'DITERIMA', 'JUMLAH (BEBAN)', 'DICABUT'],
                array_values($this->jenisPerkara),
                ['DIKUATKAN', 'DIBATALKAN', 'DIPERBAIKI', 'TAK DITERIMA', 'DITOLAK', 'GUGUR', 'DICORET', 'JUMLAH PUTUS', 'SISA AKHIR']
            )
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // Judul & Periode
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Header Tabel (Baris 5)
        $sheet->getStyle("A5:{$lastCol}5")->getFont()->setBold(true)->getColor()->setARGB('FFFFFF');
        $sheet->getStyle("A5:{$lastCol}5")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A5:{$lastCol}5")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2C3E50');

        // Body Styling
        $sheet->getStyle("A5:{$lastCol}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C6:{$lastCol}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Styling Kolom Sisa Akhir (Kolom Terakhir)
        $sheet->getStyle("{$lastCol}6:{$lastCol}{$lastRow}")->getFont()->setBold(true);

        // Baris Total (Baris Terakhir)
        $sheet->getStyle("A{$lastRow}:{$lastCol}{$lastRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$lastRow}:{$lastCol}{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('BDC3C7');
    }
}
