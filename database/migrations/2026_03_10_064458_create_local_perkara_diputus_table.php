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
        Schema::create('local_perkara_diputus', function (Blueprint $table) {
            $table->id();
            $table->string('koneksi_satker');
            $table->string('nama_satker');
            $table->string('nomor_perkara');
            $table->string('jenis_perkara_nama');
            $table->date('tanggal_pendaftaran');
            $table->date('tanggal_putusan'); // Filter utama RK4
            $table->string('jenis_putusan')->nullable();
            $table->timestamps();

            // Agar pencarian jutaan data tetap kencang
            $table->index(['tanggal_putusan', 'koneksi_satker']);
            // Agar tidak ada data ganda (Nomor Perkara + Satker harus unik)
            $table->unique(['nomor_perkara', 'koneksi_satker'], 'idx_putusan_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_perkara_diputus');
    }
};
