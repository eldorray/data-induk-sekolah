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
        Schema::table('surat_keterangan_aktifs', function (Blueprint $table) {
            // Drop foreign key constraint terlebih dahulu
            $table->dropForeign(['siswa_id']);

            // Tambah kolom siswa_type untuk polymorphic relation
            $table->string('siswa_type')->after('id')->default('App\\Models\\SiswaMi');

            // Ubah siswa_id menjadi unsignedBigInteger biasa (tanpa foreign key)
            $table->unsignedBigInteger('siswa_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keterangan_aktifs', function (Blueprint $table) {
            $table->dropColumn('siswa_type');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
        });
    }
};
