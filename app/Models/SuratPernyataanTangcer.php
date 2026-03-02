<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPernyataanTangcer extends Model
{
    protected $fillable = [
        'siswa_id',
        'siswa_type',
        'nomor_surat',
        'tahun_anggaran',
        'semester',
        'isi_surat',
        'isi_tujuan',
        'tanggal_surat',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    /**
     * Get the siswa (polymorphic manual)
     */
    public function siswa()
    {
        if ($this->siswa_type === 'siswa_mi') {
            return $this->belongsTo(SiswaMi::class, 'siswa_id');
        }
        return $this->belongsTo(SiswaSmp::class, 'siswa_id');
    }

    /**
     * Accessor to get the siswa model
     */
    public function getSiswaModelAttribute()
    {
        $model = $this->siswa_type === 'siswa_mi' ? SiswaMi::class : SiswaSmp::class;
        return $model::find($this->siswa_id);
    }
}
