<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracer_alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->enum('jenjang', ['MI', 'SMP']);
            $table->string('tahun_lulus');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();

            // Data setelah lulus
            $table->string('status_sekarang'); // Bekerja, Kuliah, Wirausaha, Belum Bekerja, Lainnya
            $table->string('nama_institusi')->nullable(); // Nama perusahaan/universitas/usaha
            $table->string('jurusan_bidang')->nullable(); // Jurusan kuliah / bidang pekerjaan
            $table->string('tahun_masuk')->nullable(); // Tahun masuk kerja/kuliah

            // Feedback untuk sekolah (marketing & analisa)
            $table->integer('kepuasan_pendidikan')->nullable(); // 1-5 scale
            $table->text('kesan_pesan')->nullable();
            $table->boolean('bersedia_dihubungi')->default(true);
            $table->string('sumber_info')->nullable(); // Dari mana tahu form ini

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_alumnis');
    }
};