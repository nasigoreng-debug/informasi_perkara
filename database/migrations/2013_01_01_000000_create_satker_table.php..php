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
        Schema::create('satker', function (Blueprint $table) {
            $table->integer('id')->primary(); // int(11) 
            $table->string('nama', 255)->nullable(); // varchar(255) 
            $table->string('nama_singkat', 30)->nullable(); // varchar(30) 
            $table->string('tabel', 30)->nullable(); // varchar(30) 
            $table->string('kode', 10)->nullable(); // varchar(10) 
            $table->string('logo_pa', 255)->nullable(); // varchar(255) 
            $table->tinyInteger('urutan')->nullable(); // tinyint(4) 
            $table->string('namapa', 30)->nullable(); // varchar(30) 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satker');
    }
};
