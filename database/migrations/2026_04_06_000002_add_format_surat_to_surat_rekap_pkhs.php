<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_rekap_pkhs', function (Blueprint $table) {
            $table->string('format_surat', 20)->default('rekap_absensi')->after('semester');
            // format_surat: 'rekap_absensi' (format 2) or 'surat_keterangan' (format 1)
        });
    }

    public function down(): void
    {
        Schema::table('surat_rekap_pkhs', function (Blueprint $table) {
            $table->dropColumn('format_surat');
        });
    }
};
