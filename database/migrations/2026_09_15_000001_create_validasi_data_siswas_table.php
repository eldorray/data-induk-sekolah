<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validasi_data_siswas', function (Blueprint $table) {
            $table->id();
            $table->enum('sekolah', ['MI', 'SMP']);
            $table->string('tingkat_rombel');
            $table->enum('status', ['belum', 'lengkap', 'ada_catatan'])->default('belum');
            $table->string('nama_pengisi');
            $table->text('catatan')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->unique(['sekolah', 'tingkat_rombel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasi_data_siswas');
    }
};
