<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            
            // Identitas Utama
            $table->string('nip', 30)->nullable();    // NIP (PNS)
            $table->string('nuptk', 30)->nullable();  // NUPTK (Nasional)
            $table->string('npk', 30)->nullable();    // NPK (Kemenag - Opsional)
            $table->string('nik', 16)->unique();      // NIK KTP (Wajib Unik)
            
            // Nama & Gelar (Dipisah agar rapi)
            $table->string('front_title', 20)->nullable(); // Gelar Depan
            $table->string('full_name');                   // Nama Asli
            $table->string('back_title', 20)->nullable();  // Gelar Belakang
            
            // Biodata
            $table->enum('gender', ['L', 'P']);
            $table->string('pob')->nullable();        // Place of Birth
            $table->date('dob')->nullable();          // Date of Birth
            $table->string('phone_number', 15)->nullable();
            $table->text('address')->nullable();
            
            // Jabatan & Sistem
            $table->string('status_pegawai')->default('GTY'); // GTY, GTT, PNS
            $table->boolean('is_active')->default(true);
            
            // File SK (path to storage)
            $table->string('sk_awal_path')->nullable();     // SK Pengangkatan Awal
            $table->string('sk_akhir_path')->nullable();    // SK Terakhir
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
