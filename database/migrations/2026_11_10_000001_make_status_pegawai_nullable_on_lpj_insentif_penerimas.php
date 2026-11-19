<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Status pegawai boleh kosong (mis. tenaga administrasi/instruktur yang tidak dicentang di format LPJ).
    public function up(): void
    {
        Schema::table('lpj_insentif_penerimas', function (Blueprint $table) {
            $table->string('status_pegawai', 10)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        DB::table('lpj_insentif_penerimas')->whereNull('status_pegawai')->update(['status_pegawai' => 'NON PNS']);

        Schema::table('lpj_insentif_penerimas', function (Blueprint $table) {
            $table->enum('status_pegawai', ['PNS', 'PPPK', 'NON PNS'])->default('NON PNS')->change();
        });
    }
};
