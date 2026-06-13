<?php

namespace Tests\Feature;

use App\Livewire\LpjBosDetail;
use App\Models\Kuitansi;
use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class LpjBosDetailTest extends TestCase
{
    use RefreshDatabase;

    private function createLpj(): LpjBos
    {
        $kuitansi = Kuitansi::create([
            'nomor_bukti' => '001',
            'tahun_anggaran' => '2026',
            'penerima' => 'Bendahara',
            'jumlah_uang' => 1500000,
            'uraian_pembayaran' => 'Pengadaan ATK',
            'tanggal_lunas' => '2026-03-01',
        ]);

        return LpjBos::create([
            'kuitansi_id' => $kuitansi->id,
            'nama_kegiatan' => 'Pengadaan ATK Semester 1',
            'tanggal_kegiatan' => '2026-03-02',
            'lokasi' => 'Madrasah',
        ]);
    }

    public function test_admin_can_view_detail_page(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj();

        $this->actingAs($admin)
            ->get('/lpj-bos/'.$lpj->id)
            ->assertOk()
            ->assertSee('Pengadaan ATK Semester 1')
            ->assertSee('Foto');
    }

    public function test_attachment_image_url_uses_the_request_host_not_a_hardcoded_app_url(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj();

        LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => 'foto',
            'file_path' => 'lpj-bos/1/foto/a.jpg',
            'original_name' => 'a.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1,
            'sort_order' => 1,
        ]);

        // Serve the page from a non-default host (as Herd does with .test domains).
        // The image src must point at that same host so the browser can load it.
        $this->actingAs($admin)
            ->get('http://data-induk.test/lpj-bos/'.$lpj->id)
            ->assertOk()
            ->assertSee('http://data-induk.test/storage/lpj-bos/1/foto/a.jpg')
            ->assertDontSee('http://localhost/storage/lpj-bos/1/foto/a.jpg');
    }

    public function test_selecting_files_uploads_automatically_without_a_separate_button(): void
    {
        Storage::fake('public');
        $lpj = $this->createLpj();

        // Mirror the browser flow: selecting a file binds it to the property and
        // triggers the `updated<Property>` lifecycle hook. No manual action call.
        Livewire::test(LpjBosDetail::class, ['lpj' => $lpj])
            ->set('fotoFiles', [UploadedFile::fake()->image('foto.jpg', 1200, 800)->size(9000)])
            ->assertHasNoErrors()
            ->set('kwitansiFiles', [UploadedFile::fake()->create('kwitansi.pdf', 4000, 'application/pdf')])
            ->assertHasNoErrors();

        $this->assertDatabaseHas('lpj_bos_attachments', ['lpj_bos_id' => $lpj->id, 'kategori' => 'foto']);
        $this->assertDatabaseHas('lpj_bos_attachments', ['lpj_bos_id' => $lpj->id, 'kategori' => 'kwitansi']);
        // Input arrays are cleared after processing so the same file is not re-saved.
        $this->assertSame(2, $lpj->fresh()->attachments()->count());
        $this->assertTrue($lpj->fresh()->is_complete);
    }

    public function test_caption_update_delete_and_reorder_work(): void
    {
        Storage::fake('public');
        $lpj = $this->createLpj();
        Storage::disk('public')->put('lpj-bos/1/foto/a.jpg', 'a');
        Storage::disk('public')->put('lpj-bos/1/foto/b.jpg', 'b');

        $first = LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => 'foto',
            'file_path' => 'lpj-bos/1/foto/a.jpg',
            'original_name' => 'a.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1,
            'sort_order' => 1,
        ]);

        $second = LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => 'foto',
            'file_path' => 'lpj-bos/1/foto/b.jpg',
            'original_name' => 'b.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1,
            'sort_order' => 2,
        ]);

        Livewire::test(LpjBosDetail::class, ['lpj' => $lpj])
            ->set('attachmentCaptions.'.$first->id, 'Foto kegiatan')
            ->call('saveCaption', $first->id)
            ->call('moveUp', $second->id)
            ->call('deleteAttachment', $first->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('lpj_bos_attachments', ['id' => $first->id]);
        Storage::disk('public')->assertMissing('lpj-bos/1/foto/a.jpg');
        $this->assertSame(1, $second->fresh()->sort_order);
    }
}
