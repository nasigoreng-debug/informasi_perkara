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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // Login pakai username
            $table->string('password');

            // Sesuaikan dengan id satker: integer (11)
            $table->integer('satker_id')->nullable();

            $table->enum('role', [
                'Super Admin',
                'Manager',
                'Staff',
                'User 26 PA',
                'Guest'
            ])->default('Guest');

            $table->rememberToken();
            $table->timestamps();

            // Foreign key sekarang aman karena 'satker' sudah dibuat duluan
            $table->foreign('satker_id')->references('id')->on('satker')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
