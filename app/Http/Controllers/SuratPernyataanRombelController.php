<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratPernyataanRombelController extends Controller
{
    /**
     * Cetak Surat Pernyataan Rekapitulasi Rombongan Belajar MI.
     */
    public function printPdf()
    {
        $settings = SchoolSetting::getAll();
        $tanggal = $settings['rombel_tanggal_surat'] ?? date('Y-m-d');

        $pdf = Pdf::loadView('pdf.surat-pernyataan-rombel', [
            'settings' => $settings,
            'rekap' => SiswaMi::rekapRombel(),
            'nomorSurat' => $settings['rombel_nomor_surat'] ?? '-',
            'tanggalSurat' => Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y'),
            'tahunPelajaran' => $settings['rombel_tahun_pelajaran'] ?? '-',
            'namaMadrasah' => $settings['rombel_nama_madrasah'] ?? ($settings['kuitansi_nama_madrasah'] ?? ''),
            'namaKepala' => $settings['rombel_nama_kepala'] ?? ($settings['kuitansi_kepala_madrasah'] ?? ''),
            'kota' => $settings['rombel_kota'] ?? '',
            'namaPengawas' => $settings['rombel_nama_pengawas'] ?? '',
            'nipPengawas' => $settings['rombel_nip_pengawas'] ?? '',
        ]);

        // F4: 215.9mm x 330.2mm
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->stream('surat-pernyataan-rombel.pdf');
    }
}
