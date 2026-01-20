<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Guru extends Model
{
    protected $fillable = [
        'nip',
        'nuptk',
        'npk',
        'nik',
        'front_title',
        'full_name',
        'back_title',
        'gender',
        'pob',
        'dob',
        'phone_number',
        'address',
        'status_pegawai',
        'is_active',
        'sk_awal_path',
        'sk_akhir_path',
    ];

    protected $casts = [
        'dob' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get full name with titles
     */
    public function getFullNameWithTitleAttribute(): string
    {
        $parts = [];
        if ($this->front_title) {
            $parts[] = $this->front_title;
        }
        $parts[] = $this->full_name;
        if ($this->back_title) {
            $parts[] = $this->back_title;
        }
        return implode(' ', $parts);
    }

    /**
     * Calculate age from DOB
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->dob) {
            return null;
        }
        return Carbon::parse($this->dob)->age;
    }

    /**
     * Get status pegawai label
     */
    public function getStatusPegawaiLabelAttribute(): string
    {
        return match($this->status_pegawai) {
            'PNS' => 'PNS',
            'GTY' => 'Guru Tetap Yayasan',
            'GTT' => 'Guru Tidak Tetap',
            default => $this->status_pegawai,
        };
    }

    /**
     * Scope for active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive teachers
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
