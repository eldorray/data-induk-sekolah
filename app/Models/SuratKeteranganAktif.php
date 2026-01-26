<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeteranganAktif extends Model
{
    protected $fillable = [
        'siswa_id',
        'nomor_surat',
        'tanggal_surat',
        'keperluan',
        'tahun_pelajaran',
        'semester',
        'status',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    /**
     * Relasi ke Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Generate nomor surat otomatis
     * Format: [urut]/[KODE]/SK.AK/[bulan_romawi]/[tahun]
     */
    public static function generateNomorSurat(): string
    {
        $kodeSekolah = SchoolSetting::get('kode_surat', 'MIDH');
        $tahun = date('Y');
        $bulan = self::getBulanRomawi(date('n'));

        // Hitung urutan di bulan ini
        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', date('n'))
            ->count() + 1;

        return sprintf('%03d/%s/SK.AK/%s/%s', $count, $kodeSekolah, $bulan, $tahun);
    }

    /**
     * Convert bulan ke romawi
     */
    private static function getBulanRomawi(int $bulan): string
    {
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[$bulan - 1];
    }

    /**
     * Get Tahun Pelajaran saat ini
     */
    public static function getCurrentTahunPelajaran(): string
    {
        $bulan = (int) date('n');
        $tahun = (int) date('Y');

        // Jika bulan Juli - Desember, tahun pelajaran adalah tahun ini - tahun depan
        // Jika bulan Januari - Juni, tahun pelajaran adalah tahun lalu - tahun ini
        if ($bulan >= 7) {
            return $tahun . '/' . ($tahun + 1);
        } else {
            return ($tahun - 1) . '/' . $tahun;
        }
    }

    /**
     * Get Semester saat ini
     */
    public static function getCurrentSemester(): string
    {
        $bulan = (int) date('n');
        // Juli - Desember = Ganjil, Januari - Juni = Genap
        return $bulan >= 7 ? 'ganjil' : 'genap';
    }

    /**
     * Accessor untuk semester label
     */
    public function getSemesterLabelAttribute(): string
    {
        return match($this->semester) {
            'ganjil' => 'Ganjil',
            'genap' => 'Genap',
            default => $this->semester ?? '-',
        };
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'disetujui' => 'Disetujui',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
