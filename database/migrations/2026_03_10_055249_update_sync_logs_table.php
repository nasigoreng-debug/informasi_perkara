<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            // Tambahkan kolom identitas jika belum ada
            if (!Schema::hasColumn('sync_logs', 'modul')) {
                $table->string('modul')->default('umum')->after('id');
            }
            if (!Schema::hasColumn('sync_logs', 'koneksi_satker')) {
                $table->string('koneksi_satker')->nullable()->after('modul');
            }
            if (!Schema::hasColumn('sync_logs', 'nama_satker')) {
                $table->string('nama_satker')->nullable()->after('koneksi_satker');
            }

            // Buat kolom lama jadi nullable agar tidak error saat modul baru jalan
            $table->string('jenis')->nullable()->change();
            $table->integer('jumlah_data')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->dropColumn(['modul', 'koneksi_satker', 'nama_satker']);
        });
    }
};
