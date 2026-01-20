<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;

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
        });
    }
}
