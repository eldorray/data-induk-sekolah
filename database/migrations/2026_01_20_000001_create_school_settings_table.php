<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $settings = [
            'nama_sekolah' => 'MI DAARUL HIKMAH',
            'nama_yayasan' => 'YAYASAN PENDIDIKAN DAARUL HIKMAH AL MADANI',
            'npsn' => '69755384',
            'nsm' => '111236710070',
            'alamat' => 'Jl. Pembangunan 3 No.103 Rt.05/05',
            'kelurahan' => 'Karangsari',
            'kecamatan' => 'Neglasari',
            'kota' => 'Kota Tangerang',
            'provinsi' => 'Banten',
            'kode_pos' => '15121',
            'telepon' => '(021)55722762',
            'email' => '',
            'nama_kepala' => 'Dra. DRA NURJANAH',
            'nip_kepala' => '196512181994032002',
            'kop_surat_path' => '',
            'stempel_path' => '',
            'ttd_kepala_path' => '',
        ];

        foreach ($settings as $key => $value) {
            \DB::table('school_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
