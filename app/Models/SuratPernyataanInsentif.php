<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPernyataanInsentif extends Model
{
    protected $fillable = [
        'guru_id',
        'guru_type',
        'jabatan',
        'unit_kerja',
        'alamat_unit_kerja',
        'sumber_insentif',
        'bulan_tahun',
        'tanggal_surat',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    /**
     * Get the guru (polymorphic manual)
     */
    public function guru()
    {
        if ($this->guru_type === 'guru_mi') {
            return $this->belongsTo(GuruMi::class, 'guru_id');
        }
        return $this->belongsTo(GuruSmp::class, 'guru_id');
    }

    /**
     * Accessor to get the guru model
     */
    public function getGuruModelAttribute()
    {
        $model = $this->guru_type === 'guru_mi' ? GuruMi::class : GuruSmp::class;
        return $model::find($this->guru_id);
    }
}
