<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_smps', function (Blueprint $table) {
            $table->id();
            
            // Identitas Utama
            $table->string('nip', 30)->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('npk', 30)->nullable();
            $table->string('nik', 16)->unique();
            
            // Nama & Gelar
            $table->string('front_title', 20)->nullable();
            $table->string('full_name');
            $table->string('back_title', 20)->nullable();
            
            // Biodata
            $table->enum('gender', ['L', 'P']);
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->text('address')->nullable();
            
            // Jabatan & Sistem
            $table->string('status_pegawai')->default('GTY');
            $table->boolean('is_active')->default(true);
            
            // File SK
            $table->string('sk_awal_path')->nullable();
            $table->string('sk_akhir_path')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_smps');
    }
};
