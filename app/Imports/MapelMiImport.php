<?php

namespace App\Imports;

use App\Models\MapelMi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class MapelMiImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private int $rowCount = 0;

    public function model(array $row)
    {
        if (empty($row['nama_mapel']) && empty($row['nama_mapel_'])) {
            return null;
        }

        $this->rowCount++;

        $namaMapel = $row['nama_mapel'] ?? $row['nama_mapel_'] ?? null;
        $kelompok = $row['kelompok_paiumum'] ?? $row['kelompok'] ?? 'Umum';
        
        if (!in_array($kelompok, ['PAI', 'Umum'])) {
            $kelompok = 'Umum';
        }

        return new MapelMi([
            'kode_mapel' => $row['kode_mapel'] ?? null,
            'nama_mapel' => $namaMapel,
            'kelompok' => $kelompok,
            'jurusan' => $row['jurusan'] ?? null,
            'jam_per_minggu' => $row['jamminggu'] ?? $row['jam_per_minggu'] ?? 2,
            'is_active' => true,
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->failures() as $failure) {
            $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
        return $errors;
    }
}
