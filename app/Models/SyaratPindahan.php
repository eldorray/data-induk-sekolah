<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SyaratPindahan extends Model
{
    protected $table = 'syarat_pindahans';

    protected $fillable = [
        'syarat',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: hanya syarat yang aktif, diurutkan
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Ambil semua syarat aktif sebagai Collection (untuk PDF)
     */
    public static function getActiveList(): Collection
    {
        return static::active()->pluck('syarat');
    }

    /**
     * Hitung sort_order berikutnya untuk item baru
     */
    public static function nextSortOrder(): int
    {
        return ((int) static::max('sort_order')) + 1;
    }
}
