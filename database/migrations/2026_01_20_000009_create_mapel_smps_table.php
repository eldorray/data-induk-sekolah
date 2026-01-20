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
        Schema::create('mapel_smps', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->unique()->nullable();
            $table->string('nama_mapel', 255);
            $table->integer('jam_per_minggu')->nullable()->default(2);
            $table->enum('kelompok', ['PAI', 'Umum'])->default('Umum');
            $table->string('jurusan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel_smps');
    }
};
