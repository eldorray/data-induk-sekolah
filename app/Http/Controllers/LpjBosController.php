<?php

namespace App\Http\Controllers;

use App\Models\LpjBos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LpjBosController extends Controller
{
    public function printPdf(int $id)
    {
        $lpj = LpjBos::with(['kuitansi', 'attachments'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.lpj-bos', [
            'lpj' => $lpj,
            'imageAttachments' => $lpj->attachments->filter->is_image,
            'pdfAttachments' => $lpj->attachments->filter->is_pdf,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('lpj-bos-'.$lpj->kuitansi->nomor_bukti.'.pdf');
    }

    public function printRekap(Request $request)
    {
        $query = LpjBos::query()
            ->with(['kuitansi', 'attachments'])
            ->when($request->query('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kegiatan', 'like', '%'.$search.'%')
                        ->orWhereHas('kuitansi', function ($kuitansiQuery) use ($search) {
                            $kuitansiQuery->where('nomor_bukti', 'like', '%'.$search.'%')
                                ->orWhere('penerima', 'like', '%'.$search.'%')
                                ->orWhere('uraian_pembayaran', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($request->query('tahun'), fn ($query, $tahun) => $query->whereHas('kuitansi', fn ($q) => $q->where('tahun_anggaran', $tahun)))
            ->when($request->query('tanggal_awal'), fn ($query, $tanggal) => $query->whereDate('tanggal_kegiatan', '>=', $tanggal))
            ->when($request->query('tanggal_akhir'), fn ($query, $tanggal) => $query->whereDate('tanggal_kegiatan', '<=', $tanggal))
            ->orderBy('tanggal_kegiatan');

        $lpjs = $query->get();

        $kelengkapan = $request->query('kelengkapan');
        if ($kelengkapan === 'lengkap' || $kelengkapan === 'belum_lengkap') {
            $lpjs = $lpjs->filter(fn (LpjBos $lpj) => $kelengkapan === 'lengkap' ? $lpj->is_complete : ! $lpj->is_complete)->values();
        }

        $summary = [
            'total_lpj' => $lpjs->count(),
            'total_lengkap' => $lpjs->filter->is_complete->count(),
            'total_belum_lengkap' => $lpjs->reject->is_complete->count(),
            'total_nominal' => $lpjs->sum(fn (LpjBos $lpj) => $lpj->kuitansi->jumlah_uang),
        ];

        $pdf = Pdf::loadView('pdf.lpj-bos-rekap', [
            'lpjs' => $lpjs,
            'summary' => $summary,
            'filters' => $request->only(['search', 'tahun', 'tanggal_awal', 'tanggal_akhir', 'kelengkapan']),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('rekap-lpj-bos.pdf');
    }
}
