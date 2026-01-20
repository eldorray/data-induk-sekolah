<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MapelMiTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['MP001', 'Matematika', 'Umum', '', 4],
            ['MP002', 'Bahasa Indonesia', 'Umum', '', 4],
            ['MP003', 'Al-Quran Hadits', 'PAI', '', 2],
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Mapel',
            'Nama Mapel',
            'Kelompok (PAI/Umum)',
            'Jurusan',
            'Jam/Minggu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
