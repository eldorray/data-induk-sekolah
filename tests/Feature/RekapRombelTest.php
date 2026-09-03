<?php

namespace Tests\Feature;

use App\Models\SiswaMi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekapRombelTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_rombel_menghitung_rombel_dan_siswa_per_tingkat(): void
    {
        $buat = function (string $rombel, string $jk, string $status = 'Aktif') {
            SiswaMi::create([
                'nama_lengkap' => 'Siswa '.uniqid(),
                'tingkat_rombel' => $rombel,
                'jenis_kelamin' => $jk,
                'status' => $status,
            ]);
        };

        $buat('Kelas 1 - KELAS 1A', 'L');
        $buat('Kelas 1 - KELAS 1B', 'P');
        $buat('Kelas 1 - KELAS 1B', 'P');
        $buat('Kelas 6 - KELAS 6A', 'L');
        $buat('Kelas 6 - KELAS 6A', 'L', 'Lulus'); // non-aktif, tidak dihitung

        $rekap = SiswaMi::rekapRombel();

        $this->assertSame(['rombel' => 2, 'l' => 1, 'p' => 2, 'total' => 3], $rekap['tingkat']['I']);
        $this->assertSame(['rombel' => 1, 'l' => 1, 'p' => 0, 'total' => 1], $rekap['tingkat']['VI']);
        $this->assertSame(['rombel' => 0, 'l' => 0, 'p' => 0, 'total' => 0], $rekap['tingkat']['III']);
        $this->assertSame(['rombel' => 3, 'l' => 2, 'p' => 2, 'total' => 4], $rekap['total']);
    }
}
