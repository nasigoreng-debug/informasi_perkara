<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('monitoring_bht_no_akta', function (Blueprint $table) {
        $table->id();
        $table->string('satker');               
        $table->unsignedBigInteger('perkara_id'); 
        $table->string('nomor_perkara');         
        $table->string('jenis_perkara_nama');    
        $table->date('tanggal_putusan')->nullable(); // Kolom baru
        $table->date('tanggal_bht');             
        $table->date('tanggal_minutasi')->nullable(); // Kolom baru
        $table->date('tgl_akta_cerai')->nullable(); 
        $table->integer('selisih_hari')->default(0); 
        $table->timestamps();

        $table->index(['satker', 'tanggal_bht']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_bht_no_akta');
    }
};
