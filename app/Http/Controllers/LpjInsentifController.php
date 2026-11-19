<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LpjInsentif;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LpjInsentifController extends Controller
{
    private const F4 = [0.0, 0.0, 609.45, 935.43];

    /**
     * Selalu muat dalam 1 halaman: coba skala dari terbesar ke terkecil
     * dan pakai skala pertama yang hasil render-nya cuma 1 halaman.
     *
     * @param  array<string, mixed>  $settings
     * @param  string|array<int, float>  $paper
     */
    private function buildSinglePagePdf(LpjInsentif $lpj, array $settings, string|array $paper, string $orientation): \Barryvdh\DomPDF\PDF
    {
        $scales = [1, 0.95, 0.9, 0.85, 0.8, 0.75, 0.7, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4];

        foreach ($scales as $scale) {
            $pdf = Pdf::loadView('pdf.lpj-insentif', [
                'lpj' => $lpj,
                'settings' => $settings,
                'scale' => $scale,
            ])->setPaper($paper, $orientation);

            $dompdf = $pdf->getDomPDF();
            $dompdf->render();

            if ($dompdf->getCanvas()->get_page_count() <= 1) {
                return $pdf;
            }
        }

        // Tidak pernah muat 1 halaman — kembalikan skala terkecil.
        return $pdf;
    }

    public function printPdf(Request $request, int $id): Response
    {
        $lpj = LpjInsentif::with('penerimas')->findOrFail($id);

        $settings = SchoolSetting::pluck('value', 'key')->toArray();

        // Default F4 (215 × 330 mm). Opsi via query: ?paper=f4|a4|a3|letter|legal|folio, ?orientation=landscape|portrait
        $paper = strtolower((string) $request->query('paper', 'f4'));
        $orientation = strtolower((string) $request->query('orientation', 'landscape'));

        if (! in_array($paper, ['a4', 'a3', 'letter', 'legal', 'f4', 'folio'], true)) {
            $paper = 'f4';
        }
        if (! in_array($orientation, ['landscape', 'portrait'], true)) {
            $orientation = 'landscape';
        }

        // 'f4' dompdf = 210 mm; F4 Indonesia = 215 × 330 mm (dalam pt).
        $paperSize = $paper === 'f4' ? self::F4 : $paper;

        $pdf = $this->buildSinglePagePdf($lpj, $settings, $paperSize, $orientation);

        return $pdf->stream('lpj-insentif-'.str($lpj->periode)->slug().'-'.$lpj->tahun.'.pdf');
    }
}
