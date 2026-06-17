<?php

namespace Database\Seeders;

use App\Models\SyaratPindahan;
use Illuminate\Database\Seeder;

class SyaratPindahanSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'Surat mutasi dari sekolah asal',
            'Surat rekomendasi dari Dinas Kota/Kabupaten',
            'Buku rapor siswa',
            'Kartu NISN / Surat Keterangan NISN',
        ];

        // Hanya seed jika tabel masih kosong, agar tidak duplikat saat seeder dipanggil ulang
        if (SyaratPindahan::count() > 0) {
            return;
        }

        foreach ($defaults as $i => $syarat) {
            SyaratPindahan::create([
                'syarat' => $syarat,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
