<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NilaiIjazahTahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'nilai_ijazah_tahun_ajarans';

    protected $fillable = [
        'nama_tahun_ajaran',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relasi ke nilai (scores) siswa per mapel pada tahun ajaran ini.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(NilaiIjazahScore::class, 'nilai_ijazah_tahun_ajaran_id');
    }

    /**
     * Scope: tahun ajaran aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Jumlah siswa unik yang sudah punya record nilai di tahun ajaran ini.
     */
    public function getJumlahSiswaAttribute(): int
    {
        return $this->scores()
            ->select('siswa_id', 'siswa_type')
            ->distinct()
            ->get()
            ->count();
    }

    /**
     * Label status aktif/nonaktif.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }
}
