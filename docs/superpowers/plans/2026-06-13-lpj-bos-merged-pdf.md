# LPJ BOS — Cetak PDF Gabungan Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Saat menekan Cetak pada sebuah LPJ BOS, hasilkan satu PDF gabungan berisi Kuitansi BOS resmi, lalu lampiran Foto, Kwitansi, dan Undangan — gambar dirender penuh dan file PDF digabung utuh via Ghostscript.

**Architecture:** Service `LpjBosPdfMerger` menyusun daftar bagian terurut (Kuitansi → Foto → Kwitansi → Undangan, per `sort_order`), merender tiap bagian non-PDF (kuitansi & gambar) menjadi PDF sementara via DomPDF, lalu menggabung semua (termasuk file PDF lampiran asli) memakai `PdfCombiner` yang membungkus Ghostscript. Controller men-stream hasilnya.

**Tech Stack:** Laravel 12, Livewire 4, DomPDF (`barryvdh/laravel-dompdf`), Ghostscript (`gs`), Symfony Process, PHPUnit.

---

## Implementation Notes

- Jalankan semua perintah dari root proyek `data-induk`.
- **Jangan** `git commit` kecuali pengguna memintanya. Tiap task punya langkah checkpoint, bukan commit otomatis.
- Spec yang disetujui: `docs/superpowers/specs/2026-06-13-lpj-bos-merged-pdf-design.md`.
- Ikuti gaya proyek: service di `app/Services`, controller di `app/Http/Controllers`, template PDF di `resources/views/pdf`.
- `php artisan test` punya ~25 kegagalan pre-existing dari Livewire Volt — bukan regresi.
- Catatan environment worktree (bila berlaku): copy `.env`, `composer install --ignore-platform-req=php`, build vite via `/opt/homebrew/bin/node node_modules/.bin/vite build`. DB test pakai sqlite `:memory:`.

## File Structure

### Create
- `app/Services/PdfCombiner.php` — pembungkus Ghostscript; `combine(array $pdfPaths): string`. Deteksi binari `gs`, jalankan merge, kembalikan biner. Titik isolasi shell-call (mudah di-mock).
- `app/Services/LpjBosPdfMerger.php` — orkestrasi: `buildParts(LpjBos): array` (urutan/klasifikasi) + `merge(LpjBos): string` (render bagian → temp → combine → cleanup).
- `resources/views/pdf/lpj-bos-attachment.blade.php` — render satu lampiran gambar penuh 1 halaman A4.
- `tests/Unit/LpjBosPdfMergerTest.php` — uji urutan & klasifikasi `buildParts` (tanpa `gs`).

### Modify
- `app/Http/Controllers/LpjBosController.php` — `printPdf` memakai `LpjBosPdfMerger`, stream hasil.
- `tests/Feature/LpjBosPdfTest.php` — ganti test cetak single agar mem-bind fake `PdfCombiner`; test rekap tetap.

### Delete
- `resources/views/pdf/lpj-bos.blade.php` — template lama (hanya mendaftar nama PDF) digantikan alur gabungan.

---

## Task 0: Prasyarat — Pasang Ghostscript

**Files:** tidak ada perubahan kode.

- [ ] **Step 1: Pasang Ghostscript (aksi pengguna)**

Jalankan di mesin pengembang/host:

```bash
brew install ghostscript
```

- [ ] **Step 2: Verifikasi `gs` tersedia**

Run:

```bash
which gs && gs --version
```

Expected: path binari (mis. `/opt/homebrew/bin/gs`) dan nomor versi (mis. `10.x`).

Catatan: Test otomatis **tidak** membutuhkan `gs` (combiner di-mock). `gs` hanya dibutuhkan saat cetak sungguhan di browser.

---

## Task 1: Buat `PdfCombiner` (pembungkus Ghostscript)

**Files:**
- Create: `app/Services/PdfCombiner.php`

- [ ] **Step 1: Implementasi `PdfCombiner`**

Create `app/Services/PdfCombiner.php`:

```php
<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\Process\Process;

class PdfCombiner
{
    /**
     * Gabungkan beberapa file PDF (sesuai urutan) menjadi satu, kembalikan biner hasil.
     *
     * @param  array<int, string>  $pdfPaths  Path absolut tiap file PDF sumber.
     */
    public function combine(array $pdfPaths): string
    {
        $pdfPaths = array_values(array_filter($pdfPaths, 'is_file'));

        if (empty($pdfPaths)) {
            throw new RuntimeException('Tidak ada dokumen untuk digabung.');
        }

        $binary = $this->resolveBinary();

        if ($binary === null) {
            throw new RuntimeException('Ghostscript belum terpasang. Jalankan: brew install ghostscript');
        }

        $output = tempnam(sys_get_temp_dir(), 'lpjmerge_').'.pdf';

        $process = new Process(array_merge([
            $binary,
            '-dBATCH',
            '-dNOPAUSE',
            '-dQUIET',
            '-dSAFER',
            '-sDEVICE=pdfwrite',
            '-sOutputFile='.$output,
        ], $pdfPaths));

        $process->setTimeout(120);
        $process->run();

        try {
            if (! $process->isSuccessful() || ! is_file($output)) {
                throw new RuntimeException('Gagal menggabung PDF dengan Ghostscript: '.$process->getErrorOutput());
            }

            return (string) file_get_contents($output);
        } finally {
            if (is_file($output)) {
                @unlink($output);
            }
        }
    }

    /**
     * Tentukan lokasi binari Ghostscript.
     */
    protected function resolveBinary(): ?string
    {
        $candidates = array_filter([
            env('LPJ_BOS_GS_BINARY'),
            '/opt/homebrew/bin/gs',
            '/usr/local/bin/gs',
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        // Fallback: cari `gs` di PATH.
        $which = new Process(['which', 'gs']);
        $which->run();

        if ($which->isSuccessful()) {
            $path = trim($which->getOutput());

            return $path !== '' ? $path : null;
        }

        return null;
    }
}
```

- [ ] **Step 2: Pastikan tidak ada error sintaks/autoload**

Run:

```bash
php artisan tinker --execute="echo class_exists(App\Services\PdfCombiner::class) ? 'ok' : 'missing';"
```

Expected:

```text
ok
```

- [ ] **Step 3: Checkpoint**

Run:

```bash
git --no-pager diff -- app/Services/PdfCombiner.php
```

Expected: hanya menampilkan file service baru.

---

## Task 2: Buat template gambar lampiran

**Files:**
- Create: `resources/views/pdf/lpj-bos-attachment.blade.php`

- [ ] **Step 1: Buat template**

Create `resources/views/pdf/lpj-bos-attachment.blade.php`:

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lampiran LPJ BOS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 16px; }
        h2 { margin: 0 0 4px; font-size: 14px; }
        .muted { color: #555; margin: 0 0 10px; }
        .attachment-image { width: 100%; max-height: 950px; object-fit: contain; }
    </style>
</head>
<body>
    <h2>{{ ucfirst($attachment->kategori) }} — {{ $attachment->original_name }}</h2>
    @if ($attachment->keterangan)
        <p class="muted">{{ $attachment->keterangan }}</p>
    @endif
    @php $absolutePath = storage_path('app/public/'.$attachment->file_path); @endphp
    @if (file_exists($absolutePath))
        <img src="{{ $absolutePath }}" class="attachment-image" alt="{{ $attachment->original_name }}">
    @else
        <p>File gambar tidak ditemukan di storage.</p>
    @endif
</body>
</html>
```

- [ ] **Step 2: Checkpoint**

Run:

```bash
git --no-pager diff -- resources/views/pdf/lpj-bos-attachment.blade.php
```

Expected: hanya menampilkan template baru.

---

## Task 3: Buat `LpjBosPdfMerger` + uji urutan

**Files:**
- Create: `app/Services/LpjBosPdfMerger.php`
- Test: `tests/Unit/LpjBosPdfMergerTest.php`

- [ ] **Step 1: Tulis test urutan/klasifikasi yang gagal**

Create `tests/Unit/LpjBosPdfMergerTest.php`:

```php
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

        // Sengaja ditambah acak; harus diurutkan per kategori lalu sort_order.
        $this->addAttachment($lpj, 'undangan', 'application/pdf', 1);
        $this->addAttachment($lpj, 'foto', 'image/jpeg', 2);
        $this->addAttachment($lpj, 'foto', 'image/jpeg', 1);
        $this->addAttachment($lpj, 'kwitansi', 'application/pdf', 1);
        $this->addAttachment($lpj, 'kwitansi', 'image/jpeg', 2);

        $merger = new LpjBosPdfMerger(new PdfCombiner());
        $parts = $merger->buildParts($lpj->fresh());

        // Bagian pertama selalu kuitansi.
        $this->assertSame('kuitansi', $parts[0]['type']);
        $this->assertNull($parts[0]['attachment']);

        // Sisanya: foto(sort1,image), foto(sort2,image), kwitansi(sort1,pdf), kwitansi(sort2,image), undangan(sort1,pdf)
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

        $merger = new LpjBosPdfMerger(new PdfCombiner());
        $parts = $merger->buildParts($lpj->fresh());

        $this->assertCount(1, $parts);
        $this->assertSame('kuitansi', $parts[0]['type']);
    }
}
```

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run:

```bash
php artisan test tests/Unit/LpjBosPdfMergerTest.php
```

Expected: FAIL karena `App\Services\LpjBosPdfMerger` belum ada.

- [ ] **Step 3: Implementasi `LpjBosPdfMerger`**

Create `app/Services/LpjBosPdfMerger.php`:

```php
<?php

namespace App\Services;

use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class LpjBosPdfMerger
{
    public function __construct(private PdfCombiner $combiner) {}

    /**
     * Susun daftar bagian terurut: Kuitansi → Foto → Kwitansi → Undangan (per sort_order).
     *
     * @return array<int, array{type: string, attachment: ?LpjBosAttachment}>
     */
    public function buildParts(LpjBos $lpj): array
    {
        $parts = [
            ['type' => 'kuitansi', 'attachment' => null],
        ];

        $categories = [
            LpjBosAttachment::CATEGORY_FOTO,
            LpjBosAttachment::CATEGORY_KWITANSI,
            LpjBosAttachment::CATEGORY_UNDANGAN,
        ];

        foreach ($categories as $category) {
            $attachments = $lpj->attachments()
                ->where('kategori', $category)
                ->orderBy('sort_order')
                ->get();

            foreach ($attachments as $attachment) {
                $parts[] = [
                    'type' => $attachment->is_pdf ? 'pdf' : 'image',
                    'attachment' => $attachment,
                ];
            }
        }

        return $parts;
    }

    /**
     * Hasilkan biner PDF gabungan untuk satu LPJ.
     */
    public function merge(LpjBos $lpj): string
    {
        $lpj->loadMissing(['kuitansi', 'attachments']);

        $tempDir = storage_path('app/lpj-bos-merge-tmp');
        File::ensureDirectoryExists($tempDir);

        $tempFiles = [];
        $orderedPaths = [];

        try {
            foreach ($this->buildParts($lpj) as $part) {
                $path = $this->renderPart($part, $lpj, $tempDir, $tempFiles);

                if ($path !== null) {
                    $orderedPaths[] = $path;
                }
            }

            return $this->combiner->combine($orderedPaths);
        } finally {
            foreach ($tempFiles as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Render satu bagian ke path PDF. Mengembalikan null jika bagian harus dilewati.
     *
     * @param  array{type: string, attachment: ?LpjBosAttachment}  $part
     * @param  array<int, string>  $tempFiles  Dikumpulkan untuk dibersihkan.
     */
    private function renderPart(array $part, LpjBos $lpj, string $tempDir, array &$tempFiles): ?string
    {
        if ($part['type'] === 'kuitansi') {
            $pdf = Pdf::loadView('pdf.kuitansi', [
                'kuitansis' => collect([$lpj->kuitansi]),
                'settings' => SchoolSetting::getAll(),
            ])->setPaper([0, 0, 612, 936], 'portrait');

            return $this->writeTemp($pdf->output(), $tempDir, $tempFiles);
        }

        $attachment = $part['attachment'];

        if ($part['type'] === 'pdf') {
            $source = storage_path('app/public/'.$attachment->file_path);

            return is_file($source) ? $source : null;
        }

        // type === 'image' — render via DomPDF (template menangani file hilang).
        $pdf = Pdf::loadView('pdf.lpj-bos-attachment', [
            'attachment' => $attachment,
        ])->setPaper('a4', 'portrait');

        return $this->writeTemp($pdf->output(), $tempDir, $tempFiles);
    }

    /**
     * Tulis biner PDF ke file sementara, catat untuk cleanup, kembalikan path.
     *
     * @param  array<int, string>  $tempFiles
     */
    private function writeTemp(string $contents, string $tempDir, array &$tempFiles): string
    {
        $path = $tempDir.'/'.uniqid('part_', true).'.pdf';
        file_put_contents($path, $contents);
        $tempFiles[] = $path;

        return $path;
    }
}
```

- [ ] **Step 4: Jalankan test untuk memastikan lulus**

Run:

```bash
php artisan test tests/Unit/LpjBosPdfMergerTest.php
```

Expected: PASS (2 test).

- [ ] **Step 5: Checkpoint**

Run:

```bash
git --no-pager diff -- app/Services/LpjBosPdfMerger.php tests/Unit/LpjBosPdfMergerTest.php
```

Expected: hanya service merger + test-nya.

---

## Task 4: Sambungkan controller + perbarui test cetak

**Files:**
- Modify: `app/Http/Controllers/LpjBosController.php`
- Modify: `tests/Feature/LpjBosPdfTest.php`
- Delete: `resources/views/pdf/lpj-bos.blade.php`

- [ ] **Step 1: Perbarui test cetak single agar mem-bind fake `PdfCombiner`**

Di `tests/Feature/LpjBosPdfTest.php`, ganti method `test_admin_can_print_single_lpj_pdf` menjadi:

```php
    public function test_admin_can_print_single_lpj_pdf(): void
    {
        $combiner = \Mockery::mock(\App\Services\PdfCombiner::class);
        $combiner->shouldReceive('combine')
            ->once()
            ->with(\Mockery::on(fn ($paths) => is_array($paths) && count($paths) >= 1))
            ->andReturn('%PDF-1.4 fake-merged');
        $this->app->instance(\App\Services\PdfCombiner::class, $combiner);

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lpj = $this->createLpj('Pengadaan ATK');

        $response = $this->actingAs($admin)->get(route('lpj-bos.print', $lpj->id));

        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('fake-merged', $response->getContent());
    }
```

Hapus method helper `fakePdf()` dan penggunaan `Pdf::shouldReceive('loadView')` HANYA di test single (test rekap tetap memakainya).

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run:

```bash
php artisan test tests/Feature/LpjBosPdfTest.php
```

Expected: FAIL pada `test_admin_can_print_single_lpj_pdf` karena controller masih memakai alur lama (`Pdf::loadView('pdf.lpj-bos')`).

- [ ] **Step 3: Perbarui `LpjBosController::printPdf`**

Di `app/Http/Controllers/LpjBosController.php`:

Ganti import `use Barryvdh\DomPDF\Facade\Pdf;` — tetap dibutuhkan oleh `printRekap`, jadi **biarkan**. Tambah import:

```php
use App\Services\LpjBosPdfMerger;
```

Ganti seluruh method `printPdf` menjadi:

```php
    public function printPdf(int $id, LpjBosPdfMerger $merger)
    {
        $lpj = LpjBos::with(['kuitansi', 'attachments'])->findOrFail($id);

        $content = $merger->merge($lpj);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="lpj-bos-'.$lpj->kuitansi->nomor_bukti.'.pdf"',
        ]);
    }
```

(`printRekap` tidak diubah.)

- [ ] **Step 4: Hapus template lama**

Run:

```bash
rm resources/views/pdf/lpj-bos.blade.php
```

- [ ] **Step 5: Jalankan test cetak**

Run:

```bash
php artisan test tests/Feature/LpjBosPdfTest.php
```

Expected: PASS (single via fake combiner; rekap via mock `Pdf::loadView`).

- [ ] **Step 6: Checkpoint**

Run:

```bash
git --no-pager status
git --no-pager diff -- app/Http/Controllers/LpjBosController.php tests/Feature/LpjBosPdfTest.php
```

Expected: perubahan controller + test; `resources/views/pdf/lpj-bos.blade.php` terhapus.

---

## Task 5: Validasi penuh

**Files:** tidak ada file baru kecuali perbaikan diperlukan.

- [ ] **Step 1: Jalankan semua test LPJ**

Run:

```bash
php artisan test tests/Feature/LpjBosModelTest.php tests/Unit/LpjBosImageCompressorTest.php tests/Feature/LpjBosManagementTest.php tests/Feature/LpjBosDetailTest.php tests/Feature/LpjBosPdfTest.php tests/Unit/LpjBosPdfMergerTest.php
```

Expected: PASS semua.

- [ ] **Step 2: Pint pada file berubah**

Run:

```bash
./vendor/bin/pint --dirty
```

Expected: `{"result":"pass"}` atau memformat hanya file PHP yang berubah.

- [ ] **Step 3: Verifikasi cetak sungguhan (manual, butuh `gs`)**

Dengan `gs` terpasang dan ada LPJ yang punya lampiran foto + minimal satu lampiran PDF:

1. Buka halaman detail LPJ di browser, klik **Cetak PDF**.
2. Pastikan PDF berisi, berurutan: halaman Kuitansi BOS → halaman Foto → halaman Kwitansi (gambar dirender / PDF utuh) → halaman Undangan.

Jika `gs` belum terpasang, halaman akan menampilkan pesan: *"Ghostscript belum terpasang. Jalankan: brew install ghostscript"*.

- [ ] **Step 4: Bersihkan cache view**

Run:

```bash
php artisan view:clear
```

---

## Spec Coverage Self-Review

- Satu PDF gabungan saat cetak: Task 3 (`merge`) + Task 4 (controller stream).
- Kuitansi BOS resmi sebagai halaman pertama: Task 3 `renderPart` tipe `kuitansi` (reuse `pdf.kuitansi`, F4).
- Urutan Kuitansi → Foto → Kwitansi → Undangan per `sort_order`: Task 3 `buildParts` + Task 3 test urutan.
- Gambar dirender penuh: Task 2 template + Task 3 tipe `image`.
- File PDF lampiran digabung utuh: Task 3 tipe `pdf` (pakai file asli) + Task 1 Ghostscript.
- Gabung andal via Ghostscript: Task 0 (pasang) + Task 1 (`PdfCombiner`).
- Pesan jelas jika `gs` tak ada: Task 1 `resolveBinary`/`combine`.
- File lampiran hilang dilewati: Task 3 `renderPart` (tipe `pdf` → null jika tidak ada).
- LPJ tanpa lampiran → hanya halaman kuitansi: Task 3 test kedua.
- Rekap PDF tidak berubah: Task 4 hanya ubah `printPdf`.
- Cleanup temp via finally: Task 3 `merge`.
- Test tanpa `gs`: Task 3 (mock combiner) + Task 4 (bind fake combiner).
