<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\SiswaMiManagement;
use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Models\User;
use App\Services\NomorIndukSiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class NomorIndukSiswaTest extends TestCase
{
    use RefreshDatabase;

    private const KELAS_3A = 'Kelas 3 - KELAS 3A';

    private function siswa(string $nama, string $rombel = self::KELAS_3A, array $lain = []): SiswaMi
    {
        return SiswaMi::create(['nama_lengkap' => $nama, 'tingkat_rombel' => $rombel, 'status' => 'Aktif', ...$lain]);
    }

    public function test_generate_mengisi_dua_digit_tahun_dan_empat_digit_urut_sesuai_abjad(): void
    {
        $zahra = $this->siswa('Zahra');
        $ahmad = $this->siswa('Ahmad');
        $kelasLain = $this->siswa('Budi', 'Kelas 4 - KELAS 4A');

        $jumlah = NomorIndukSiswa::generate(SiswaMi::class, self::KELAS_3A, 2024);

        $this->assertSame(2, $jumlah);
        $this->assertSame('240001', $ahmad->refresh()->nomor_induk);
        $this->assertSame('240002', $zahra->refresh()->nomor_induk);
        $this->assertNull($kelasLain->refresh()->nomor_induk);
    }

    public function test_generate_melanjutkan_urut_terakhir_tahun_itu_dan_tidak_menimpa(): void
    {
        $lama = $this->siswa('Citra', self::KELAS_3A, ['nomor_induk' => '240007']);
        $tahunLain = $this->siswa('Dewi', 'Kelas 2 - KELAS 2A', ['nomor_induk' => '250050']);
        $baru = $this->siswa('Eko');

        NomorIndukSiswa::generate(SiswaMi::class, self::KELAS_3A, 2024);

        $this->assertSame('240007', $lama->refresh()->nomor_induk);
        $this->assertSame('250050', $tahunLain->refresh()->nomor_induk);
        $this->assertSame('240008', $baru->refresh()->nomor_induk);
    }

    public function test_generate_dua_kali_tidak_menghasilkan_nomor_kembar(): void
    {
        $this->siswa('Ahmad');
        $this->siswa('Budi', 'Kelas 3 - KELAS 3B');

        NomorIndukSiswa::generate(SiswaMi::class, self::KELAS_3A, 2024);
        NomorIndukSiswa::generate(SiswaMi::class, 'Kelas 3 - KELAS 3B', 2024);
        $ulang = NomorIndukSiswa::generate(SiswaMi::class, self::KELAS_3A, 2024);

        $this->assertSame(0, $ulang);
        $this->assertSame(['240001', '240002'], SiswaMi::orderBy('nomor_induk')->pluck('nomor_induk')->all());
    }

    public function test_siswa_tidak_aktif_dilewati(): void
    {
        $lulus = $this->siswa('Fajar', self::KELAS_3A, ['status' => 'Pindah']);

        $this->assertSame(0, NomorIndukSiswa::generate(SiswaMi::class, self::KELAS_3A, 2024));
        $this->assertNull($lulus->refresh()->nomor_induk);
    }

    public function test_tahun_masuk_bawaan_dihitung_dari_tingkat_dan_tahun_ajaran(): void
    {
        Carbon::setTestNow('2026-11-19'); // tahun ajaran 2026/2027

        $this->assertSame(2026, NomorIndukSiswa::tahunMasuk('Kelas 1 - KELAS 1A', 1));
        $this->assertSame(2024, NomorIndukSiswa::tahunMasuk(self::KELAS_3A, 1));
        $this->assertSame(2025, NomorIndukSiswa::tahunMasuk('Kelas 8 - KELAS 8A', 7));

        Carbon::setTestNow('2027-03-01'); // masih tahun ajaran 2026/2027
        $this->assertSame(2024, NomorIndukSiswa::tahunMasuk(self::KELAS_3A, 1));
    }

    public function test_nomor_induk_lengkap_mi_memakai_nsm_bila_diatur(): void
    {
        SchoolSetting::set('nsm', '111236710070');
        $siswa = $this->siswa('Ahmad', self::KELAS_3A, ['nomor_induk' => '240001']);

        $this->assertSame('240001', $siswa->nomor_induk_lengkap);

        SchoolSetting::set('format_nomor_induk', 'nsm');
        $this->assertSame('111236710070240001', $siswa->nomor_induk_lengkap);
        $this->assertNull($this->siswa('Tanpa Nomor')->nomor_induk_lengkap);

        $smp = SiswaSmp::create(['nama_lengkap' => 'Gilang', 'status' => 'Aktif', 'nomor_induk' => '240001']);
        $this->assertSame('240001', $smp->nomor_induk_lengkap);
    }

    public function test_admin_generate_lewat_halaman_siswa_mi(): void
    {
        Carbon::setTestNow('2026-11-19');
        $siswa = $this->siswa('Ahmad');

        Livewire::actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]))
            ->test(SiswaMiManagement::class)
            ->call('openGenerateModal')
            ->set('genKelas', self::KELAS_3A)
            ->assertSet('genTahunMasuk', 2024)
            ->call('generateNomorInduk')
            ->assertHasNoErrors()
            ->assertSet('showGenerateModal', false);

        $this->assertSame('240001', $siswa->refresh()->nomor_induk);
    }

    public function test_nomor_induk_manual_harus_enam_digit_dan_unik(): void
    {
        $this->siswa('Ahmad', self::KELAS_3A, ['nomor_induk' => '240001']);

        Livewire::actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]))
            ->test(SiswaMiManagement::class)
            ->call('openCreateModal')
            ->set('nama_lengkap', 'Budi')
            ->set('nomor_induk', '240001')
            ->call('save')
            ->assertHasErrors(['nomor_induk' => 'unique'])
            ->set('nomor_induk', '2401')
            ->call('save')
            ->assertHasErrors(['nomor_induk' => 'digits'])
            ->set('nomor_induk', '240002')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('240002', SiswaMi::where('nama_lengkap', 'Budi')->value('nomor_induk'));
    }
}
