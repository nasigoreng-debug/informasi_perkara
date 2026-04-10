<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('local_perkara_tepat_waktu', function (Blueprint $table) {
            $table->id();
            $table->string('koneksi_satker')->unique();
            $table->string('nama_satker');
            $table->integer('jumlah_putus')->default(0);
            $table->integer('tepat_waktu')->default(0);
            $table->integer('terlambat')->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->integer('batas_hari');
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_perkara_tepat_waktu');
    }
};
