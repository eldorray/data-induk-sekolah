<?php

namespace App\Imports;

use App\Models\SiswaMi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Carbon;

class SiswaMiImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private int $rowCount = 0;

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nama_lengkap']) && empty($row['nama_lengkap_'])) {
            return null;
        }

        $this->rowCount++;

        // Handle different header formats
        $namaLengkap = $row['nama_lengkap'] ?? $row['nama_lengkap_'] ?? null;
        $tanggalLahir = $row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? null;
        $status = $row['status_aktiftidak_aktifluluskeluarpindah'] ?? $row['status'] ?? 'Aktif';
        $jenisKelamin = $row['jenis_kelamin_lp'] ?? $row['jenis_kelamin'] ?? null;

        // Parse date
        $parsedDate = null;
        if ($tanggalLahir) {
            try {
                if (is_numeric($tanggalLahir)) {
                    $parsedDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalLahir));
                } else {
                    $parsedDate = Carbon::parse($tanggalLahir);
                }
            } catch (\Exception $e) {
                $parsedDate = null;
            }
        }

        // Normalize jenis kelamin
        if ($jenisKelamin) {
            $jenisKelamin = strtoupper(trim($jenisKelamin));
            if (!in_array($jenisKelamin, ['L', 'P'])) {
                $jenisKelamin = null;
            }
        }

        return new SiswaMi([
            'nama_lengkap' => $namaLengkap,
            'nisn' => $row['nisn'] ?? null,
            'nik' => $row['nik'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $parsedDate,
            'tingkat_rombel' => $row['tingkat_rombel'] ?? $row['tingkat___rombel'] ?? null,
            'status' => $status ?: 'Aktif',
            'jenis_kelamin' => $jenisKelamin,
            'alamat' => $row['alamat'] ?? null,
            'no_telepon' => $row['no_telepon'] ?? null,
            'kebutuhan_khusus' => $row['kebutuhan_khusus'] ?? null,
            'disabilitas' => $row['disabilitas'] ?? null,
            'nomor_kip_pip' => $row['nomor_kippip'] ?? $row['nomor_kip_pip'] ?? null,
            'nama_ayah_kandung' => $row['nama_ayah_kandung'] ?? null,
            'nama_ibu_kandung' => $row['nama_ibu_kandung'] ?? null,
            'nama_wali' => $row['nama_wali'] ?? null,
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
