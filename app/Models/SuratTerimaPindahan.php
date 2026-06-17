<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTerimaPindahan extends Model
{
    protected $table = 'surat_terima_pindahans';

    protected $fillable = [
        'nama_siswa',
        'tempat_lahir',
        'tanggal_lahir',
        'kelas',
        'jenis_kelamin',
        'asal_sekolah',
        'nama_orang_tua',
        'alamat_rumah',
        'nomor_surat',
        'tanggal_surat',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_surat' => 'date',
    ];

    /**
     * Generate nomor surat otomatis
     * Format: [urut]/[KODE]/SK.TP/[bulan_romawi]/[tahun]
     */
    public static function generateNomorSurat(): string
    {
        $kodeSekolah = SchoolSetting::get('kode_surat', 'MIDH');
        $tahun = date('Y');
        $bulan = self::getBulanRomawi(date('n'));

        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', date('n'))
            ->count() + 1;

        return sprintf('%03d/%s/SK.TP/%s/%s', $count, $kodeSekolah, $bulan, $tahun);
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
