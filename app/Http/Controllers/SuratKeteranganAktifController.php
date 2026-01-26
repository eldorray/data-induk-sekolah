<?php

namespace App\Http\Controllers;

use App\Models\SuratKeteranganAktif;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratKeteranganAktifController extends Controller
{
    /**
     * Generate PDF surat keterangan aktif
     */
    public function printPdf(int $id)
    {
        $surat = SuratKeteranganAktif::with('siswa')->findOrFail($id);

        // Cek status harus disetujui
        if ($surat->status !== 'disetujui') {
            abort(403, 'Surat keterangan aktif belum disetujui');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.surat-keterangan-aktif', [
            'surat' => $surat,
            'settings' => $settings,
        ]);

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-keterangan-aktif-' . str_replace('/', '-', $surat->nomor_surat) . '.pdf';

        return $pdf->stream($filename);
    }
}
