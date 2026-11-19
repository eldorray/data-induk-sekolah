<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaSmp extends Model
{
    use HasFactory;

    protected $table = 'siswa_smps';

    protected $fillable = [
        'nama_lengkap',
        'nisn',
        'nomor_induk',
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
     * Nomor induk untuk ditampilkan/dicetak. SMP bukan madrasah, jadi tanpa prefix NSM.
     */
    public function getNomorIndukLengkapAttribute(): ?string
    {
        if (! $this->nomor_induk) {
            return null;
        }

        return $this->nomor_induk;
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
            // Form mengirim '' bukan null; '' kedua akan melanggar unique index
            if ($siswa->nomor_induk === '') {
                $siswa->nomor_induk = null;
            }

            if ($siswa->jenis_kelamin === '') {
                $siswa->jenis_kelamin = null;
            }
        });
    }
}
