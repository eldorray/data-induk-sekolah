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
        Schema::create('surat_keterangan_aktifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('keperluan')->nullable(); // untuk keperluan apa surat ini dibuat
            $table->string('tahun_pelajaran')->nullable();
            $table->enum('semester', ['ganjil', 'genap'])->nullable();
            $table->enum('status', ['draft', 'disetujui', 'dibatalkan'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keterangan_aktifs');
    }
};
