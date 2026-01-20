<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                '198501012010011001',  // NIP
                '1234567890123456',    // NUPTK
                '123456',              // NPK
                '3201010101010001',    // NIK
                'Drs.',                // Gelar Depan
                'Ahmad Suparman',      // Nama Lengkap
                'M.Pd.',               // Gelar Belakang
                'L',                   // Jenis Kelamin (L/P)
                'Jakarta',             // Tempat Lahir
                '1985-01-01',          // Tanggal Lahir (YYYY-MM-DD)
                '081234567890',        // No. Telepon
                'Jl. Contoh No. 123',  // Alamat
                'PNS',                 // Status (PNS/GTY/GTT)
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NIP',
            'NUPTK',
            'NPK',
            'NIK *',
            'Gelar Depan',
            'Nama Lengkap *',
            'Gelar Belakang',
            'Jenis Kelamin * (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'No. Telepon',
            'Alamat',
            'Status Pegawai (PNS/GTY/GTT)',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
