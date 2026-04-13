<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByUpdatedByToTbSuratMasukTable extends Migration
{
    public function up()
    {
        Schema::connection('db_pm_hukum')->table('tb_surat_masuk', function (Blueprint $table) {
            // Tambahkan kolom created_by dan updated_by setelah lampiran
            $table->unsignedBigInteger('created_by')->nullable()->after('lampiran');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // Tambahkan foreign key (opsional, sesuaikan dengan database users)
            // Jika users berada di database yang sama:
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::connection('db_pm_hukum')->table('tb_surat_masuk', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
}
