<?php

namespace App\Http\Controllers;

use App\Models\SkGtyMi;
use App\Models\SkTugasTambahanMi;
use App\Models\SkPembagianTugasMi;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SkGuruMiController extends Controller
{
    /**
     * Generate PDF SK GTY
     */
    public function printSkGty(int $id)
    {
        $sk = SkGtyMi::with('guru')->findOrFail($id);

        // Cek status harus aktif
        if ($sk->status !== 'aktif') {
            abort(403, 'SK GTY belum diaktifkan');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.sk-gty-mi', [
            'sk' => $sk,
            'settings' => $settings,
        ]);

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'sk-gty-' . str_replace('/', '-', $sk->nomor_sk) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Generate PDF SK Tugas Tambahan
     */
    public function printSkTugasTambahan(int $id)
    {
        $sk = SkTugasTambahanMi::with('guru')->findOrFail($id);

        // Cek status harus aktif
        if ($sk->status !== 'aktif') {
            abort(403, 'SK Tugas Tambahan belum diaktifkan');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.sk-tugas-tambahan-mi', [
            'sk' => $sk,
            'settings' => $settings,
        ]);

        // F4 paper size
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'sk-tugas-tambahan-' . str_replace('/', '-', $sk->nomor_sk) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Generate PDF SK Pembagian Tugas Mengajar
     */
    public function printSkPembagianTugas(int $id)
    {
        $sk = SkPembagianTugasMi::with(['details.guru'])->findOrFail($id);

        // Cek status harus aktif
        if ($sk->status !== 'aktif') {
            abort(403, 'SK Pembagian Tugas belum diaktifkan');
        }

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.sk-pembagian-tugas-mi', [
            'sk' => $sk,
            'settings' => $settings,
        ]);

        // F4 paper size
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'sk-pembagian-tugas-' . str_replace('/', '-', $sk->nomor_sk) . '.pdf';

        return $pdf->stream($filename);
    }
}
