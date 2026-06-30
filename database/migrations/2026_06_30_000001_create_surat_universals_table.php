<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_universals', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->string('judul');
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('jenjang')->nullable();
            $table->string('kop_path')->nullable();
            $table->longText('isi')->nullable();
            $table->string('tempat')->nullable();
            $table->string('ttd_jabatan')->nullable();
            $table->string('ttd_nama')->nullable();
            $table->string('ttd_nip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_universals');
    }
};
