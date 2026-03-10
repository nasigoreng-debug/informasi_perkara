<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('local_perkara_diterima', function (Blueprint $table) {
            $table->id();
            $table->string('koneksi_satker');
            $table->string('nama_satker');
            $table->string('nomor_perkara'); // Unique identifier
            $table->string('jenis_perkara_nama');
            $table->date('tanggal_pendaftaran');
            $table->timestamps();

            // Index untuk kecepatan filter range date
            $table->index(['tanggal_pendaftaran', 'koneksi_satker']);
            $table->unique(['nomor_perkara', 'koneksi_satker'], 'idx_perkara_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('local_perkara_diterima');
    }
};
