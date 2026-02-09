<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SK Pembagian Tugas Mengajar (Header)
        Schema::create('sk_pembagian_tugas_mis', function (Blueprint $table) {
            $table->id();

            // Nomor SK
            $table->string('nomor_sk', 100);
            $table->date('tanggal_sk');

            // Periode
            $table->string('tahun_pelajaran', 20); // e.g. 2025/2026
            $table->enum('semester', ['1', '2'])->default('1');

            // Penandatangan
            $table->string('penandatangan_nama');
            $table->string('penandatangan_nip')->nullable();
            $table->string('penandatangan_jabatan')->default('Kepala Madrasah');
            $table->string('tempat_penetapan')->default('Tangerang');
            $table->date('tanggal_penetapan');

            // Status
            $table->enum('status', ['draft', 'aktif', 'tidak_aktif'])->default('draft');

            $table->timestamps();

            // Index
            $table->index('nomor_sk');
            $table->index(['tahun_pelajaran', 'semester']);
            $table->index('status');
        });

        // Detail Pembagian Tugas per Guru
        Schema::create('pembagian_tugas_detail_mis', function (Blueprint $table) {
            $table->id();

            // Referensi SK
            $table->foreignId('sk_pembagian_tugas_mi_id')->constrained('sk_pembagian_tugas_mis')->cascadeOnDelete();

            // Referensi Guru
            $table->foreignId('guru_mi_id')->constrained('guru_mis')->cascadeOnDelete();

            // Detail Tugas
            $table->string('jabatan', 100); // e.g. Guru, Kepala Madrasah, TU, Operator/Guru
            $table->string('jenis_guru', 100); // e.g. Guru Kelas, Guru Bidang, Kamad, TU
            $table->string('tugas_mengajar', 255); // e.g. Kelas IA, Guru Tahfidz, Guru B. Inggris, Fiqih
            $table->integer('jumlah_jam')->nullable(); // Jumlah jam per minggu

            // Urutan tampil
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Index
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembagian_tugas_detail_mis');
        Schema::dropIfExists('sk_pembagian_tugas_mis');
    }
};
