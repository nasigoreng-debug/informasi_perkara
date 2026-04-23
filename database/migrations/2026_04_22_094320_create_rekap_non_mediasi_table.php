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
        Schema::dropIfExists('rekap_non_mediasi');
        Schema::create('rekap_non_mediasi', function (Blueprint $table) {
            $table->id();
            $table->string('satker')->index();
            $table->string('nomor_perkara');
            $table->string('jenis_perkara_nama');
            $table->date('tanggal_pendaftaran');
            $table->boolean('is_mediasi')->default(0); // 1: Sudah Mediasi, 0: Belum
            $table->string('klasifikasi'); // GUGATAN atau PERMOHONAN
            $table->text('pihak1_text')->nullable();
            $table->text('pihak2_text')->nullable();
            $table->string('proses_terakhir_text')->nullable();
            $table->date('tgl_awal_filter');
            $table->date('tgl_akhir_filter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_non_mediasi');
    }
};
