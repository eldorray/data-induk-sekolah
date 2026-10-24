<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_insentifs', function (Blueprint $table) {
            $table->id();
            $table->string('periode'); // JULI - SEPTEMBER
            $table->string('tahun', 9); // 2026
            $table->string('kota')->default('TANGERANG');
            $table->string('bulan_ttd'); // September
            $table->string('nama_kepala');
            $table->string('jabatan_kepala')->default('KEPALA MADRASAH');
            $table->string('nama_bendahara');
            $table->string('jabatan_bendahara')->default('BENDAHARA SEKOLAH');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('lpj_insentif_penerimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_insentif_id')->constrained('lpj_insentifs')->cascadeOnDelete();
            $table->string('npwp', 30)->nullable();
            $table->string('nama');
            $table->string('nip_pegid_nuptk', 30)->nullable();
            $table->string('pangkat', 50)->nullable();
            $table->string('golongan', 10)->nullable();
            $table->enum('status_pegawai', ['PNS', 'PPPK', 'NON PNS'])->default('NON PNS');
            $table->string('jabatan');
            $table->unsignedBigInteger('tarif')->default(0);
            $table->unsignedInteger('volume')->default(1);
            $table->string('satuan', 10)->default('BLN');
            $table->unsignedBigInteger('pph21')->default(0);
            $table->string('no_rekening', 30)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_insentif_penerimas');
        Schema::dropIfExists('lpj_insentifs');
    }
};
