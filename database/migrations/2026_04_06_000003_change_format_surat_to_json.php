<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change from string to text (model handles JSON casting)
        Schema::table('surat_rekap_pkhs', function (Blueprint $table) {
            $table->text('format_surat')->change();
        });

        // Convert existing string values to JSON array
        DB::table('surat_rekap_pkhs')
            ->whereRaw("format_surat NOT LIKE '[%'")
            ->update([
                'format_surat' => DB::raw("CONCAT('[\"', format_surat, '\"]')"),
            ]);
    }

    public function down(): void
    {
        Schema::table('surat_rekap_pkhs', function (Blueprint $table) {
            $table->string('format_surat', 20)->default('rekap_absensi')->change();
        });
    }
};

