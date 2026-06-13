<?php

namespace App\Http\Controllers;

use App\Models\Kuitansi;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KuitansiController extends Controller
{
    /**
     * Cetak satu kuitansi (2 rangkap dalam satu halaman A4).
     */
    public function printPdf(int $id)
    {
        $kuitansi = Kuitansi::findOrFail($id);

        return $this->render(collect([$kuitansi]), 'kuitansi-' . $kuitansi->nomor_bukti . '.pdf');
    }

    /**
     * Cetak beberapa kuitansi terpilih jadi satu dokumen.
     * Tiap kuitansi mulai di halaman baru, masing-masing 2 rangkap.
     */
    public function printSelected(Request $request)
    {
        $ids = array_filter(explode(',', (string) $request->query('ids')));

        abort_if(empty($ids), 404, 'Tidak ada kuitansi yang dipilih.');

        $kuitansis = Kuitansi::whereIn('id', $ids)
            ->orderBy('created_at')
            ->get();

        abort_if($kuitansis->isEmpty(), 404, 'Kuitansi tidak ditemukan.');

        return $this->render($kuitansis, 'kuitansi-terpilih.pdf');
    }

    private function render($kuitansis, string $filename)
    {
        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.kuitansi', [
            'kuitansis' => $kuitansis,
            'settings' => $settings,
        ]);

        // F4 / Folio: 215.9mm x 330.2mm (612 x 936 pt)
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->stream($filename);
    }
}
