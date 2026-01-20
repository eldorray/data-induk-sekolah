<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        // Example data row
        return [
            [
                'Ahmad Fauzi',
                '1234567890',
                '3201234567890001',
                'Jakarta',
                '2015-05-15',
                'Kelas 1 - KELAS 1A',
                'Aktif',
                'L',
                'Jl. Contoh No. 123, Jakarta',
                '081234567890',
                '',
                '',
                '',
                'Budi Santoso',
                'Siti Aminah',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap *',
            'NISN',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Tingkat - Rombel',
            'Status (Aktif/Tidak Aktif/Lulus/Pindah/Keluar)',
            'Jenis Kelamin (L/P)',
            'Alamat',
            'No Telepon',
            'Kebutuhan Khusus',
            'Disabilitas',
            'Nomor KIP/PIP',
            'Nama Ayah Kandung',
            'Nama Ibu Kandung',
            'Nama Wali',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
    }
}
