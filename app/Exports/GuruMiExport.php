<?php

namespace App\Exports;

use App\Models\GuruMi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruMiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return GuruMi::orderBy('full_name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'NUPTK',
            'NPK',
            'NIK',
            'Gelar Depan',
            'Nama Lengkap',
            'Gelar Belakang',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No. Telepon',
            'Alamat',
            'Status Pegawai',
            'Status Aktif',
        ];
    }

    public function map($guru): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $guru->nip,
            $guru->nuptk,
            $guru->npk,
            $guru->nik,
            $guru->front_title,
            $guru->full_name,
            $guru->back_title,
            $guru->gender === 'L' ? 'Laki-laki' : 'Perempuan',
            $guru->pob,
            $guru->dob?->format('Y-m-d'),
            $guru->phone_number,
            $guru->address,
            $guru->status_pegawai,
            $guru->is_active ? 'Aktif' : 'Tidak Aktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
