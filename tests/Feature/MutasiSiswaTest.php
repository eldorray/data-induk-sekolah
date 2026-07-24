<?php

namespace Tests\Feature;

use App\Livewire\MutasiSiswaManagement;
use App\Models\MutasiSiswa;
use App\Models\SiswaMi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MutasiSiswaTest extends TestCase
{
    use RefreshDatabase;

    private function siswaAktif(): SiswaMi
    {
        return SiswaMi::create([
            'nama_lengkap' => 'Budi',
            'nisn' => '1234567890',
            'tingkat_rombel' => '6A',
            'status' => 'Aktif',
        ]);
    }

    private function mutasiDisetujui(SiswaMi $siswa): MutasiSiswa
    {
        return MutasiSiswa::create([
            'siswa_id' => $siswa->id,
            'siswa_type' => 'siswa_mi',
            'nomor_surat' => '001/MIDH/SK.PS/I/2026',
            'tanggal_surat' => '2026-01-10',
            'tanggal_mutasi' => '2026-01-10',
            'jenis_mutasi' => 'pindah',
            'alasan_mutasi' => 'Ikut orang tua',
            'status' => 'disetujui',
        ]);
    }

    public function test_membatalkan_mutasi_mengembalikan_siswa_aktif(): void
    {
        $siswa = $this->siswaAktif();
        $mutasi = $this->mutasiDisetujui($siswa);
        $siswa->update(['status' => 'Pindah']); // efek mutasi disetujui

        Livewire::test(MutasiSiswaManagement::class)
            ->call('openEditModal', $mutasi->id)
            ->set('status', 'dibatalkan')
            ->call('save');

        $this->assertSame('Aktif', $siswa->fresh()->status);
    }

    public function test_menghapus_mutasi_disetujui_mengembalikan_siswa_aktif(): void
    {
        $siswa = $this->siswaAktif();
        $mutasi = $this->mutasiDisetujui($siswa);
        $siswa->update(['status' => 'Pindah']);

        Livewire::test(MutasiSiswaManagement::class)
            ->call('openDeleteModal', $mutasi->id)
            ->call('delete');

        $this->assertSame('Aktif', $siswa->fresh()->status);
    }

    public function test_mutasi_disetujui_menandai_siswa_pindah(): void
    {
        $siswa = $this->siswaAktif();
        $mutasi = $this->mutasiDisetujui($siswa);

        Livewire::test(MutasiSiswaManagement::class)
            ->call('openEditModal', $mutasi->id)
            ->set('status', 'disetujui')
            ->call('save');

        $this->assertSame('Pindah', $siswa->fresh()->status);
    }

    public function test_menyimpan_siswa_dengan_jenis_kelamin_kosong_jadi_null(): void
    {
        // enum('L','P') tidak boleh '' — harus dikonversi ke null saat save
        $siswa = SiswaMi::create([
            'nama_lengkap' => 'Ani',
            'jenis_kelamin' => '',
            'status' => 'Aktif',
        ]);

        $this->assertNull($siswa->fresh()->jenis_kelamin);
    }
}
