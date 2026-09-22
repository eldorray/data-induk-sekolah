<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unduhan_data_siswas', function (Blueprint $table) {
            $table->id();
            $table->enum('sekolah', ['MI', 'SMP']);
            $table->string('tingkat_rombel');
            $table->string('nama_pengisi');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['sekolah', 'tingkat_rombel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unduhan_data_siswas');
    }
};
