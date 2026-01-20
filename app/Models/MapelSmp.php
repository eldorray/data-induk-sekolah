<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapelSmp extends Model
{
    protected $table = 'mapel_smps';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'jam_per_minggu',
        'kelompok',
        'jurusan',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_per_minggu' => 'integer',
    ];

    /**
     * Scope for active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive subjects
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }
}
