<?php

namespace App\Http\Controllers;

use App\Models\SuratTerimaPindahan;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuratTerimaPindahanController extends Controller
{
    /**
     * Generate PDF surat menerima siswa pindahan (single)
     */
    public function printPdf(int $id)
    {
        $surat = SuratTerimaPindahan::findOrFail($id);

        if ($surat->status !== 'disetujui') {
            abort(403, 'Surat belum disetujui');
        }

        $settings = SchoolSetting::getAll();

        $pdf = $this->buildPdf('pdf.surat-menerima-pindahan', [
            'surat' => $surat,
            'settings' => $settings,
        ]);

        $filename = 'surat-menerima-pindahan-' . str_replace('/', '-', $surat->nomor_surat) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Cetak banyak dokumen (selected atau semua yang disetujui) menjadi satu PDF
     */
    public function cetakDokumen(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'integer|exists:surat_terima_pindahans,id',
        ]);

        $query = SuratTerimaPindahan::query()
            ->where('status', 'disetujui')
            ->orderBy('tanggal_surat', 'asc')
            ->orderBy('id', 'asc');

        if ($request->filled('ids')) {
            $query->whereIn('id', $request->ids);
        }

        $surats = $query->get();

        if ($surats->isEmpty()) {
            return back()->with('error', 'Tidak ada surat yang disetujui untuk dicetak.');
        }

        $settings = SchoolSetting::getAll();

        $pdf = $this->buildPdf('pdf.surat-menerima-pindahan-all', [
            'surats' => $surats,
            'settings' => $settings,
        ]);

        $filename = 'cetak-dokumen-menerima-siswa-pindahan-' . date('Ymd-His') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Build PDF dengan locale Indonesia (untuk tanggal dalam Bahasa Indonesia).
     * Locale di-scope hanya untuk method ini agar tidak mengganggu modul lain.
     */
    private function buildPdf(string $view, array $data)
    {
        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('id');

        try {
            $pdf = Pdf::loadView($view, $data);
            $pdf->setPaper([0, 0, 612, 936], 'portrait');
            return $pdf;
        } finally {
            Carbon::setLocale($previousLocale);
        }
    }
}
