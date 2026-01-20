<?php

namespace App\Http\Controllers;

use App\Models\MutasiSiswa;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MutasiSiswaController extends Controller
{
    /**
     * Generate PDF surat mutasi
     */
    public function printPdf(int $id)
    {
        $mutasi = MutasiSiswa::with('siswa')->findOrFail($id);
        
        // Cek status harus disetujui
        if ($mutasi->status !== 'disetujui') {
            abort(403, 'Surat mutasi belum disetujui');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.surat-mutasi', [
            'mutasi' => $mutasi,
            'settings' => $settings,
        ]);

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-mutasi-' . str_replace('/', '-', $mutasi->nomor_surat) . '.pdf';
        
        return $pdf->stream($filename);
    }
}
