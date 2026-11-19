<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\LpjInsentifDetail;
use App\Models\LpjInsentif;
use App\Models\LpjInsentifPenerima;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LpjInsentifTest extends TestCase
{
    use RefreshDatabase;

    private function createLpj(): LpjInsentif
    {
        return LpjInsentif::create([
            'periode' => 'Juli - September',
            'tahun' => '2026',
            'kota' => 'TANGERANG',
            'bulan_ttd' => 'September',
            'nama_kepala' => 'Kepala Uji',
            'jabatan_kepala' => 'KEPALA MADRASAH',
            'nama_bendahara' => 'Bendahara Uji',
            'jabatan_bendahara' => 'BENDAHARA SEKOLAH',
        ]);
    }

    /** @param array<string, mixed> $attrs */
    private function addPenerima(LpjInsentif $lpj, array $attrs = []): LpjInsentifPenerima
    {
        return $lpj->penerimas()->create(array_merge([
            'nama' => 'Penerima',
            'status_pegawai' => 'NON PNS',
            'jabatan' => 'GURU',
            'tarif' => 650000,
            'volume' => 3,
            'satuan' => 'BLN',
            'pph21' => 0,
        ], $attrs));
    }

    public function test_admin_can_add_edit_and_delete_penerima_manually(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj();

        $component = Livewire::actingAs($admin)
            ->test(LpjInsentifDetail::class, ['lpj' => $lpj])
            ->call('openCreateModal')
            ->set('nama', 'Humairotusy Syarifah')
            ->set('npwp', '632985479402000')
            ->set('status_pegawai', '')
            ->set('jabatan', 'Tenaga Administrasi')
            ->set('tarif', '650000')
            ->set('volume', '3')
            ->set('no_rekening', '0155028564100')
            ->call('save')
            ->assertHasNoErrors();

        $p = $lpj->penerimas()->sole();
        $this->assertSame('Humairotusy Syarifah', $p->nama);
        $this->assertNull($p->status_pegawai);
        $this->assertSame(1950000, $p->jumlah_uang);

        $component->call('openEditModal', $p->id)
            ->set('status_pegawai', 'PNS')
            ->set('pph21', '97500')
            ->call('save')
            ->assertHasNoErrors();

        $p->refresh();
        $this->assertSame('PNS', $p->status_pegawai);
        $this->assertSame(1852500, $p->jumlah_diterima);

        $component->call('openDeleteModal', $p->id)->call('delete');
        $this->assertSame(0, $lpj->penerimas()->count());
    }

    public function test_penerima_of_other_lpj_cannot_be_edited_through_this_lpj(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj();
        $other = $this->addPenerima($this->createLpj(), ['nama' => 'Milik LPJ Lain']);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::actingAs($admin)
            ->test(LpjInsentifDetail::class, ['lpj' => $lpj])
            ->call('openEditModal', $other->id);
    }

    public function test_guru_cannot_open_detail_or_print(): void
    {
        $guru = User::factory()->create(['role' => User::ROLE_GURU]);
        $lpj = $this->createLpj();

        $this->actingAs($guru)->get(route('lpj-insentif.show', $lpj->id))->assertRedirect();
        $this->actingAs($guru)->get(route('lpj-insentif.print', $lpj->id))->assertRedirect();
    }

    public function test_admin_can_print_single_page_f4_landscape_pdf(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj();
        for ($i = 1; $i <= 21; $i++) {
            $this->addPenerima($lpj, ['nama' => "Penerima {$i}", 'npwp' => '3671105102950002', 'nip_pegid_nuptk' => '29557586603000001']);
        }

        $response = $this->actingAs($admin)->get(route('lpj-insentif.print', $lpj->id));

        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('content-type'));
        $pdf = (string) $response->getContent();
        $this->assertSame(1, preg_match_all('#/Type\s*/Page[^s]#', $pdf));
        $this->assertMatchesRegularExpression('#/MediaBox \[0\.0+ 0\.0+ 935\.43\d* 609\.45\d*\]#', $pdf);
    }

    public function test_print_view_matches_official_format(): void
    {
        $lpj = $this->createLpj();
        $this->addPenerima($lpj, ['nama' => 'Heni Susanti, S.Pd', 'status_pegawai' => 'PNS', 'pph21' => 97500]);
        $this->addPenerima($lpj, ['nama' => 'Patmawati, S.Pd.I']);
        $this->addPenerima($lpj, ['nama' => 'Humairotusy Syarifah', 'status_pegawai' => null]);

        $html = view('pdf.lpj-insentif', [
            'lpj' => $lpj->load('penerimas'),
            'settings' => ['nama_sekolah' => 'MI Daarul Hikmah', 'kecamatan' => 'Neglasari'],
        ])->render();

        $this->assertStringContainsString('<p>MI DAARUL HIKMAH</p>', $html);
        $this->assertStringContainsString('<p>KECAMATAN NEGLASARI</p>', $html);
        $this->assertStringContainsString('<p>BULAN JULI - SEPTEMBER 2026</p>', $html);
        $this->assertStringContainsString('97.500', $html);
        $this->assertStringContainsString('1.852.500', $html);
        $this->assertStringContainsString('1.950.000', $html);
        // Total: jumlah uang 3×1.950.000, diterima 5.850.000 − 97.500.
        $this->assertStringContainsString('5.850.000', $html);
        $this->assertStringContainsString('5.752.500', $html);
        // Tanda tangan zig-zag: ganjil kanan, genap kiri.
        $this->assertStringContainsString('<td class="r">1</td>', $html);
        $this->assertStringContainsString('<td class="l">2</td>', $html);
        $this->assertStringContainsString('<td class="r">3</td>', $html);
        $this->assertStringContainsString('Kepala Uji', $html);
        $this->assertStringContainsString('Bendahara Uji', $html);
    }
}
