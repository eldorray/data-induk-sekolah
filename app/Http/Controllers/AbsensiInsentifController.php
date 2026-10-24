<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use App\Models\LpjInsentif;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AbsensiInsentifController extends Controller
{
    public function printPdf(Request $request)
    {
        $validated = $request->validate([
            'lpj' => 'required|integer|exists:lpj_insentifs,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        $lpj = LpjInsentif::with('penerimas')->findOrFail($validated['lpj']);
        $bulan = (int) $validated['bulan'];
        $tahun = (int) $validated['tahun'];

        $settings = SchoolSetting::pluck('value', 'key')->toArray();

        $jumlahHari = HariLibur::jumlahHari($tahun, $bulan);
        $arsir = HariLibur::hariArsir($tahun, $bulan);

        $pdf = Pdf::loadView('pdf.absensi-insentif', [
            'lpj' => $lpj,
            'settings' => $settings,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlahHari' => $jumlahHari,
            'arsir' => $arsir,
            'namaBulan' => HariLibur::namaBulan($bulan),
            'tahunAjaran' => HariLibur::tahunAjaran($tahun, $bulan),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('absensi-insentif-'.HariLibur::namaBulan($bulan).'-'.$tahun.'.pdf');
    }
}
