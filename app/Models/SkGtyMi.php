<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkGtyMi extends Model
{
    protected $table = 'sk_gty_mis';

    protected $fillable = [
        'guru_mi_id',
        'nomor_sk',
        'tanggal_sk',
        'tempat_lahir',
        'tanggal_lahir',
        'nuptk',
        'pendidikan_terakhir',
        'jabatan',
        'berlaku_mulai',
        'berlaku_sampai',
        'penandatangan_nama',
        'penandatangan_jabatan',
        'tempat_penetapan',
        'tanggal_penetapan',
        'status',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'tanggal_lahir' => 'date',
        'berlaku_mulai' => 'date',
        'berlaku_sampai' => 'date',
        'tanggal_penetapan' => 'date',
    ];

    /**
     * Relasi ke Guru MI
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(GuruMi::class, 'guru_mi_id');
    }

    /**
     * Generate nomor SK otomatis
     */
    public static function generateNomorSk(): string
    {
        $year = date('Y');
        $month = date('m');
        $romanMonth = self::getRomanMonth((int) $month);

        $lastSk = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastSk) {
            preg_match('/^(\d+)/', $lastSk->nomor_sk, $matches);
            $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;
        }

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$newNumber}/SK/GTY/{$romanMonth}/{$year}";
    }

    /**
     * Convert month to Roman numeral
     */
    public static function getRomanMonth(int $month): string
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        return $romans[$month] ?? 'I';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            default => $this->status,
        };
    }

    /**
     * Scope for active SK
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope for draft SK
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
