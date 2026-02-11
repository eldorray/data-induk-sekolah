<?php

namespace App\Http\Controllers;

use App\Models\SuratPernyataanInsentif;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPernyataanInsentifController extends Controller
{
    /**
     * Generate PDF surat pernyataan insentif
     */
    public function printPdf(int $id)
    {
        $surat = SuratPernyataanInsentif::findOrFail($id);
        $guru = $surat->guru_model;

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.surat-pernyataan-insentif', [
            'surat' => $surat,
            'guru' => $guru,
            'settings' => $settings,
        ]);

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-pernyataan-insentif-' . str_replace(' ', '-', strtolower($guru->full_name)) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Export all surat pernyataan insentif as a single merged PDF
     */
    public function exportAllPdf()
    {
        $surats = SuratPernyataanInsentif::orderBy('created_at', 'desc')->get();

        if ($surats->isEmpty()) {
            return back()->with('error', 'Tidak ada surat untuk diekspor.');
        }

        $settings = SchoolSetting::getAll();

        $items = $surats->map(function ($surat) {
            return [
                'surat' => $surat,
                'guru' => $surat->guru_model,
            ];
        })->filter(fn($item) => $item['guru'] !== null);

        $pdf = Pdf::loadView('pdf.surat-pernyataan-insentif-all', [
            'items' => $items,
            'settings' => $settings,
        ]);

        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->stream('surat-pernyataan-insentif-all.pdf');
    }
}
