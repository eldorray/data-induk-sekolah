<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pernyataan_tangcers', function (Blueprint $table) {
            $table->id();

            // Relasi ke siswa
            $table->unsignedBigInteger('siswa_id');
            $table->string('siswa_type')->default('siswa_mi'); // siswa_mi atau siswa_smp

            // Data surat
            $table->string('nomor_surat');
            $table->string('tahun_anggaran')->default('2025');
            $table->string('semester')->default('Genap');
            $table->text('isi_surat')->nullable();
            $table->text('isi_tujuan')->nullable();
            $table->date('tanggal_surat');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pernyataan_tangcers');
    }
};
