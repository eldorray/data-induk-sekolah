<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SchoolSettingsManagement;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SchoolSettingsPinTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_menyimpan_pin_validasi_data_siswa(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(SchoolSettingsManagement::class)
            ->set('nama_sekolah', 'MI Darul Hikmah')
            ->set('validasi_data_siswa_pin', '2468')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('2468', SchoolSetting::get('validasi_data_siswa_pin'));
    }

    public function test_pin_yang_tersimpan_dimuat_ke_form(): void
    {
        SchoolSetting::set('validasi_data_siswa_pin', '1357');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(SchoolSettingsManagement::class)
            ->assertSet('validasi_data_siswa_pin', '1357');
    }

    public function test_pin_minimal_empat_karakter(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        Livewire::actingAs($admin)
            ->test(SchoolSettingsManagement::class)
            ->set('nama_sekolah', 'MI Darul Hikmah')
            ->set('validasi_data_siswa_pin', '12')
            ->call('save')
            ->assertHasErrors('validasi_data_siswa_pin');

        $this->assertNull(SchoolSetting::get('validasi_data_siswa_pin'));
    }
}
