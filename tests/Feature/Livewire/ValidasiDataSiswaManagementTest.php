<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ValidasiDataSiswaManagement;
use App\Models\SiswaMi;
use App\Models\User;
use App\Models\ValidasiDataSiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ValidasiDataSiswaManagementTest extends TestCase
{
    use RefreshDatabase;

    private function buatSiswa(string $rombel = 'Kelas 1 - KELAS 1A'): SiswaMi
    {
        return SiswaMi::create([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tanggal_lahir' => '2018-05-17',
            'tingkat_rombel' => $rombel,
            'jenis_kelamin' => 'L',
            'status' => 'Aktif',
        ]);
    }

    public function test_guest_diarahkan_ke_login(): void
    {
        $this->get(route('validasi-data-siswa.index'))->assertRedirect(route('login'));
    }

    public function test_guru_tidak_boleh_mengakses(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);

        $this->actingAs($guru)
            ->get(route('validasi-data-siswa.index'))
            ->assertRedirect(route('nilai-ijazah.index'));
    }

    public function test_admin_boleh_mengakses(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('validasi-data-siswa.index'))
            ->assertOk();
    }

    public function test_kelas_yang_belum_pernah_divalidasi_tampil_sebagai_belum_dicek(): void
    {
        $this->buatSiswa();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(ValidasiDataSiswaManagement::class)
            ->assertSee('Kelas 1 - KELAS 1A')
            ->assertSee('Belum Dicek');
    }

    public function test_kelas_dengan_catatan_menampilkan_catatan_dan_pengisi(): void
    {
        $this->buatSiswa();
        ValidasiDataSiswa::create([
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'status' => 'ada_catatan',
            'nama_pengisi' => 'Bu Siti',
            'catatan' => 'Zahra Aulia belum ada',
            'checked_at' => now(),
        ]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(ValidasiDataSiswaManagement::class)
            ->assertSee('Ada Catatan')
            ->assertSee('Bu Siti')
            ->assertSee('Zahra Aulia belum ada');
    }

    public function test_tandai_selesai_mereset_status_ke_belum(): void
    {
        $this->buatSiswa();
        $row = ValidasiDataSiswa::create([
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'status' => 'ada_catatan',
            'nama_pengisi' => 'Bu Siti',
            'catatan' => 'Zahra Aulia belum ada',
            'checked_at' => now(),
        ]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(ValidasiDataSiswaManagement::class)
            ->call('tandaiSelesai', $row->id);

        $this->assertDatabaseHas('validasi_data_siswas', [
            'id' => $row->id,
            'status' => 'belum',
            'catatan' => null,
        ]);
    }

    public function test_admin_melihat_riwayat_unduhan(): void
    {
        $this->buatSiswa();
        \App\Models\UnduhanDataSiswa::create([
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'nama_pengisi' => 'Bu Siti',
            'ip_address' => '203.0.113.9',
        ]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(ValidasiDataSiswaManagement::class)
            ->assertSee('Riwayat Unduhan')
            ->assertSee('Bu Siti')
            ->assertSee('203.0.113.9');
    }

    public function test_filter_status_menyaring_baris(): void
    {
        $this->buatSiswa('Kelas 1 - KELAS 1A');
        $this->buatSiswa('Kelas 2 - KELAS 2A');
        ValidasiDataSiswa::create([
            'sekolah' => 'MI',
            'tingkat_rombel' => 'Kelas 1 - KELAS 1A',
            'status' => 'lengkap',
            'nama_pengisi' => 'Bu Siti',
            'checked_at' => now(),
        ]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(ValidasiDataSiswaManagement::class)
            ->set('filterStatus', 'lengkap')
            ->assertSee('Kelas 1 - KELAS 1A')
            ->assertDontSee('Kelas 2 - KELAS 2A');
    }
}
