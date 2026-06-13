<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_bos_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_bos_id')->constrained('lpj_bos')->cascadeOnDelete();
            $table->string('kategori', 20);
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['lpj_bos_id', 'kategori', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_bos_attachments');
    }
};
