<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_ijazah_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nilai_ijazah_tahun_ajaran_id')
                ->constrained('nilai_ijazah_tahun_ajarans')
                ->cascadeOnDelete();

            // Polymorphic ke siswa (siswa_mi / siswa_smp) mengikuti pola project
            $table->unsignedBigInteger('siswa_id');
            $table->string('siswa_type', 50)->default('siswa_mi');

            // Polymorphic ke mapel (mapel_mi / mapel_smp) mengikuti pola project
            $table->unsignedBigInteger('mapel_id');
            $table->string('mapel_type', 50)->default('mapel_mi');

            // Komponen nilai raport kelas 4, 5, 6
            $table->decimal('kelas_4_semester_1', 5, 2)->nullable();
            $table->decimal('kelas_4_semester_2', 5, 2)->nullable();
            $table->decimal('kelas_5_semester_1', 5, 2)->nullable();
            $table->decimal('kelas_5_semester_2', 5, 2)->nullable();
            $table->decimal('kelas_6_semester_1', 5, 2)->nullable();

            // Nilai Ujian Madrasah
            $table->decimal('nilai_um', 5, 2)->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'nilai_ijazah_tahun_ajaran_id',
                    'siswa_id',
                    'siswa_type',
                    'mapel_id',
                    'mapel_type',
                ],
                'nilai_ijazah_scores_unique'
            );

            $table->index(['siswa_id', 'siswa_type'], 'nilai_ijazah_scores_siswa_idx');
            $table->index(['mapel_id', 'mapel_type'], 'nilai_ijazah_scores_mapel_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_ijazah_scores');
    }
};