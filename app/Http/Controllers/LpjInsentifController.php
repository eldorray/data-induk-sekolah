<?php

namespace App\Http\Controllers;

use App\Models\LpjInsentif;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LpjInsentifController extends Controller
{
    /**
     * Selalu muat dalam 1 halaman: coba skala dari terbesar ke terkecil
     * dan pakai skala pertama yang hasil render-nya cuma 1 halaman.
     */
    private function buildSinglePagePdf(LpjInsentif $lpj, array $settings, string $paper, string $orientation)
    {
        $scales = [1, 0.95, 0.9, 0.85, 0.8, 0.75, 0.7, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4];

        $lastPdf = null;

        foreach ($scales as $scale) {
            $pdf = Pdf::loadView('pdf.lpj-insentif', [
                'lpj' => $lpj,
                'settings' => $settings,
                'scale' => $scale,
            ])->setPaper($paper, $orientation);

            $dompdf = $pdf->getDomPDF();
            $dompdf->render();

            $lastPdf = $pdf;

            if ($dompdf->getCanvas()->get_page_count() <= 1) {
                return $pdf;
            }
        }

        // Tidak pernah muat 1 halaman — kembalikan skala terkecil.
        return $lastPdf;
    }

    public function printPdf(Request $request, int $id)
    {
        $lpj = LpjInsentif::with('penerimas')->findOrFail($id);

        $settings = SchoolSetting::pluck('value', 'key')->toArray();

        // Opsi kertas via query param: ?paper=a4|f4|letter|legal, ?orientation=landscape|portrait
        $paper = strtolower($request->query('paper', 'a4'));
        $orientation = strtolower($request->query('orientation', 'landscape'));

        if (! in_array($paper, ['a4', 'a3', 'letter', 'legal', 'f4', 'folio'], true)) {
            $paper = 'a4';
        }
        if (! in_array($orientation, ['landscape', 'portrait'], true)) {
            $orientation = 'landscape';
        }

        $pdf = $this->buildSinglePagePdf($lpj, $settings, $paper, $orientation);

        return $pdf->stream('lpj-insentif-'.str($lpj->periode)->slug().'-'.$lpj->tahun.'.pdf');
    }
}
