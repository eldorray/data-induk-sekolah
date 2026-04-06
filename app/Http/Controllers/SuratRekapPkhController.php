<?php

namespace App\Http\Controllers;

use App\Models\SuratRekapPkh;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratRekapPkhController extends Controller
{
    /**
     * Generate PDF surat rekap PKH
     */
    public function printPdf(int $id)
    {
        $surat = SuratRekapPkh::with('siswa')->findOrFail($id);

        // Cek status harus disetujui
        if ($surat->status !== 'disetujui') {
            abort(403, 'Surat rekap PKH belum disetujui');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.surat-rekap-pkh', [
            'surat' => $surat,
            'settings' => $settings,
        ]);

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-rekap-pkh-' . str_replace('/', '-', $surat->nomor_surat) . '.pdf';

        return $pdf->stream($filename);
    }
}
