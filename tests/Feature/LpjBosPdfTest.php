<?php

namespace Tests\Feature;

use App\Models\Kuitansi;
use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LpjBosPdfTest extends TestCase
{
    use RefreshDatabase;

    private function fakePdf(): \Barryvdh\DomPDF\PDF
    {
        $pdf = \Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdf->shouldReceive('setPaper')->andReturnSelf();
        $pdf->shouldReceive('stream')->andReturnUsing(fn ($filename) => response('PDF:'.$filename));

        return $pdf;
    }

    public function test_admin_can_print_single_lpj_pdf(): void
    {
        Pdf::shouldReceive('loadView')
            ->once()
            ->with('pdf.lpj-bos', \Mockery::on(fn ($data) => $data['lpj']->nama_kegiatan === 'Pengadaan ATK'))
            ->andReturn($this->fakePdf());

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj('Pengadaan ATK');

        $this->actingAs($admin)
            ->get(route('lpj-bos.print', $lpj->id))
            ->assertOk()
            ->assertSee('PDF:lpj-bos-');
    }

    public function test_admin_can_print_filtered_rekap_pdf(): void
    {
        Pdf::shouldReceive('loadView')
            ->once()
            ->with('pdf.lpj-bos-rekap', \Mockery::on(fn ($data) => $data['summary']['total_lpj'] === 1))
            ->andReturn($this->fakePdf());

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->createLpj('Pengadaan ATK');

        $this->actingAs($admin)
            ->get(route('lpj-bos.print-rekap', ['tahun' => '2026']))
            ->assertOk()
            ->assertSee('PDF:rekap-lpj-bos.pdf');
    }

    private function createLpj(string $namaKegiatan): LpjBos
    {
        $kuitansi = Kuitansi::create([
            'nomor_bukti' => '001',
            'tahun_anggaran' => '2026',
            'penerima' => 'Bendahara',
            'jumlah_uang' => 1500000,
            'uraian_pembayaran' => 'Pengadaan ATK',
            'tanggal_lunas' => '2026-03-01',
        ]);

        $lpj = LpjBos::create([
            'kuitansi_id' => $kuitansi->id,
            'nama_kegiatan' => $namaKegiatan,
            'tanggal_kegiatan' => '2026-03-02',
            'lokasi' => 'Madrasah',
        ]);

        LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => 'foto',
            'file_path' => 'lpj-bos/1/foto/foto.jpg',
            'original_name' => 'foto.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1000,
            'sort_order' => 1,
        ]);

        return $lpj;
    }
}
