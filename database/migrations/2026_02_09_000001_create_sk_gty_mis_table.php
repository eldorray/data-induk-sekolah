<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sk_gty_mis', function (Blueprint $table) {
            $table->id();

            // Referensi Guru
            $table->foreignId('guru_mi_id')->constrained('guru_mis')->cascadeOnDelete();

            // Nomor SK
            $table->string('nomor_sk', 100);
            $table->date('tanggal_sk');

            // Data Pengangkatan
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('jabatan', 100)->nullable();

            // Masa Berlaku
            $table->date('berlaku_mulai');
            $table->date('berlaku_sampai');

            // Penandatangan
            $table->string('penandatangan_nama');
            $table->string('penandatangan_jabatan')->default('Ketua Yayasan');
            $table->string('tempat_penetapan')->default('Tangerang');
            $table->date('tanggal_penetapan');

            // Status
            $table->enum('status', ['draft', 'aktif', 'tidak_aktif'])->default('draft');

            $table->timestamps();

            // Index
            $table->index('nomor_sk');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sk_gty_mis');
    }
};
