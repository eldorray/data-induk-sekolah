<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_ijazah_tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun_ajaran', 20)->unique(); // contoh: 2025/2026
            $table->boolean('status')->default(true); // true = aktif
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_ijazah_tahun_ajarans');
    }
};