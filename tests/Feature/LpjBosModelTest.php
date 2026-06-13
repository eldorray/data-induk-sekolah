<?php

namespace Tests\Feature;

use App\Models\Kuitansi;
use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LpjBosModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_kuitansi_has_one_lpj_bos(): void
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
            'nama_kegiatan' => 'Pengadaan ATK Semester 1',
            'tanggal_kegiatan' => '2026-03-02',
            'lokasi' => 'Madrasah',
            'catatan' => 'Pembelian alat tulis kantor.',
        ]);

        $this->assertTrue($kuitansi->lpjBos->is($lpj));
        $this->assertTrue($lpj->kuitansi->is($kuitansi));
    }

    public function test_lpj_has_many_attachments_and_completeness_is_computed(): void
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

        $this->assertFalse($lpj->fresh()->is_complete);
        $this->assertSame('Belum lengkap', $lpj->fresh()->completeness_label);

        LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => LpjBosAttachment::CATEGORY_FOTO,
            'file_path' => 'lpj-bos/1/foto/foto.jpg',
            'original_name' => 'foto.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1000,
            'sort_order' => 1,
        ]);

        $this->assertFalse($lpj->fresh()->is_complete);

        LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => LpjBosAttachment::CATEGORY_KWITANSI,
            'file_path' => 'lpj-bos/1/kwitansi/kwitansi.jpg',
            'original_name' => 'kwitansi.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1000,
            'sort_order' => 1,
        ]);

        $this->assertTrue($lpj->fresh()->is_complete);
        $this->assertSame('Lengkap', $lpj->fresh()->completeness_label);
        $this->assertSame(2, $lpj->fresh()->attachments()->count());
    }

    public function test_one_kuitansi_can_only_have_one_lpj(): void
    {
        $kuitansi = Kuitansi::create([
            'nomor_bukti' => '003',
            'tahun_anggaran' => '2026',
            'penerima' => 'Bendahara',
            'jumlah_uang' => 500000,
            'uraian_pembayaran' => 'Transport kegiatan',
            'tanggal_lunas' => '2026-05-01',
        ]);

        LpjBos::create([
            'kuitansi_id' => $kuitansi->id,
            'nama_kegiatan' => 'Kegiatan Pertama',
            'tanggal_kegiatan' => '2026-05-02',
            'lokasi' => 'Madrasah',
        ]);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        LpjBos::create([
            'kuitansi_id' => $kuitansi->id,
            'nama_kegiatan' => 'Kegiatan Kedua',
            'tanggal_kegiatan' => '2026-05-03',
            'lokasi' => 'Madrasah',
        ]);
    }
}
