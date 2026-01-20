<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->date('tanggal_mutasi');
            $table->enum('jenis_mutasi', ['pindah', 'keluar'])->default('pindah');
            $table->text('alasan_mutasi');
            $table->string('sekolah_tujuan')->nullable();
            $table->string('npsn_tujuan')->nullable();
            $table->text('alamat_tujuan')->nullable();
            $table->enum('status', ['draft', 'disetujui', 'dibatalkan'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_siswas');
    }
};
