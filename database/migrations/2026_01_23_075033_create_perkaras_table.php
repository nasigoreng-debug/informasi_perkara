<?php
// database/migrations/2024_01_15_create_perkaras_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perkara', function (Blueprint $table) {
            $table->id('perkara_id');
            $table->string('nomor_perkara_banding')->unique();
            $table->string('nomor_perkara_pa')->nullable();
            $table->string('jenis_perkara');
            $table->string('nama_pembanding')->nullable();
            $table->string('nama_terbanding')->nullable();
            $table->string('nama_satker')->nullable();
            $table->boolean('tabayun')->default(false);
            $table->date('tgl_mohon_banding')->nullable();
            $table->date('tgl_kirim_berkas')->nullable();
            $table->date('berkas_kembali_kl')->nullable();
            $table->date('tgl_register');
            $table->date('tgl_penetapan_majelis')->nullable();
            $table->date('tgl_penunjukan_pp')->nullable();
            $table->date('tgl_phs')->nullable();
            $table->date('tgl_terima_berkas_pp')->nullable();
            $table->date('tgl_kirim_konsep')->nullable();
            $table->date('tgl_sidang_pertama')->nullable();
            $table->date('tgl_putusan_sela')->nullable();
            $table->date('tgl_putusan')->nullable();
            $table->date('tgl_minutasi')->nullable();
            $table->date('tgl_serah_panmud')->nullable();
            $table->date('tgl_kirim_pa')->nullable();
            $table->date('tgl_serah_meja3')->nullable();
            $table->date('tgl_upload')->nullable();
            $table->date('tgl_anonimasi')->nullable();
            $table->date('tgl_arsip')->nullable();
            $table->integer('jenis_putus_id')->nullable();
            $table->string('jenis_putus_text')->nullable();
            $table->text('amar_putusan')->nullable();
            $table->integer('km_id')->nullable()->index();
            $table->string('nama_km')->nullable();
            $table->integer('pp_id')->nullable()->index();
            $table->string('nama_pp')->nullable();
            $table->integer('konseptor_id')->nullable();
            $table->string('nama_konseptor')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            // Indexes
            $table->index('tgl_register');
            $table->index('tgl_putusan');
            $table->index('jenis_perkara');
            $table->index('jenis_putus_id');
            $table->index(['km_id', 'tgl_register']);
            $table->index(['pp_id', 'tgl_register']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkaras');
    }
};
