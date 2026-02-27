<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks agar bisa truncate
        \Schema::disableForeignKeyConstraints();
        \DB::table('satker')->truncate();
        \Schema::enableForeignKeyConstraints();

        $satkerData = [
            [400662, 'PENGADILAN AGAMA BANDUNG', 'PA Bandung', 'bandung', 'PA.Badg', 'bandung.png', 1],
            [400766, 'PENGADILAN AGAMA INDRAMAYU', 'PA Indramayu', 'indramayu', 'PA.Im', 'indramayu.png', 2],
            [400772, 'PENGADILAN AGAMA MAJALENGKA', 'PA Majalengka', 'majalengka', 'PA.Mjl', 'majalengka.png', 3],
            [403009, 'PENGADILAN AGAMA SUMBER', 'PA Sumber', 'sumber', 'PA.Sbr', 'sumber.png', 4],
            [400690, 'PENGADILAN AGAMA CIAMIS', 'PA Ciamis', 'ciamis', 'PA.Cms', 'ciamis.png', 5],
            [400704, 'PENGADILAN AGAMA TASIKMALAYA', 'PA Tasikmalaya', 'tasikmalaya', 'PA.Tsm', 'tasikmalaya.png', 6],
            [400848, 'PENGADILAN AGAMA KARAWANG', 'PA Karawang', 'karawang', 'PA.Krw', 'karawang.png', 7],
            [400684, 'PENGADILAN AGAMA CIMAHI', 'PA Cimahi', 'cimahi', 'PA.Cmi', 'cimahi.png', 9],
            [402587, 'PENGADILAN AGAMA SUBANG', 'PA Subang', 'subang', 'PA.Sbg', 'subang.png', 8],
            [400678, 'PENGADILAN AGAMA SUMEDANG', 'PA Sumedang', 'sumedang', 'PA.Smdg', 'sumedang.png', 10],
            [400854, 'PENGADILAN AGAMA PURWAKARTA', 'PA Purwakarta', 'purwakarta', 'PA.Pwk', 'purwakarta.png', 11],
            [400735, 'PENGADILAN AGAMA SUKABUMI', 'PA Sukabumi', 'sukabumi', 'PA.Smi', 'sukabumi.png', 12],
            [400741, 'PENGADILAN AGAMA CIANJUR', 'PA Cianjur', 'cianjur', 'PA.Cjr', 'cianjur.png', 13],
            [400781, 'PENGADILAN AGAMA KUNINGAN', 'PA Kuningan', 'kuningan', 'PA.Kng', 'kuningan.png', 14],
            [402995, 'PENGADILAN AGAMA CIBADAK', 'PA Cibadak', 'cibadak', 'PA.Cbd', 'cibadak.png', 15],
            [400750, 'PENGADILAN AGAMA CIREBON', 'PA Cirebon', 'cirebon', 'PA.Crb', 'cirebon.png', 16],
            [400710, 'PENGADILAN AGAMA GARUT', 'PA Garut', 'garut', 'PA.Grt', 'garut.png', 17],
            [400729, 'PENGADILAN AGAMA BOGOR', 'PA Bogor', 'bogor', 'PA.Bgr', 'bogor.png', 18],
            [400832, 'PENGADILAN AGAMA BEKASI', 'PA Bekasi', 'bekasi', 'PA.Bks', 'bekasi.png', 19],
            [604719, 'PENGADILAN AGAMA CIBINONG', 'PA Cibinong', 'cibinong', 'PA.Cbn', 'cibinong.png', 20],
            [614706, 'PENGADILAN AGAMA CIKARANG', 'PA Cikarang', 'cikarang', 'PA.Ckr', 'cikarang.png', 21],
            [652062, 'PENGADILAN AGAMA DEPOK', 'PA Depok', 'depok', 'PA.Dpk', 'depok.png', 22],
            [682150, 'PENGADILAN AGAMA KOTA TASIKMALAYA', 'PA Kota Tasikmalaya', 'tasikkota', 'PA.Tmk', 'kotatasik.png', 23],
            [682164, 'PENGADILAN AGAMA KOTA BANJAR', 'PA Kota Banjar', 'banjar', 'PA.Bjr', 'banjar.png', 24],
            [401959, 'PENGADILAN AGAMA NGAMPRAH', 'PA Ngamprah', 'ngamprah', 'PA-Nph', 'ngamprah.png', 25],
            [401957, 'PENGADILAN AGAMA SOREANG', 'PA Soreang', 'soreang', 'PA.Srg', 'soreang.png', 26],
        ];

        foreach ($satkerData as $row) {
            \DB::table('satker')->insert([
                'id'           => $row[0],
                'nama'         => $row[1],
                'nama_singkat' => $row[2],
                'tabel'        => $row[3],
                'kode'         => $row[4],
                'logo_pa'      => $row[5],
                'urutan'       => $row[6],
                'namapa'       => 'PTA Bandung', // Tetap terikat ke PTA Bandung
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
