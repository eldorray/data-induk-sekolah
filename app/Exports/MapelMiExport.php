<?php

namespace App\Exports;

use App\Models\MapelMi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MapelMiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return MapelMi::orderBy('nama_mapel')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Mapel',
            'Nama Mapel',
            'Kelompok',
            'Jurusan',
            'Jam/Minggu',
            'Status',
        ];
    }

    public function map($mapel): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $mapel->kode_mapel,
            $mapel->nama_mapel,
            $mapel->kelompok,
            $mapel->jurusan,
            $mapel->jam_per_minggu,
            $mapel->is_active ? 'Aktif' : 'Tidak Aktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
