<?php

namespace App\Http\Controllers;

use App\Models\SuratPernyataanTangcer;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratPernyataanTangcerController extends Controller
{
    /**
     * Generate PDF surat pernyataan tangcer
     */
    public function printPdf(int $id)
    {
        $surat = SuratPernyataanTangcer::findOrFail($id);
        $siswa = $surat->siswa_model;

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.surat-pernyataan-tangcer', [
            'surat' => $surat,
            'siswa' => $siswa,
            'settings' => $settings,
        ]);

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-pernyataan-tangcer-' . str_replace(' ', '-', strtolower($siswa->nama_lengkap)) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Export all surat pernyataan tangcer as a single merged PDF
     */
    public function exportAllPdf(Request $request)
    {
        $surats = SuratPernyataanTangcer::query()
            ->when($request->tahun_anggaran, fn($q) => $q->where('tahun_anggaran', $request->tahun_anggaran))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($surats->isEmpty()) {
            return back()->with('error', 'Tidak ada surat untuk diekspor.');
        }

        $settings = SchoolSetting::getAll();

        $items = $surats->map(function ($surat) {
            return [
                'surat' => $surat,
                'siswa' => $surat->siswa_model,
            ];
        })->filter(fn($item) => $item['siswa'] !== null);

        $pdf = Pdf::loadView('pdf.surat-pernyataan-tangcer-all', [
            'items' => $items,
            'settings' => $settings,
        ]);

        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->stream('surat-pernyataan-tangcer-all.pdf');
    }
}
