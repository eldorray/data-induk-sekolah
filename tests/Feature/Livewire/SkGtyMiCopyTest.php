<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SkGtyMiManagement;
use App\Models\GuruMi;
use App\Models\SkGtyMi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SkGtyMiCopyTest extends TestCase
{
    use RefreshDatabase;

    public function test_copy_opens_an_unpersisted_form_with_only_template_fields(): void
    {
        $source = $this->createSourceSk();

        Livewire::test(SkGtyMiManagement::class)
            ->call('openCopyModal', $source->id)
            ->assertSet('showModal', true)
            ->assertSet('isEditing', false)
            ->assertSet('isCopying', true)
            ->assertSet('copiedFromNomorSk', $source->nomor_sk)
            ->assertSet('skId', null)
            ->assertSet('guru_mi_id', null)
            ->assertSet('selectedGuru', null)
            ->assertSet('searchGuru', '')
            ->assertSet('nomor_sk', '0002/SK/GTY/'.SkGtyMi::getRomanMonth((int) date('m')).'/'.date('Y'))
            ->assertSet('tanggal_sk', '2026-01-15')
            ->assertSet('tanggal_musyawarah', '2026-01-10')
            ->assertSet('tempat_lahir', null)
            ->assertSet('tanggal_lahir', null)
            ->assertSet('nuptk', null)
            ->assertSet('pendidikan_terakhir', 'S1')
            ->assertSet('jabatan', null)
            ->assertSet('berlaku_mulai', '2026-01-15')
            ->assertSet('berlaku_sampai', '2028-01-14')
            ->assertSet('penandatangan_nama', 'Ketua Lama')
            ->assertSet('penandatangan_jabatan', 'Ketua Yayasan')
            ->assertSet('tempat_penetapan', 'Tangerang')
            ->assertSet('tanggal_penetapan', '2026-01-15')
            ->assertSet('status', 'draft')
            ->assertSee('Copy SK GTY MI')
            ->assertSee('Salinan dari SK '.$source->nomor_sk)
            ->assertSee('Simpan sebagai SK Baru');

        $this->assertDatabaseCount('sk_gty_mis', 1);
    }

    public function test_selecting_a_new_guru_and_saving_creates_a_draft_without_changing_source(): void
    {
        $source = $this->createSourceSk();
        $sourceSnapshot = $source->getAttributes();
        $newGuru = $this->createGuru([
            'nik' => '3671000000000002',
            'full_name' => 'Guru Baru',
            'nuptk' => '2222222222222222',
            'pob' => 'Serang',
            'dob' => '1992-02-03',
        ]);

        Livewire::test(SkGtyMiManagement::class)
            ->call('openCopyModal', $source->id)
            ->call('selectGuru', $newGuru->id)
            ->assertSet('guru_mi_id', $newGuru->id)
            ->assertSet('selectedGuru.id', $newGuru->id)
            ->assertSet('tempat_lahir', 'Serang')
            ->assertSet('tanggal_lahir', '1992-02-03')
            ->assertSet('nuptk', '2222222222222222')
            ->assertSet('pendidikan_terakhir', 'S1')
            ->assertSet('jabatan', null)
            ->set('jabatan', 'Guru Kelas')
            ->set('status', 'aktif')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false)
            ->assertSet('isCopying', false)
            ->assertSet('copiedFromNomorSk', null);

        $this->assertDatabaseCount('sk_gty_mis', 2);
        $this->assertDatabaseHas('sk_gty_mis', [
            'guru_mi_id' => $newGuru->id,
            'jabatan' => 'Guru Kelas',
            'pendidikan_terakhir' => 'S1',
            'status' => 'draft',
        ]);

        $source->refresh();
        $refreshedSource = $source->getAttributes();
        ksort($sourceSnapshot);
        ksort($refreshedSource);
        $this->assertSame($sourceSnapshot, $refreshedSource);
    }

    public function test_duplicate_nomor_sk_is_rejected_when_copying(): void
    {
        $source = $this->createSourceSk();
        $newGuru = $this->createGuru([
            'nik' => '3671000000000002',
            'full_name' => 'Guru Baru',
        ]);

        Livewire::test(SkGtyMiManagement::class)
            ->call('openCopyModal', $source->id)
            ->call('selectGuru', $newGuru->id)
            ->set('nomor_sk', $source->nomor_sk)
            ->set('jabatan', 'Guru Kelas')
            ->call('save')
            ->assertHasErrors(['nomor_sk' => 'unique']);

        $this->assertDatabaseCount('sk_gty_mis', 1);
    }

    public function test_edit_can_keep_its_current_nomor_sk(): void
    {
        $source = $this->createSourceSk();

        Livewire::test(SkGtyMiManagement::class)
            ->call('openEditModal', $source->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('sk_gty_mis', 1);
    }

    public function test_closing_copy_does_not_persist_and_resets_copy_state_for_create_and_edit(): void
    {
        $source = $this->createSourceSk();

        Livewire::test(SkGtyMiManagement::class)
            ->call('openCopyModal', $source->id)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('isCopying', false)
            ->assertSet('copiedFromNomorSk', null)
            ->call('openCreateModal')
            ->assertSet('isCopying', false)
            ->assertSet('copiedFromNomorSk', null)
            ->call('closeModal')
            ->call('openEditModal', $source->id)
            ->assertSet('isEditing', true)
            ->assertSet('isCopying', false)
            ->assertSet('copiedFromNomorSk', null);

        $this->assertDatabaseCount('sk_gty_mis', 1);
    }

    public function test_copying_a_missing_source_throws_model_not_found_and_does_not_open_modal(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::test(SkGtyMiManagement::class)
            ->call('openCopyModal', 999999);
    }

    public function test_copy_action_is_rendered_for_every_status_with_accessible_labels(): void
    {
        foreach (['draft', 'aktif', 'tidak_aktif'] as $index => $status) {
            $guru = $this->createGuru([
                'nik' => '367100000000001'.($index + 1),
                'full_name' => 'Guru '.$status,
            ]);
            $this->createSourceSk($guru, [
                'nomor_sk' => '000'.($index + 1).'/SK/GTY/I/2026',
                'status' => $status,
            ]);
        }

        $component = Livewire::test(SkGtyMiManagement::class);

        foreach (SkGtyMi::all() as $sk) {
            $component
                ->assertSeeHtml('wire:click="openCopyModal('.$sk->id.')"')
                ->assertSeeHtml('title="Copy SK"')
                ->assertSeeHtml('aria-label="Copy SK '.$sk->nomor_sk.'"');
        }
    }

    private function createSourceSk(?GuruMi $guru = null, array $overrides = []): SkGtyMi
    {
        $guru ??= $this->createGuru();

        return SkGtyMi::create(array_merge([
            'guru_mi_id' => $guru->id,
            'nomor_sk' => '0001/SK/GTY/I/2026',
            'tanggal_sk' => '2026-01-15',
            'tanggal_musyawarah' => '2026-01-10',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-02',
            'nuptk' => '1111111111111111',
            'pendidikan_terakhir' => 'S1',
            'jabatan' => 'Guru Lama',
            'berlaku_mulai' => '2026-01-15',
            'berlaku_sampai' => '2028-01-14',
            'penandatangan_nama' => 'Ketua Lama',
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
            'full_name' => 'Guru Sumber',
            'gender' => 'L',
            'nuptk' => '1111111111111111',
            'pob' => 'Jakarta',
            'dob' => '1990-01-02',
            'status_pegawai' => 'GTY',
            'is_active' => true,
        ], $overrides));
    }
}
