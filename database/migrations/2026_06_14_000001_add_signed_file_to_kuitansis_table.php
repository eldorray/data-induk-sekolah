<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuitansis', function (Blueprint $table) {
            // File kuitansi yang sudah ditandatangani (scan/foto PDF/JPG/PNG).
            $table->string('signed_file_path')->nullable()->after('tanggal_lunas');
            $table->string('signed_original_name')->nullable()->after('signed_file_path');
            $table->string('signed_mime_type', 100)->nullable()->after('signed_original_name');
            $table->unsignedBigInteger('signed_file_size')->nullable()->after('signed_mime_type');
            $table->timestamp('signed_uploaded_at')->nullable()->after('signed_file_size');
        });
    }

    public function down(): void
    {
        Schema::table('kuitansis', function (Blueprint $table) {
            $table->dropColumn([
                'signed_file_path',
                'signed_original_name',
                'signed_mime_type',
                'signed_file_size',
                'signed_uploaded_at',
            ]);
        });
    }
};
