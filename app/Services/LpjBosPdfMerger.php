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
