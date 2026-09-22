<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ValidasiDataSiswaForm;
use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use App\Models\ValidasiDataSiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ValidasiDataSiswaFormTest extends TestCase
{
    use RefreshDatabase;

    private function setPin(string $pin = '1234'): void
    {
        SchoolSetting::set('validasi_data_siswa_pin', $pin);
    }

    private function buatSiswa(string $nama = 'Ahmad Fauzi', string $rombel = 'Kelas 1 - KELAS 1A'): SiswaMi
    {
        return SiswaMi::create([
            'nama_lengkap' => $nama,
            'nik' => '3276010101100001',
            'alamat' => 'Jl. Rahasia No. 1',
            'tanggal_lahir' => '2018-05-17',
            'tingkat_rombel' => $rombel,
            'jenis_kelamin' => 'L',
            'status' => 'Aktif',
        ]);
    }

    public function test_akses_tertutup_saat_admin_belum_menyetel_pin(): void
    {
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->assertSet('pinTersedia', false)
            ->set('pinInput', '')
            ->call('bukaAkses')
            ->assertSet('pinOk', false);
    }

    public function test_pin_salah_tidak_membuka_akses(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '9999')
            ->call('bukaAkses')
            ->assertSet('pinOk', false)
            ->assertHasErrors('pinInput');
    }

    public function test_pin_benar_membuka_daftar_kelas(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->assertSet('pinOk', true)
            ->assertSee('Kelas 1 - KELAS 1A');
    }

    public function test_daftar_siswa_hanya_menampilkan_nama_dan_tanggal_lahir(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', 'Kelas 1 - KELAS 1A')
            ->assertSee('Ahmad Fauzi')
            ->assertSee('17/05/2018')
            ->assertDontSee('3276010101100001')
            ->assertDontSee('Jl. Rahasia No. 1');
    }

    public function test_tandai_lengkap_menyimpan_status_lengkap(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', 'Kelas 1 - KELAS 1A')
            ->set('namaPengisi', 'Bu Siti')
            ->call('tandaiLengkap')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('validasi_data_siswas', [
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'status' => 'lengkap',
            'nama_pengisi' => 'Bu Siti',
            'catatan' => null,
        ]);
    }

    public function test_submit_ulang_kelas_yang_sama_menimpa_baris_yang_sama(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        $kirim = function (string $pengisi) {
            Livewire::test(ValidasiDataSiswaForm::class)
                ->set('pinInput', '1234')
                ->call('bukaAkses')
                ->set('sekolah', 'MI')
                ->set('tingkatRombel', 'Kelas 1 - KELAS 1A')
                ->set('namaPengisi', $pengisi)
                ->call('tandaiLengkap');
        };

        $kirim('Bu Siti');
        $kirim('Pak Budi');

        $this->assertDatabaseCount('validasi_data_siswas', 1);
        $this->assertDatabaseHas('validasi_data_siswas', ['nama_pengisi' => 'Pak Budi']);
    }

    public function test_kirim_catatan_menyimpan_status_ada_catatan(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', 'Kelas 1 - KELAS 1A')
            ->set('namaPengisi', 'Bu Siti')
            ->set('catatan', 'Zahra Aulia belum ada di daftar')
            ->call('kirimCatatan')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('validasi_data_siswas', [
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'status' => 'ada_catatan',
            'catatan' => 'Zahra Aulia belum ada di daftar',
        ]);
    }

    public function test_kirim_catatan_kosong_ditolak(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', 'Kelas 1 - KELAS 1A')
            ->set('namaPengisi', 'Bu Siti')
            ->set('catatan', '')
            ->call('kirimCatatan')
            ->assertHasErrors('catatan');

        $this->assertDatabaseCount('validasi_data_siswas', 0);
    }

    public function test_nama_pengisi_wajib_diisi(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->set('sekolah', 'MI')
            ->set('tingkatRombel', 'Kelas 1 - KELAS 1A')
            ->set('namaPengisi', '')
            ->call('tandaiLengkap')
            ->assertHasErrors('namaPengisi');

        $this->assertDatabaseCount('validasi_data_siswas', 0);
    }

    public function test_lima_kali_pin_salah_mengunci_percobaan_berikutnya(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        $component = Livewire::test(ValidasiDataSiswaForm::class);

        for ($i = 0; $i < 5; $i++) {
            $component->set('pinInput', '0000')->call('bukaAkses');
        }

        $component->set('pinInput', '1234')
            ->call('bukaAkses')
            ->assertSet('pinOk', false)
            ->assertSet('terkunci', true);
    }

    public function test_status_validasi_terakhir_tampil_di_daftar_kelas(): void
    {
        $this->setPin('1234');
        $this->buatSiswa();

        ValidasiDataSiswa::create([
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'status' => 'lengkap',
            'nama_pengisi' => 'Bu Siti',
            'checked_at' => now(),
        ]);

        Livewire::test(ValidasiDataSiswaForm::class)
            ->set('pinInput', '1234')
            ->call('bukaAkses')
            ->assertSee('Lengkap');
    }
}
