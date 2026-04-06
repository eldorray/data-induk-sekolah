<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_rekap_pkhs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->string('siswa_type')->default('siswa_mi'); // siswa_mi atau siswa_smp
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('tahun_ajaran', 20)->nullable(); // e.g. 2025/2026
            $table->string('semester', 10)->default('genap'); // ganjil/genap
            $table->json('bulan_rekap'); // ["Januari","Februari","Maret"]
            $table->json('data_absensi'); // {"Januari":{"sakit":2,"izin":0,"alfa":0},...}
            $table->string('nama_wali_kelas')->nullable();
            $table->string('nip_wali_kelas')->nullable();
            $table->string('status', 20)->default('draft'); // draft/disetujui/dibatalkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_rekap_pkhs');
    }
};
