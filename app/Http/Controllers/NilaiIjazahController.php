<?php

namespace App\Http\Controllers;

use App\Exports\NilaiIjazahRekapExport;
use App\Models\MapelMi;
use App\Models\NilaiIjazahScore;
use App\Models\NilaiIjazahTahunAjaran;
use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use App\Services\NilaiIjazahCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NilaiIjazahController extends Controller
{
    public function __construct(private NilaiIjazahCalculator $calculator) {}

    /**
     * Cetak cover depan ijazah (DUMMY).
     * Jika query `siswa` diisi maka cetak 1 siswa, selain itu semua siswa kelas 6 aktif.
     */
    public function printCover(NilaiIjazahTahunAjaran $tahunAjaran, Request $request)
    {
        $siswaId = $request->integer('siswa') ?: null;
        $siswas = $this->resolveSiswas($siswaId);

        $settings = SchoolSetting::getAll();

        $pdf = Pdf::loadView('pdf.nilai-ijazah-cover', [
            'tahunAjaran' => $tahunAjaran,
            'siswas' => $siswas,
            'settings' => $settings,
        ])->setPaper([0, 0, 609.45, 935.43], 'portrait'); // F4: 215mm x 330mm

        $filename = 'cover-ijazah-dummy-'.$tahunAjaran->nama_tahun_ajaran.'.pdf';
        $filename = str_replace('/', '-', $filename);

        return $pdf->stream($filename);
    }

    /**
     * Cetak halaman nilai ijazah.
     */
    public function printNilai(NilaiIjazahTahunAjaran $tahunAjaran, Request $request)
    {
        $siswaId = $request->integer('siswa') ?: null;
        $siswas = $this->resolveSiswas($siswaId);

        $settings = SchoolSetting::getAll();

        $mapels = MapelMi::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('nama_mapel')
            ->get();

        // Build struktur: $nilaiPerSiswa[$siswa->id] = collection of rows per mapel
        $scores = NilaiIjazahScore::where('nilai_ijazah_tahun_ajaran_id', $tahunAjaran->id)
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->where('siswa_type', 'siswa_mi')
            ->where('mapel_type', 'mapel_mi')
            ->get()
            ->groupBy('siswa_id');

        $pdf = Pdf::loadView('pdf.nilai-ijazah-nilai', [
            'tahunAjaran' => $tahunAjaran,
            'siswas' => $siswas,
            'mapels' => $mapels,
            'scoresGrouped' => $scores,
            'settings' => $settings,
            'calculator' => $this->calculator,
        ])->setPaper([0, 0, 609.45, 935.43], 'portrait'); // F4: 215mm x 330mm

        $filename = 'nilai-ijazah-'.$tahunAjaran->nama_tahun_ajaran.'.pdf';
        $filename = str_replace('/', '-', $filename);

        return $pdf->stream($filename);
    }

    /**
     * Resolve siswa yang akan dicetak: per-id kalau diberikan, jika tidak ambil
     * semua siswa MI aktif kelas 6.
     */
    private function resolveSiswas(?int $siswaId)
    {
        if ($siswaId) {
            $siswa = SiswaMi::findOrFail($siswaId);

            return collect([$siswa]);
        }

        return SiswaMi::query()
            ->where('status', 'Aktif')
            ->where(function ($query) {
                $query->where('tingkat_rombel', 'like', '%6%')
                    ->orWhere('tingkat_rombel', 'like', '%VI%');
            })
            ->orderBy('nama_lengkap')
            ->get();
    }

    /**
     * Download rekap nilai rata-rata (70% raport + 30% UM) sebagai Excel.
     */
    public function exportRekap(NilaiIjazahTahunAjaran $tahunAjaran)
    {
        $filename = 'rekap-nilai-ijazah-'.str_replace('/', '-', $tahunAjaran->nama_tahun_ajaran).'.xlsx';

        return Excel::download(new NilaiIjazahRekapExport($tahunAjaran), $filename);
    }
}
