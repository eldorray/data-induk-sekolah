<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuitansis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bukti', 20);            // nomor urut saja (mis. "001")
            $table->string('penerima');
            $table->unsignedBigInteger('jumlah_uang');
            $table->text('uraian_pembayaran');
            $table->date('tanggal_lunas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuitansis');
    }
};
