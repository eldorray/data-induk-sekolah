<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaMi extends Model
{
    use HasFactory;

    protected $table = 'siswa_mis';

    protected $fillable = [
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'tingkat_rombel',
        'umur',
        'status',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'kebutuhan_khusus',
        'disabilitas',
        'nomor_kip_pip',
        'nama_ayah_kandung',
        'nama_ibu_kandung',
        'nama_wali',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the calculated age from tanggal_lahir
     */
    public function getCalculatedUmurAttribute(): ?int
    {
        if ($this->tanggal_lahir) {
            return Carbon::parse($this->tanggal_lahir)->age;
        }

        return $this->umur;
    }

    /**
     * Boot method to auto-calculate umur on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($siswa) {
            if ($siswa->tanggal_lahir) {
                $siswa->umur = Carbon::parse($siswa->tanggal_lahir)->age;
            }

            // Kolom enum('L','P') tidak bisa menyimpan '' (strict mode); form kirim '' bukan null
            if ($siswa->jenis_kelamin === '') {
                $siswa->jenis_kelamin = null;
            }
        });
    }

    /**
     * Rekap jumlah rombel & siswa per tingkat untuk Surat Pernyataan Rombel.
     * tingkat_rombel disimpan sebagai "Kelas 1 - KELAS 1A".
     *
     * @return array{tingkat: array<string, array{rombel:int,l:int,p:int,total:int}>, total: array{rombel:int,l:int,p:int,total:int}}
     */
    public static function rekapRombel(): array
    {
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];

        $tingkat = [];
        foreach ($romawi as $label) {
            $tingkat[$label] = ['rombel' => 0, 'l' => 0, 'p' => 0, 'total' => 0];
        }

        $rombelSet = [];

        foreach (self::where('status', 'Aktif')->get(['tingkat_rombel', 'jenis_kelamin']) as $siswa) {
            if (! preg_match('/(\d+)/', (string) $siswa->tingkat_rombel, $m)) {
                continue;
            }
            $label = $romawi[(int) $m[1]] ?? null;
            if ($label === null) {
                continue;
            }

            $rombelSet[$label][trim((string) $siswa->tingkat_rombel)] = true;
            $tingkat[$label]['total']++;
            if ($siswa->jenis_kelamin === 'L') {
                $tingkat[$label]['l']++;
            } elseif ($siswa->jenis_kelamin === 'P') {
                $tingkat[$label]['p']++;
            }
        }

        $total = ['rombel' => 0, 'l' => 0, 'p' => 0, 'total' => 0];
        foreach ($tingkat as $label => $data) {
            $tingkat[$label]['rombel'] = count($rombelSet[$label] ?? []);
            foreach (['rombel', 'l', 'p', 'total'] as $k) {
                $total[$k] += $tingkat[$label][$k];
            }
        }

        return ['tingkat' => $tingkat, 'total' => $total];
    }
}
