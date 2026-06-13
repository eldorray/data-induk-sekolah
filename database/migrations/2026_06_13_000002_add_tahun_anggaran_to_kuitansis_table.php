<?php

use App\Models\SchoolSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuitansis', function (Blueprint $table) {
            $table->string('tahun_anggaran', 10)->nullable()->after('nomor_bukti');
        });

        // Isi data lama dengan tahun anggaran default dari pengaturan.
        $default = SchoolSetting::get('kuitansi_tahun_anggaran', (string) date('Y'));
        DB::table('kuitansis')->whereNull('tahun_anggaran')->update(['tahun_anggaran' => $default]);
    }

    public function down(): void
    {
        Schema::table('kuitansis', function (Blueprint $table) {
            $table->dropColumn('tahun_anggaran');
        });
    }
};
