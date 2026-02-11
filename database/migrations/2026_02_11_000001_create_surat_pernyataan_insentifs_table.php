<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pernyataan_insentifs', function (Blueprint $table) {
            $table->id();

            // Relasi ke guru
            $table->unsignedBigInteger('guru_id');
            $table->string('guru_type')->default('guru_mi'); // guru_mi atau guru_smp

            // Data surat
            $table->string('jabatan')->default('Guru');
            $table->string('unit_kerja'); // MI DAARUL HIKMAH
            $table->text('alamat_unit_kerja')->nullable();
            $table->string('sumber_insentif')->default('APBD Kota Tangerang');
            $table->string('bulan_tahun'); // Januari 2025
            $table->date('tanggal_surat');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pernyataan_insentifs');
    }
};
