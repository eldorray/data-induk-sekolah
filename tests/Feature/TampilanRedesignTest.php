<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SiswaMi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TampilanRedesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_publik_tidak_menampilkan_statistik_maupun_identitas_sensitif(): void
    {
        SiswaMi::create(['nama_lengkap' => 'Aisyah Rahma', 'tingkat_rombel' => 'Kelas 4 - KELAS 4B', 'status' => 'Aktif']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Masuk ke portal')
            ->assertDontSee('NPSN')
            ->assertDontSee('Siswa MI')
            ->assertDontSee('Aisyah Rahma');
    }

    public function test_dashboard_admin_menampilkan_siswa_per_rombel(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        SiswaMi::create(['nama_lengkap' => 'Siswa A', 'tingkat_rombel' => 'Kelas 4 - KELAS 4B', 'status' => 'Aktif']);
        SiswaMi::create(['nama_lengkap' => 'Siswa B', 'tingkat_rombel' => 'Kelas 4 - KELAS 4B', 'status' => 'Lulus']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Siswa per rombel')
            ->assertSee('4B')
            ->assertSee('MI 4B')
            ->assertDontSee('Selamat Datang');
    }

    public function test_dashboard_ditolak_untuk_tamu(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }
}
