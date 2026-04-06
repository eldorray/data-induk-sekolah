<?php

namespace App\Http\Controllers;

use App\Models\SuratRekapPkh;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratRekapPkhController extends Controller
{
    /**
     * Generate PDF surat rekap PKH (supports multiple format pages)
     */
    public function printPdf(int $id)
    {
        $surat = SuratRekapPkh::with('siswa')->findOrFail($id);

        // Cek status harus disetujui
        if ($surat->status !== 'disetujui') {
            abort(403, 'Surat rekap PKH belum disetujui');
        }

        $settings = SchoolSetting::getAll();

        // format_surat is now an array (e.g. ['rekap_absensi', 'surat_keterangan'])
        $formats = $surat->format_surat ?? ['rekap_absensi'];

        // Ensure it's an array (backward compatibility for old string values)
        if (is_string($formats)) {
            $formats = [$formats];
        }

        // Map format keys to view names
        $viewMap = [
            'rekap_absensi' => 'pdf.surat-rekap-pkh',
            'surat_keterangan' => 'pdf.surat-keterangan-pkh',
        ];

        $viewData = [
            'surat' => $surat,
            'settings' => $settings,
        ];

        // If only one format, use the standard approach
        if (count($formats) === 1) {
            $viewName = $viewMap[$formats[0]] ?? 'pdf.surat-rekap-pkh';
            $pdf = Pdf::loadView($viewName, $viewData);
        } else {
            // Multiple formats: render each view, extract <style> and <body>, combine
            $allStyles = '';
            $allBodies = '';

            foreach ($formats as $index => $format) {
                $viewName = $viewMap[$format] ?? 'pdf.surat-rekap-pkh';
                $html = view($viewName, $viewData)->render();

                // Extract <style> content
                if (preg_match('/<style>(.*?)<\/style>/s', $html, $styleMatch)) {
                    $allStyles .= "/* --- Style for {$format} --- */\n" . $styleMatch[1] . "\n";
                }

                // Extract <body> content
                if (preg_match('/<body>(.*?)<\/body>/s', $html, $bodyMatch)) {
                    $bodyContent = $bodyMatch[1];
                } else {
                    $bodyContent = $html;
                }

                if ($index > 0) {
                    $allBodies .= '<div style="page-break-before: always;"></div>';
                }

                $allBodies .= '<div class="page-' . $format . '">' . $bodyContent . '</div>';
            }

            $mergedHtml = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Rekap PKH</title>
    <style>
        @page { margin: 1.5cm 2cm; }
        ' . $allStyles . '
    </style>
</head>
<body>' . $allBodies . '</body>
</html>';

            $pdf = Pdf::loadHTML($mergedHtml);
        }

        // F4 paper size: 215.9mm x 330.2mm (8.5" x 13")
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'surat-rekap-pkh-' . str_replace('/', '-', $surat->nomor_surat) . '.pdf';

        return $pdf->stream($filename);
    }
}
