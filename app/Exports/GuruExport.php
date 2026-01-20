<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Guru::all();
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
            'Aktif',
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
            $guru->gender,
            $guru->pob,
            $guru->dob?->format('Y-m-d'),
            $guru->phone_number,
            $guru->address,
            $guru->status_pegawai,
            $guru->is_active ? 'Ya' : 'Tidak',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
