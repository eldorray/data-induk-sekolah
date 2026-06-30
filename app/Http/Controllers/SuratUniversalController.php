<?php

namespace App\Http\Controllers;

use App\Models\SuratUniversal;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratUniversalController extends Controller
{
    public function printPdf(int $id)
    {
        $surat = SuratUniversal::findOrFail($id);
        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.surat-universal', [
            'surat' => $surat,
            'settings' => $settings,
        ]);

        // F4: 215.9mm x 330.2mm
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-' . str_replace('/', '-', $surat->nomor_surat) . '.pdf';

        return $pdf->stream($filename);
    }
}
