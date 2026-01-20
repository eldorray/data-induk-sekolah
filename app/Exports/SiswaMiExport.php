<?php

namespace App\Exports;

use App\Models\SiswaMi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaMiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return SiswaMi::orderBy('nama_lengkap')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NISN',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Tingkat - Rombel',
            'Umur',
            'Status',
            'Jenis Kelamin',
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

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $siswa->nama_lengkap,
            $siswa->nisn,
            $siswa->nik,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir?->format('Y-m-d'),
            $siswa->tingkat_rombel,
            $siswa->umur,
            $siswa->status,
            $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan' : ''),
            $siswa->alamat,
            $siswa->no_telepon,
            $siswa->kebutuhan_khusus,
            $siswa->disabilitas,
            $siswa->nomor_kip_pip,
            $siswa->nama_ayah_kandung,
            $siswa->nama_ibu_kandung,
            $siswa->nama_wali,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
