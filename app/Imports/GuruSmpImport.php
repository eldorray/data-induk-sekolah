<?php

namespace App\Imports;

use App\Models\GuruSmp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Carbon;

class GuruSmpImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        $data = $this->normalizeRow($row);
        
        if (empty($data['nik']) || empty($data['full_name'])) {
            return null;
        }

        if (GuruSmp::where('nik', $data['nik'])->exists()) {
            return null;
        }

        return new GuruSmp([
            'nip' => $data['nip'] ?? null,
            'nuptk' => $data['nuptk'] ?? null,
            'npk' => $data['npk'] ?? null,
            'nik' => $data['nik'],
            'front_title' => $data['front_title'] ?? null,
            'full_name' => $data['full_name'],
            'back_title' => $data['back_title'] ?? null,
            'gender' => strtoupper($data['gender'] ?? 'L'),
            'pob' => $data['pob'] ?? null,
            'dob' => $this->parseDate($data['dob'] ?? null),
            'phone_number' => $data['phone_number'] ?? null,
            'address' => $data['address'] ?? null,
            'status_pegawai' => strtoupper($data['status_pegawai'] ?? 'GTY'),
            'is_active' => true,
        ]);
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];
        
        $mapping = [
            'nip' => ['nip'],
            'nuptk' => ['nuptk'],
            'npk' => ['npk'],
            'nik' => ['nik', 'nik_', 'nik__', 'nik_wajib'],
            'front_title' => ['front_title', 'gelar_depan', 'gelar depan'],
            'full_name' => ['full_name', 'nama_lengkap', 'nama lengkap', 'nama_lengkap_', 'nama_lengkap__', 'nama_lengkap_wajib'],
            'back_title' => ['back_title', 'gelar_belakang', 'gelar belakang'],
            'gender' => ['gender', 'jenis_kelamin', 'jenis kelamin', 'jenis_kelamin_lp', 'jenis_kelamin___l_p_', 'jenis_kelamin__lp', 'jk'],
            'pob' => ['pob', 'tempat_lahir', 'tempat lahir'],
            'dob' => ['dob', 'tanggal_lahir', 'tanggal lahir', 'tanggal_lahir_yyyy_mm_dd', 'tanggal_lahir__yyyy_mm_dd_', 'tgl_lahir'],
            'phone_number' => ['phone_number', 'no_telepon', 'no telepon', 'no__telepon', 'telepon', 'hp'],
            'address' => ['address', 'alamat'],
            'status_pegawai' => ['status_pegawai', 'status pegawai', 'status', 'status_pegawai_pnsgttgty', 'status_pegawai__pns_gty_gtt_'],
        ];

        foreach ($mapping as $key => $possibleNames) {
            foreach ($possibleNames as $name) {
                $searchKeys = [
                    $name,
                    str_replace([' ', '.', '*', '(', ')', '/'], '_', strtolower($name)),
                    strtolower($name),
                ];
                
                foreach ($searchKeys as $searchKey) {
                    if (isset($row[$searchKey]) && $row[$searchKey] !== null && $row[$searchKey] !== '') {
                        $normalized[$key] = trim((string) $row[$searchKey]);
                        break 2;
                    }
                }
            }
        }

        return $normalized;
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->failures() as $failure) {
            $row = $failure->row();
            $errorMessages = $failure->errors();
            foreach ($errorMessages as $message) {
                $errors[] = "Baris {$row}: {$message}";
            }
        }
        return $errors;
    }
}
