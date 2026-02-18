<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanPerkaraExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Config\SatkerConfig;

class LaporanPerkaraController extends Controller
{
    private $jenisPerkara = [
        'iz' => 'Izin Poligami', 'pp' => 'Pencegahan Perkawinan', 'p_ppn' => 'Penolakan Perkawinan oleh PPN', 'pb' => 'Pembatalan Perkawinan', 'lks' => 'Kelalaian Kewajiban Suami/Isteri', 'ct' => 'Cerai Talak', 'cg' => 'Cerai Gugat', 'hb' => 'Harta Bersama', 'pa' => 'Penguasaan Anak', 'nai' => 'Nafkah Anak oleh Ibu', 'hbi' => 'Hak-hak Bekas Isteri', 'psa' => 'Pengesahan Anak', 'pkot' => 'Pencabutan Kekuasaan Orang Tua', 'pw' => 'Perwalian', 'phw' => 'Pencabutan Kekuasaan Wali', 'pol' => 'Penunjukan orang lain sebagai Wali oleh Pengadilan', 'grw' => 'Ganti Rugi terhadap Wali', 'aua' => 'Asal Usul Anak', 'pkc' => 'Penolakan Kawin Campuran', 'isbath' => 'Pengesahan Perkawinan/Istbat Nikah', 'ik' => 'Izin Kawin', 'dk' => 'Dispensasi Kawin', 'wa' => 'Wali Adhol', 'kw' => 'Kewarisan', 'wst' => 'Wasiat', 'hb_h' => 'Hibah', 'wkf' => 'Wakaf', 'zkt' => 'Zakat', 'infq' => 'Infaq', 'es' => 'Ekonomi Syariah', 'p3hp' => 'P3HP/Penetapan Ahli Waris', 'll' => 'Lain-Lain'
    ];

    public function index(Request $request) {
        $res = $this->fetch($request);
        return view('laporan.perkara', array_merge($res, ['jenisPerkara' => $this->jenisPerkara]));
    }

    public function export(Request $request) {
        $res = $this->fetch($request);
        return Excel::download(new LaporanPerkaraExport($res['laporan'], $this->jenisPerkara), 'Laporan_Perkara.xlsx');
    }

    private function fetch($request) {
        $y = $request->get('tahun', date('Y'));
        $m = $request->get('bulan');
        $q = $request->get('triwulan');

        $laporan = [];
        // Inisialisasi total untuk baris paling bawah
        $totals = array_fill_keys(array_keys($this->jenisPerkara), 0);
        $grandTotalJml = 0;

        // Loop melalui 26 Satker dari Config
        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                // Bina Query menggunakan Query Builder (Lebih selamat daripada Raw SQL)
                $query = DB::connection($koneksi)->table('perkara as p')
                    ->whereYear('p.tanggal_pendaftaran', $y);

                // Filter Bulan / Triwulan
                if ($m) {
                    $query->whereMonth('p.tanggal_pendaftaran', $m);
                } elseif ($q) {
                    $range = [1 => [1,3], 2 => [4,6], 3 => [7,9], 4 => [10,12]];
                    $query->whereBetween(DB::raw('MONTH(p.tanggal_pendaftaran)'), [$range[$q][0], $range[$q][1]]);
                }

                // Buat SELECT secara dinamik untuk setiap jenis perkara
                $selects = ["COUNT(*) as jml"];
                foreach ($this->jenisPerkara as $key => $nama) {
                    $selects[] = "COUNT(CASE WHEN p.jenis_perkara_nama = '$nama' THEN 1 END) AS $key";
                }

                $data = $query->selectRaw(implode(", ", $selects))->first();

                // Masukkan hasil ke dalam row
                $row = [
                    'no_urut' => SatkerConfig::getNomorUrut($koneksi),
                    'satker' => strtoupper($namaSatker),
                    'jml' => $data->jml ?? 0
                ];

                foreach ($this->jenisPerkara as $key => $nama) {
                    $val = $data->$key ?? 0;
                    $row[$key] = $val;
                    $totals[$key] += $val; // Tambah ke jumlah keseluruhan
                }

                $laporan[] = (object) $row;
                $grandTotalJml += ($data->jml ?? 0);

            } catch (\Exception $e) {
                // Jika satu satker ralat/offline, kita tetap paparkan barisnya dengan nilai 0
                $row = ['no_urut' => SatkerConfig::getNomorUrut($koneksi), 'satker' => strtoupper($namaSatker), 'jml' => 0];
                foreach ($this->jenisPerkara as $key => $n) $row[$key] = 0;
                $laporan[] = (object) $row;
                continue;
            }
        }

        // Susun semula mengikut No Urut
        usort($laporan, fn($a, $b) => $a->no_urut <=> $b->no_urut);

        // Tambah baris JUMLAH KESELURUHAN di paling bawah
        $footer = [
            'no_urut' => 'TOTAL',
            'satker' => 'JUMLAH KESELURUHAN',
            'jml' => $grandTotalJml
        ];
        foreach ($totals as $key => $val) $footer[$key] = $val;
        $laporan[] = (object) $footer;

        return [
            'laporan' => $laporan, 
            'year' => $y, 
            'month' => $m, 
            'quarter' => $q
        ];
    }
}