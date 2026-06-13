<?php

namespace Tests\Unit;

use App\Models\Kuitansi;
use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use App\Services\LpjBosPdfMerger;
use App\Services\PdfCombiner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LpjBosPdfMergerTest extends TestCase
{
    use RefreshDatabase;

    private function makeLpj(): LpjBos
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
            'nama_kegiatan' => 'Pengadaan ATK',
            'tanggal_kegiatan' => '2026-03-02',
            'lokasi' => 'Madrasah',
        ]);
    }

    private function addAttachment(LpjBos $lpj, string $kategori, string $mime, int $sort): LpjBosAttachment
    {
        return LpjBosAttachment::create([
            'lpj_bos_id' => $lpj->id,
            'kategori' => $kategori,
            'file_path' => "lpj-bos/{$lpj->id}/{$kategori}/file{$sort}",
            'original_name' => "file{$sort}",
            'mime_type' => $mime,
            'file_size' => 1,
            'sort_order' => $sort,
        ]);
    }

    public function test_parts_are_ordered_kuitansi_then_foto_kwitansi_undangan_by_sort_order(): void
    {
        $lpj = $this->makeLpj();

        $this->addAttachment($lpj, 'undangan', 'application/pdf', 1);
        $this->addAttachment($lpj, 'foto', 'image/jpeg', 2);
        $this->addAttachment($lpj, 'foto', 'image/jpeg', 1);
        $this->addAttachment($lpj, 'kwitansi', 'application/pdf', 1);
        $this->addAttachment($lpj, 'kwitansi', 'image/jpeg', 2);

        $merger = new LpjBosPdfMerger(new PdfCombiner);
        $parts = $merger->buildParts($lpj->fresh());

        $this->assertSame('kuitansi', $parts[0]['type']);
        $this->assertNull($parts[0]['attachment']);

        $summary = array_map(function ($part) {
            return $part['attachment']
                ? $part['attachment']->kategori.':'.$part['attachment']->sort_order.':'.$part['type']
                : $part['type'];
        }, $parts);

        $this->assertSame([
            'kuitansi',
            'foto:1:image',
            'foto:2:image',
            'kwitansi:1:pdf',
            'kwitansi:2:image',
            'undangan:1:pdf',
        ], $summary);
    }

    public function test_parts_contain_only_kuitansi_when_no_attachments(): void
    {
        $lpj = $this->makeLpj();

        $merger = new LpjBosPdfMerger(new PdfCombiner);
        $parts = $merger->buildParts($lpj->fresh());

        $this->assertCount(1, $parts);
        $this->assertSame('kuitansi', $parts[0]['type']);
    }
}
