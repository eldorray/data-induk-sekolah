<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class KuitansiSettingSeeder extends Seeder
{
    /**
     * Konstanta lembaga untuk Kuitansi / Bukti Pembayaran Dana BOS.
     * Hanya menambahkan key yang belum ada (tidak menimpa nilai yang sudah diubah user).
     */
    public function run(): void
    {
        $defaults = [
            'kuitansi_tahun_anggaran'   => '2026',
            'kuitansi_nama_madrasah'    => 'MI Daarul Hikmah',
            'kuitansi_desa_kecamatan'   => 'Neglasari',
            'kuitansi_kabupaten'        => 'Kota Tangerang',
            'kuitansi_provinsi'         => 'Banten',
            'kuitansi_sumber_dana'      => 'Dana BOS Tahap 1 2026',
            'kuitansi_format_nomor'     => '.../T1/MIDH/2026',
            'kuitansi_sudah_terima_dari' => 'Kepala Madrasah',
            'kuitansi_kepala_madrasah'  => 'Dra. Nurjanah',
            'kuitansi_bendahara_madrasah' => 'Fahmie Al Khudhorie, S.Pd',
        ];

        foreach ($defaults as $key => $value) {
            if (! SchoolSetting::where('key', $key)->exists()) {
                SchoolSetting::set($key, $value);
            }
        }

        SchoolSetting::clearCache();
    }
}
