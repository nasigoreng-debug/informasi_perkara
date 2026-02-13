<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanPerkaraExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $where = "WHERE YEAR(p.tanggal_pendaftaran) = $y";
        if ($m) $where .= " AND MONTH(p.tanggal_pendaftaran) = $m";
        elseif ($q) {
            $r = [1 => [1,3], 2 => [4,6], 3 => [7,9], 4 => [10,12]];
            $where .= " AND MONTH(p.tanggal_pendaftaran) BETWEEN ".$r[$q][0]." AND ".$r[$q][1];
        }

        $dbs = ['bandung', 'indramayu', 'majalengka', 'sumber', 'ciamis', 'tasikmalaya', 'karawang', 'cimahi', 'subang', 'sumedang', 'purwakarta', 'sukabumi', 'cianjur', 'kuningan', 'cibadak', 'cirebon', 'garut', 'bogor', 'bekasi', 'cibinong', 'cikarang', 'depok', 'tasikkota', 'banjar', 'soreang', 'ngamprah'];

        $sub = [];
        foreach ($dbs as $i => $db) {
            $no = $i + 1; $name = strtoupper($db); $cols = [];
            foreach ($this->jenisPerkara as $a => $n) $cols[] = "COUNT(CASE WHEN p.jenis_perkara_nama = '$n' THEN 1 END) AS $a";
            $sub[] = "SELECT '$no' AS no_urut, '$name' AS satker, ".implode(", ", $cols).", COUNT(*) AS jml FROM $db.perkara p $where";
        }

        $union = implode(" UNION ALL ", $sub);
        $sums = []; foreach ($this->jenisPerkara as $a => $n) $sums[] = "SUM($a)";
        $totalS = implode(", ", $sums);

        $sql = "SELECT * FROM ($union) AS g UNION ALL SELECT 'TOTAL', 'JUMLAH KESELURUHAN', $totalS, SUM(jml) FROM ($union) AS t ORDER BY CASE WHEN no_urut = 'TOTAL' THEN 999 ELSE CAST(no_urut AS UNSIGNED) END";

        return ['laporan' => DB::select($sql), 'year' => $y, 'month' => $m, 'quarter' => $q];
    }
}