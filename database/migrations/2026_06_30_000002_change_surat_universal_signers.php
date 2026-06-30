<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_universals', function (Blueprint $table) {
            $table->json('signers')->nullable()->after('tempat');
            $table->string('ttd_atas')->nullable()->after('signers'); // label di atas ttd, mis. "Mengetahui,"
            $table->dropColumn(['ttd_jabatan', 'ttd_nama', 'ttd_nip']);
        });
    }

    public function down(): void
    {
        Schema::table('surat_universals', function (Blueprint $table) {
            $table->dropColumn(['signers', 'ttd_atas']);
            $table->string('ttd_jabatan')->nullable();
            $table->string('ttd_nama')->nullable();
            $table->string('ttd_nip')->nullable();
        });
    }
};
