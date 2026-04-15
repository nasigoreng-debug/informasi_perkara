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
        Schema::create('rekap_saldo_minus', function (Blueprint $table) {
            $table->id();
            $table->string('satker');
            $table->string('nomor_perkara');
            $table->string('jenis_perkara_nama');
            $table->date('tanggal_pendaftaran');
            $table->decimal('total_penerimaan', 20, 2);
            $table->decimal('total_pengeluaran', 20, 2);
            $table->decimal('sisa_akhir', 20, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_saldo_minus');
    }
};
