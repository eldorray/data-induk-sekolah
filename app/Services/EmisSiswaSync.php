<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Cocokkan data siswa (MI/SMP) dengan file "Daftar Siswa" unduhan EMIS
 * (satu sheet per rombel + sheet "Tidak Aktif", baris 1 = judul kolom).
 *
 * EMIS = sumber kebenaran: nilai EMIS menimpa data induk, KECUALI sel EMIS
 * kosong (data induk dipertahankan supaya tidak hilang).
 */
class EmisSiswaSync
{
    /** Kolom tabel siswa_mis / siswa_smps; judul kolom EMIS di-slug persis jadi nama ini. */
    public const FIELDS = [
        'nama_lengkap', 'nisn', 'nik', 'tempat_lahir', 'tanggal_lahir', 'tingkat_rombel',
        'status', 'jenis_kelamin', 'alamat', 'no_telepon', 'kebutuhan_khusus', 'disabilitas',
        'nomor_kip_pip', 'nama_ayah_kandung', 'nama_ibu_kandung', 'nama_wali',
    ];

    /** @return list<array<string, string>> */
    public function readFile(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);

        $rows = [];
        foreach ($reader->load($path)->getWorksheetIterator() as $sheet) {
            $cells = $sheet->toArray(null, true, false, false);
            $header = array_map(fn ($h): string => $this->slug((string) $h), $cells[0] ?? []);

            foreach (array_slice($cells, 1) as $cellRow) {
                $row = [];
                foreach ($header as $i => $key) {
                    if (in_array($key, self::FIELDS, true)) {
                        $row[$key] = $this->normalize($key, $cellRow[$i] ?? null);
                    }
                }
                if (($row['nama_lengkap'] ?? '') !== '') {
                    $rows[] = $row;
                }
            }
        }

        return $rows;
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  list<array<string, string>>  $emisRows
     * @return array{sama: int, beda: list<array{id: int, nama: string, perubahan: array<string, array{induk: string, emis: string}>}>, baru: list<array<string, string>>, tidak_di_emis: list<array{id: int, nama: string, tingkat_rombel: string}>, bentrok: list<array{nama: string, kolom: string, nilai: string, dipakai_oleh: string}>}
     */
    public function compare(string $modelClass, array $emisRows): array
    {
        $local = $modelClass::all();
        $byNisn = $this->index($local, 'nisn');
        $byNik = $this->index($local, 'nik');

        $hasil = ['sama' => 0, 'beda' => [], 'baru' => [], 'tidak_di_emis' => []];
        $cocok = [];

        foreach ($emisRows as $row) {
            $siswa = $byNisn->get($row['nisn'] ?? '') ?? $byNik->get($row['nik'] ?? '');
            if ($siswa === null) {
                $hasil['baru'][] = $row;

                continue;
            }

            $cocok[$siswa->id] = true;
            $perubahan = $this->diff($siswa, $row);
            if ($perubahan === []) {
                $hasil['sama']++;
            } else {
                $hasil['beda'][] = ['id' => $siswa->id, 'nama' => (string) $siswa->nama_lengkap, 'perubahan' => $perubahan];
            }
        }

        // ponytail: hanya tingkat yang ada di file — unduhan EMIS bisa per kelas, jangan anggap kelas lain hilang.
        $tingkatDiFile = collect($emisRows)->map(fn (array $r): ?int => $this->tingkat($r['tingkat_rombel'] ?? ''))->filter()->unique();
        foreach ($local as $siswa) {
            if (! isset($cocok[$siswa->id]) && $siswa->status === 'Aktif' && $tingkatDiFile->contains($this->tingkat((string) $siswa->tingkat_rombel))) {
                $hasil['tidak_di_emis'][] = ['id' => $siswa->id, 'nama' => (string) $siswa->nama_lengkap, 'tingkat_rombel' => (string) $siswa->tingkat_rombel];
            }
        }

        $hasil['bentrok'] = $this->bentrok($local, $hasil);

        return $hasil;
    }

    /**
     * Simulasikan apply() pada kolom unik (urutan sama: semua update, lalu semua tambah)
     * supaya NISN/NIK yang bentrok ketahuan sebelum database menolaknya.
     *
     * @param  Collection<int, Model>  $local
     * @param  array{beda: list<array{id: int, nama: string, perubahan: array<string, array{induk: string, emis: string}>}>, baru: list<array<string, string>>}  $hasil
     * @return list<array{nama: string, kolom: string, nilai: string, dipakai_oleh: string}>
     */
    private function bentrok(Collection $local, array $hasil): array
    {
        $pemilik = []; // "kolom:nilai" => [kunci pemilik, nama]
        foreach ($local as $s) {
            foreach (['nisn', 'nik'] as $kolom) {
                $nilai = $this->normalize($kolom, $s->{$kolom});
                if ($nilai !== '') {
                    $pemilik["$kolom:$nilai"] = [$s->id, (string) $s->nama_lengkap];
                }
            }
        }

        $bentrok = [];
        $klaim = function (int|string $kunci, string $nama, string $kolom, string $nilai, string $lama = '') use (&$pemilik, &$bentrok): void {
            if ($nilai === '') {
                return;
            }
            [$kunciPemilik, $namaPemilik] = $pemilik["$kolom:$nilai"] ?? [$kunci, ''];
            if ($kunciPemilik !== $kunci) {
                $bentrok[] = ['nama' => $nama, 'kolom' => $kolom, 'nilai' => $nilai, 'dipakai_oleh' => $namaPemilik];

                return;
            }
            unset($pemilik["$kolom:$lama"]);
            $pemilik["$kolom:$nilai"] = [$kunci, $nama];
        };

        foreach ($hasil['beda'] as $b) {
            foreach (['nisn', 'nik'] as $kolom) {
                if (isset($b['perubahan'][$kolom])) {
                    $klaim($b['id'], $b['nama'], $kolom, $b['perubahan'][$kolom]['emis'], $b['perubahan'][$kolom]['induk']);
                }
            }
        }
        foreach ($hasil['baru'] as $i => $row) {
            foreach (['nisn', 'nik'] as $kolom) {
                $klaim("baru-$i", $row['nama_lengkap'], $kolom, $row[$kolom] ?? '');
            }
        }

        return $bentrok;
    }

    /**
     * Terapkan hasil compare(): timpa kolom yang beda, tambah siswa baru.
     * Siswa yang tidak ada di EMIS TIDAK dihapus — diputuskan manual oleh admin.
     *
     * @param  class-string<Model>  $modelClass
     * @param  array{beda: list<array{id: int, perubahan: array<string, array{emis: string}>}>, baru: list<array<string, string>>}  $hasil
     * @return array{diperbarui: int, ditambah: int}
     */
    public function apply(string $modelClass, array $hasil): array
    {
        return DB::transaction(function () use ($modelClass, $hasil): array {
            foreach ($hasil['beda'] as $b) {
                $modelClass::findOrFail($b['id'])->update(array_map(fn (array $p): string => $p['emis'], $b['perubahan']));
            }
            foreach ($hasil['baru'] as $row) {
                $modelClass::create(array_map(fn (string $v): ?string => $v === '' ? null : $v, $row));
            }

            return ['diperbarui' => count($hasil['beda']), 'ditambah' => count($hasil['baru'])];
        });
    }

    /** @return array<string, array{induk: string, emis: string}> */
    private function diff(Model $siswa, array $row): array
    {
        $out = [];
        foreach (self::FIELDS as $field) {
            $emis = $row[$field] ?? '';
            if ($emis === '') {
                continue; // sel EMIS kosong: jangan hapus data induk
            }

            $raw = $field === 'tanggal_lahir' ? $siswa->tanggal_lahir?->format('Y-m-d') : $siswa->{$field};
            $induk = $this->normalize($field, $raw);
            if ($induk === $emis) {
                continue;
            }

            // EMIS hanya kenal "Tidak Aktif"; jangan timpa status data induk yang lebih spesifik (Lulus/Keluar/Pindah).
            if (in_array($field, ['status', 'tingkat_rombel'], true)
                && ($row['status'] ?? '') === 'Tidak Aktif'
                && ! in_array((string) $siswa->status, ['', 'Aktif'], true)) {
                continue;
            }

            $out[$field] = ['induk' => $induk, 'emis' => $emis];
        }

        return $out;
    }

    /** @return Collection<string, Model> */
    private function index(Collection $local, string $field): Collection
    {
        return $local
            ->mapWithKeys(fn (Model $s): array => [$this->normalize($field, $s->{$field}) => $s])
            ->forget('');
    }

    private function normalize(string $field, mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if ($field === 'tanggal_lahir') {
            try {
                return is_numeric($value)
                    ? Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->format('Y-m-d')
                    : Carbon::parse((string) $value)->format('Y-m-d');
            } catch (\Throwable) {
                return '';
            }
        }

        // EMIS menulis NIK sebagai teks berawalan petik ('3603...); ambil digitnya saja.
        if (in_array($field, ['nisn', 'nik'], true)) {
            return preg_replace('/\D/', '', (string) $value) ?? '';
        }

        $value = trim((string) preg_replace('/\s+/', ' ', ltrim((string) $value, "'")));

        if ($field === 'jenis_kelamin') {
            return match (strtoupper(substr($value, 0, 1))) {
                'L' => 'L',
                'P' => 'P',
                default => '',
            };
        }

        return $value;
    }

    private function slug(string $header): string
    {
        return trim((string) preg_replace('/[^a-z0-9]+/', '_', strtolower($header)), '_');
    }

    private function tingkat(string $rombel): ?int
    {
        return preg_match('/(\d+)/', $rombel, $m) ? (int) $m[1] : null;
    }
}
