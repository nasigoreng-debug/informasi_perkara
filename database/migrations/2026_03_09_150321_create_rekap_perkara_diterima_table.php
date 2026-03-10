<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rekap_perkara_diterima', function (Blueprint $table) {
            $table->id();
            $table->string('koneksi_satker')->index();
            $table->string('nama_satker');
            $table->integer('tahun');
            $table->integer('bulan')->nullable();
            $table->integer('total_perkara')->default(0);

            // Buat kolom untuk tiap jenis perkara secara dinamis
            $jenisPerkara = [
                'izin_poligami',
                'pencegahan_perkawinan',
                'penolakan_perkawinan',
                'pembatalan_perkawinan',
                'kelalaian_kewajiban',
                'cerai_talak',
                'cerai_gugat',
                'harta_bersama',
                'penguasaan_anak',
                'nafkah_anak',
                'hak_bekas_istri',
                'asal_usul_anak',
                'pencabutan_kuasa_ortu',
                'perwalian',
                'pencabutan_wali',
                'penunjukan_wali',
                'ganti_rugi_wali',
                'penolakan_ppn',
                'istbat_nikah',
                'izin_kawin',
                'dispensasi_kawin',
                'wali_adhol',
                'kewarisan',
                'wasiat',
                'hibah',
                'wakaf',
                'lain_lain',
                'ekonomi_syariah',
                'p3hp',
                'pengampuan',
                'perkawinan_campuran'
            ];

            foreach ($jenisPerkara as $jenis) {
                $table->integer($jenis)->default(0);
            }

            $table->timestamps();
            $table->unique(['koneksi_satker', 'tahun', 'bulan'], 'idx_sync_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_perkara_diterima');
    }
};
