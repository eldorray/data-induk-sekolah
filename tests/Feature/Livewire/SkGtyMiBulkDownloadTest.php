<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SkGtyMiManagement;
use App\Models\GuruMi;
use App\Models\SchoolSetting;
use App\Models\SkGtyMi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SkGtyMiBulkDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_aktif_sk_are_downloaded_as_one_file(): void
    {
        $first = $this->createSk(['nomor_sk' => '0001/SK/GTY/I/2026']);
        $second = $this->createSk([
            'nomor_sk' => '0002/SK/GTY/I/2026',
            'guru_mi_id' => $this->createGuru([
                'nik' => '3671000000000002',
                'full_name' => 'Guru Kedua',
                'nuptk' => '2222222222222222',
            ])->id,
        ]);

        Livewire::test(SkGtyMiManagement::class)
            ->set('selected', [(string) $first->id, (string) $second->id])
            ->call('bulkDownload')
            ->assertFileDownloaded();
    }

    public function test_non_aktif_sk_are_skipped_and_an_empty_selection_is_rejected(): void
    {
        $draft = $this->createSk(['status' => 'draft']);

        Livewire::test(SkGtyMiManagement::class)
            ->set('selected', [(string) $draft->id])
            ->call('bulkDownload')
            ->assertNoFileDownloaded()
            ->assertSee('Pilih minimal satu SK GTY berstatus aktif untuk diunduh.');
    }

    public function test_toggle_select_page_only_picks_aktif_rows_and_toggles_back_off(): void
    {
        $aktif = $this->createSk(['nomor_sk' => '0001/SK/GTY/I/2026']);
        $this->createSk([
            'nomor_sk' => '0002/SK/GTY/I/2026',
            'status' => 'draft',
            'guru_mi_id' => $this->createGuru([
                'nik' => '3671000000000002',
                'full_name' => 'Guru Draft',
                'nuptk' => '2222222222222222',
            ])->id,
        ]);

        Livewire::test(SkGtyMiManagement::class)
            ->call('toggleSelectPage')
            ->assertSet('selected', [(string) $aktif->id])
            ->call('toggleSelectPage')
            ->assertSet('selected', []);
    }

    public function test_pdf_view_renders_one_document_per_sk_separated_by_page_breaks(): void
    {
        $first = $this->createSk(['nomor_sk' => '0001/SK/GTY/I/2026']);
        $second = $this->createSk([
            'nomor_sk' => '0002/SK/GTY/I/2026',
            'guru_mi_id' => $this->createGuru([
                'nik' => '3671000000000002',
                'full_name' => 'Guru Kedua',
                'nuptk' => '2222222222222222',
            ])->id,
        ]);

        $html = view('pdf.sk-gty-mi', [
            'skList' => SkGtyMi::with('guru')->orderBy('nomor_sk')->get(),
            'settings' => SchoolSetting::getAll(),
        ])->render();

        $this->assertStringContainsString($first->nomor_sk, $html);
        $this->assertStringContainsString($second->nomor_sk, $html);
        $this->assertSame(1, substr_count($html, 'class="page-break"'));
    }

    public function test_single_sk_pdf_has_no_trailing_page_break(): void
    {
        $sk = $this->createSk();

        $html = view('pdf.sk-gty-mi', [
            'skList' => collect([$sk->load('guru')]),
            'settings' => SchoolSetting::getAll(),
        ])->render();

        $this->assertStringContainsString($sk->nomor_sk, $html);
        $this->assertSame(0, substr_count($html, 'class="page-break"'));
    }

    private function createSk(array $overrides = []): SkGtyMi
    {
        $guruId = $overrides['guru_mi_id'] ?? $this->createGuru()->id;
        unset($overrides['guru_mi_id']);

        return SkGtyMi::create(array_merge([
            'guru_mi_id' => $guruId,
            'nomor_sk' => '0001/SK/GTY/I/2026',
            'tanggal_sk' => '2026-01-15',
            'tanggal_musyawarah' => '2026-01-10',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-02',
            'nuptk' => '1111111111111111',
            'pendidikan_terakhir' => 'S1',
            'jabatan' => 'Guru Kelas',
            'berlaku_mulai' => '2026-01-15',
            'berlaku_sampai' => '2028-01-14',
            'penandatangan_nama' => 'Ketua Yayasan',
            'penandatangan_jabatan' => 'Ketua Yayasan',
            'tempat_penetapan' => 'Tangerang',
            'tanggal_penetapan' => '2026-01-15',
            'status' => 'aktif',
        ], $overrides));
    }

    private function createGuru(array $overrides = []): GuruMi
    {
        return GuruMi::create(array_merge([
            'nik' => '3671000000000001',
            'full_name' => 'Guru Pertama',
            'gender' => 'L',
            'nuptk' => '1111111111111111',
            'pob' => 'Jakarta',
            'dob' => '1990-01-02',
            'status_pegawai' => 'GTY',
            'is_active' => true,
        ], $overrides));
    }
}
