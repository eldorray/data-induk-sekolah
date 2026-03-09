<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the foreign key constraint first
        Schema::table('mutasi_siswas', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
        });

        Schema::table('mutasi_siswas', function (Blueprint $table) {
            $table->string('siswa_type')->default('siswa_mi')->after('id');
        });

        // Update existing records to use siswa_mi as default
        DB::table('mutasi_siswas')->update(['siswa_type' => 'siswa_mi']);
    }

    public function down(): void
    {
        Schema::table('mutasi_siswas', function (Blueprint $table) {
            $table->dropColumn('siswa_type');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
        });
    }
};
