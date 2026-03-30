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
        Schema::table('rekap_sisa_panjar', function (Blueprint $table) {
            $table->string('jenis_perkara_nama')->nullable()->after('nomor_perkara');
            $table->string('proses_terakhir_teks')->nullable()->after('jenis_perkara_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_sisa_panjar', function (Blueprint $table) {
            //
        });
    }
};
