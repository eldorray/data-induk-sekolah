<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_terima_pindahans', function (Blueprint $table) {
            $table->id();

            // Data siswa pindahan (diinput langsung, belum terhubung ke siswa_mi/siswa_smp)
            $table->string('nama_siswa');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kelas', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_orang_tua')->nullable();
            $table->text('alamat_rumah')->nullable();

            // Data surat
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->enum('status', ['draft', 'disetujui', 'dibatalkan'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_terima_pindahans');
    }
};
