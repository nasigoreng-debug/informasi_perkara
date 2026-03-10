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
        Schema::create('rekap_sisa_panjar', function (Blueprint $table) {
            $table->id();
            $table->string('satker_key');
            $table->string('nomor_perkara');
            $table->string('nomor_perkara_atas')->nullable();
            $table->date('tgl_putusan')->nullable();
            $table->date('tgl_notif')->nullable();
            $table->decimal('selisih_bulan', 8, 2);
            $table->decimal('sisa', 20, 2);
            $table->string('jenis'); // pertama, banding, kasasi, pk
            $table->timestamps();

            // Index agar pencarian kilat
            $table->index(['jenis', 'selisih_bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_sisa_panjar');
    }
};
