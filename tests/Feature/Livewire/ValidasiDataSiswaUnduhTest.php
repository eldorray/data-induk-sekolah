<?php

namespace Tests\Feature\Livewire;

use App\Exports\SiswaKelasPublikExport;
use App\Livewire\ValidasiDataSiswaForm;
use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Models\UnduhanDataSiswa;
use App\Models\ValidasiDataSiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ValidasiDataSiswaUnduhTest extends TestCase
{
    use RefreshDatabase;

    private const KELAS = 'Kelas 1 - KELAS 1A';

    protected function setUp(): void
    {
        parent::setUp();
        SchoolSetting::set('validasi_data_siswa_pin', '1234');
    }

    private function buatSiswa(string $rombel = self::KELAS): SiswaMi
    {
        return SiswaMi::create([
            'nama_lengkap' => 'Ahmad Fauzi',
            'nisn' => '0123456789',
            'nik' => '3276010101100001',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '2018-05-17',
            'tingkat_rombel' => $rombel,
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Melati No. 5',
            'no_telepon' => '081234567890',
            'nomor_kip_pip' => 'KIP-99887766',
            'nama_ayah_kandung' => 'Budi Santoso',
            'status' => 'Aktif',
        ]);
    }

    private function tandaiLengkap(): ValidasiDataSiswa
    {
        return ValidasiDataSiswa::create([
            'sekolah' => 'MI',
            'tingkat_rombel' => self::KELAS,
            'status' => ValidasiDataSiswa::STATUS_LENGKAP,
            'nama_pengisi' => 'Bu Siti',
            'checked_at' => now(),
        ]);
    }

    private function komponenSiap(): \Livewire\Features\SupportTesting\Testable
    {
        return Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', self::KELAS);
    }

    public function test_tombol_unduh_tidak_muncul_sebelum_kelas_ditandai_lengkap(): void
    {
        $this->buatSiswa();

        $this->komponenSiap()->assertDontSee('Unduh Data Lengkap');
    }

    public function test_tombol_unduh_muncul_setelah_kelas_lengkap(): void
    {
        $this->buatSiswa();
        $this->tandaiLengkap();

        $this->komponenSiap()->assertSee('Unduh Data Lengkap');
    }

    public function test_unduhan_ditolak_saat_kelas_belum_lengkap(): void
    {
        $this->buatSiswa();

        $this->komponenSiap()
            ->set('namaPengisi', 'Bu Siti')
            ->call('unduhExcel')
            ->assertHasErrors('tingkatRombel');

        $this->assertDatabaseCount('unduhan_data_siswas', 0);
    }

    public function test_unduhan_ditolak_tanpa_pin(): void
    {
        $this->buatSiswa();
        $this->tandaiLengkap();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', self::KELAS)
            ->set('namaPengisi', 'Bu Siti')
            ->call('unduhExcel')
            ->assertForbidden();

        $this->assertDatabaseCount('unduhan_data_siswas', 0);
    }

    public function test_unduhan_berhasil_saat_kelas_lengkap(): void
    {
        $this->buatSiswa();
        $this->tandaiLengkap();

        $response = $this->komponenSiap()
            ->set('namaPengisi', 'Bu Siti')
            ->call('unduhExcel')
            ->assertHasNoErrors()
            ->effects['download'] ?? null;

        $this->assertNotNull($response, 'Livewire harus mengirim file unduhan.');
    }

    public function test_unduhan_dicatat_ke_log(): void
    {
        $this->buatSiswa();
        $this->tandaiLengkap();

        $this->komponenSiap()
            ->set('namaPengisi', 'Bu Siti')
            ->call('unduhExcel');

        $this->assertDatabaseHas('unduhan_data_siswas', [
            'sekolah' => 'MI',
            'tingkat_rombel' => self::KELAS,
            'nama_pengisi' => 'Bu Siti',
        ]);

        $this->assertNotNull(UnduhanDataSiswa::first()->ip_address);
    }

    public function test_export_tidak_memuat_kolom_sensitif(): void
    {
        $headings = (new SiswaKelasPublikExport('MI', self::KELAS))->headings();

        $this->assertNotContains('NIK', $headings);
        $this->assertNotContains('No Telepon', $headings);
        $this->assertNotContains('Nomor KIP/PIP', $headings);

        $this->assertContains('Nama Lengkap', $headings);
        $this->assertContains('NISN', $headings);
        $this->assertContains('Alamat', $headings);
        $this->assertContains('Nama Ayah Kandung', $headings);
    }

    public function test_export_hanya_memuat_siswa_aktif_di_kelas_itu(): void
    {
        $this->buatSiswa();
        SiswaMi::create([
            'nama_lengkap' => 'Siswa Kelas Lain',
            'tingkat_rombel' => 'Kelas 2 - KELAS 2A',
            'status' => 'Aktif',
        ]);
        SiswaMi::create([
            'nama_lengkap' => 'Siswa Lulus',
            'tingkat_rombel' => self::KELAS,
            'status' => 'Lulus',
        ]);
        SiswaSmp::create([
            'nama_lengkap' => 'Siswa SMP',
            'tingkat_rombel' => self::KELAS,
            'status' => 'Aktif',
        ]);

        $nama = (new SiswaKelasPublikExport('MI', self::KELAS))
            ->collection()
            ->pluck('nama_lengkap')
            ->all();

        $this->assertSame(['Ahmad Fauzi'], $nama);
    }

    public function test_baris_export_tidak_memuat_nilai_sensitif(): void
    {
        $siswa = $this->buatSiswa();

        $baris = (new SiswaKelasPublikExport('MI', self::KELAS))->map($siswa);

        $this->assertNotContains('3276010101100001', $baris);
        $this->assertNotContains('081234567890', $baris);
        $this->assertNotContains('KIP-99887766', $baris);
        $this->assertContains('Ahmad Fauzi', $baris);
    }
}
