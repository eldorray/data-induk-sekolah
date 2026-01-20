<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class MutasiSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_mutasi',
        'jenis_mutasi',
        'alasan_mutasi',
        'sekolah_tujuan',
        'npsn_tujuan',
        'alamat_tujuan',
        'status',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_mutasi' => 'date',
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
     * Format: [urut]/[KODE]/SK.PS/[bulan_romawi]/[tahun]
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
        
        return sprintf('%03d/%s/SK.PS/%s/%s', $count, $kodeSekolah, $bulan, $tahun);
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
     * Accessor untuk jenis mutasi label
     */
    public function getJenisMutasiLabelAttribute(): string
    {
        return match($this->jenis_mutasi) {
            'pindah' => 'Pindah Sekolah',
            'keluar' => 'Keluar/Berhenti',
            default => $this->jenis_mutasi,
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
