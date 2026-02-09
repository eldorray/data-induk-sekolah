<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkPembagianTugasMi extends Model
{
    protected $table = 'sk_pembagian_tugas_mis';

    protected $fillable = [
        'nomor_sk',
        'tanggal_sk',
        'tahun_pelajaran',
        'semester',
        'penandatangan_nama',
        'penandatangan_nip',
        'penandatangan_jabatan',
        'tempat_penetapan',
        'tanggal_penetapan',
        'status',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'tanggal_penetapan' => 'date',
    ];

    /**
     * Relasi ke Detail Pembagian Tugas
     */
    public function details(): HasMany
    {
        return $this->hasMany(PembagianTugasDetailMi::class, 'sk_pembagian_tugas_mi_id')
            ->orderBy('sort_order');
    }

    /**
     * Generate nomor SK otomatis
     */
    public static function generateNomorSk(): string
    {
        $year = date('Y');
        $month = date('m');
        $romanMonth = self::getRomanMonth((int) $month);

        $kode = SchoolSetting::get('kode_surat', 'MIDH');

        $lastSk = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastSk) {
            preg_match('/^(\d+)/', $lastSk->nomor_sk, $matches);
            $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;
        }

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "{$newNumber}/{$kode}/SK-PTM/{$romanMonth}/{$year}";
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
     * Get current tahun pelajaran
     */
    public static function getCurrentTahunPelajaran(): string
    {
        $year = (int) date('Y');
        $month = (int) date('m');

        // Jika bulan Juli atau setelahnya, tahun pelajaran dimulai tahun ini
        if ($month >= 7) {
            return $year . '/' . ($year + 1);
        }

        // Jika sebelum Juli, masih tahun pelajaran sebelumnya
        return ($year - 1) . '/' . $year;
    }

    /**
     * Get current semester
     */
    public static function getCurrentSemester(): string
    {
        $month = (int) date('m');

        // Semester 1: Juli - Desember
        // Semester 2: Januari - Juni
        return ($month >= 7 || $month <= 12) && $month >= 7 ? '1' : '2';
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
