<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruMiTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                '198501012010011001',
                '1234567890123456',
                '123456',
                '3201010101010001',
                'Drs.',
                'Ahmad Suparman',
                'M.Pd.',
                'L',
                'Jakarta',
                '1985-01-01',
                '081234567890',
                'Jl. Contoh No. 123',
                'PNS',
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
