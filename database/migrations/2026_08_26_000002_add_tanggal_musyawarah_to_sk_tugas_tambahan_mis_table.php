<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sk_tugas_tambahan_mis', function (Blueprint $table) {
            $table->date('tanggal_musyawarah')->nullable()->after('tanggal_sk');
        });

        DB::table('sk_tugas_tambahan_mis')
            ->whereNull('tanggal_musyawarah')
            ->update(['tanggal_musyawarah' => '2025-07-02']);
    }

    public function down(): void
    {
        Schema::table('sk_tugas_tambahan_mis', function (Blueprint $table) {
            $table->dropColumn('tanggal_musyawarah');
        });
    }
};