<?php

namespace App\Exports;

use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export satu kelas untuk wali kelas lewat halaman publik.
 *
 * NIK, no telepon, dan nomor KIP/PIP sengaja tidak ikut: file ini keluar lewat
 * halaman publik yang hanya dijaga PIN bersama, bukan akun per orang.
 */
class SiswaKelasPublikExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private string $sekolah,
        private string $tingkatRombel,
    ) {}

    public function collection(): Collection
    {
        $model = $this->sekolah === 'SMP' ? SiswaSmp::class : SiswaMi::class;

        return $model::query()
            ->where('status', 'Aktif')
            ->where('tingkat_rombel', $this->tingkatRombel)
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NISN',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Tingkat - Rombel',
            'Umur',
            'Jenis Kelamin',
            'Alamat',
            'Kebutuhan Khusus',
            'Disabilitas',
            'Nama Ayah Kandung',
            'Nama Ibu Kandung',
            'Nama Wali',
            'Status',
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
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir?->format('Y-m-d'),
            $siswa->tingkat_rombel,
            $siswa->umur,
            $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan' : ''),
            $siswa->alamat,
            $siswa->kebutuhan_khusus,
            $siswa->disabilitas,
            $siswa->nama_ayah_kandung,
            $siswa->nama_ibu_kandung,
            $siswa->nama_wali,
            $siswa->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
