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
        Schema::table('monitoring_bht_no_akta', function (Blueprint $table) {
            // Tambahkan kolom tgl_ikrar setelah tanggal_bht
            $table->date('tgl_ikrar')->nullable()->after('tanggal_bht');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_bht', function (Blueprint $table) {
            //
        });
    }
};
