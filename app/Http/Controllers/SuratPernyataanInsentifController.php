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
}
