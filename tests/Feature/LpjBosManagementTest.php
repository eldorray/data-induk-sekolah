<?php

namespace Tests\Feature;

use App\Livewire\LpjBosManagement;
use App\Models\Kuitansi;
use App\Models\LpjBos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LpjBosManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_lpj_bos_page(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get('/lpj-bos')
            ->assertOk()
            ->assertSee('LPJ BOS');
    }

    public function test_component_lists_kuitansi_with_lpj_status(): void
    {
        $kuitansi = Kuitansi::create([
            'nomor_bukti' => '001',
            'tahun_anggaran' => '2026',
            'penerima' => 'Bendahara',
            'jumlah_uang' => 1500000,
            'uraian_pembayaran' => 'Pengadaan ATK',
            'tanggal_lunas' => '2026-03-01',
        ]);

        Livewire::test(LpjBosManagement::class)
            ->assertSee('Pengadaan ATK')
            ->assertSee('Belum Ada LPJ')
            ->call('openCreateModal', $kuitansi->id)
            ->set('nama_kegiatan', 'Pengadaan ATK Semester 1')
            ->set('tanggal_kegiatan', '2026-03-02')
            ->set('lokasi', 'Madrasah')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('lpj_bos', [
            'kuitansi_id' => $kuitansi->id,
            'nama_kegiatan' => 'Pengadaan ATK Semester 1',
        ]);
    }

    public function test_component_updates_and_deletes_lpj(): void
    {
        $kuitansi = Kuitansi::create([
            'nomor_bukti' => '002',
            'tahun_anggaran' => '2026',
            'penerima' => 'Panitia',
            'jumlah_uang' => 750000,
            'uraian_pembayaran' => 'Konsumsi rapat',
            'tanggal_lunas' => '2026-04-01',
        ]);

        $lpj = LpjBos::create([
            'kuitansi_id' => $kuitansi->id,
            'nama_kegiatan' => 'Rapat Komite',
            'tanggal_kegiatan' => '2026-04-02',
            'lokasi' => 'Aula',
        ]);

        Livewire::test(LpjBosManagement::class)
            ->call('openEditModal', $lpj->id)
            ->set('nama_kegiatan', 'Rapat Komite Madrasah')
            ->call('save')
            ->assertHasNoErrors()
            ->call('openDeleteModal', $lpj->id)
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('lpj_bos', ['id' => $lpj->id]);
    }
}
