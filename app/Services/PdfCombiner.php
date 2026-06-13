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

        // Build a unique path directly (avoid tempnam(), which would leave an
        // orphaned extension-less file beside the .pdf we actually write to).
        $output = sys_get_temp_dir().'/lpjmerge_'.uniqid('', true).'.pdf';

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
