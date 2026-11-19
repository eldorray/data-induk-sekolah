<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = ['siswa_mis', 'siswa_smps'];

    public function up(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                // 2 digit tahun masuk + 4 digit urut. Unik per unit: tiap sekolah punya buku induk sendiri.
                $table->string('nomor_induk', 6)->nullable()->unique()->after('nisn');
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                $table->dropUnique($name.'_nomor_induk_unique');
                $table->dropColumn('nomor_induk');
            });
        }
    }
};
