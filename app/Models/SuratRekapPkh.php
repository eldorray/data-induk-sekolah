<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SuratRekapPkh extends Model
{
    protected $fillable = [
        'siswa_id',
        'siswa_type',
        'nomor_surat',
        'tanggal_surat',
        'tahun_ajaran',
        'semester',
        'format_surat',
        'bulan_rekap',
        'data_absensi',
        'nama_wali_kelas',
        'nip_wali_kelas',
        'status',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'format_surat' => 'array',
        'bulan_rekap' => 'array',
        'data_absensi' => 'array',
    ];

    /**
     * Relasi polymorphic ke Siswa (MI atau SMP)
     */
    public function siswa(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Generate nomor surat otomatis
     */
    public static function generateNomorSurat(): string
    {
        $kodeSekolah = SchoolSetting::get('kode_surat', 'MIDH');
        $tahun = date('Y');
        $bulan = self::getBulanRomawi(date('n'));

        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', date('n'))
            ->count() + 1;

        return sprintf('%03d/%s/RP/%s/%s', $count, $kodeSekolah, $bulan, $tahun);
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
     * Hitung total absensi dari semua bulan
     */
    public function getTotalAbsensiAttribute(): array
    {
        $total = ['sakit' => 0, 'izin' => 0, 'alfa' => 0];
        $data = $this->data_absensi ?? [];

        foreach ($data as $bulan => $absensi) {
            $total['sakit'] += (int) ($absensi['sakit'] ?? 0);
            $total['izin'] += (int) ($absensi['izin'] ?? 0);
            $total['alfa'] += (int) ($absensi['alfa'] ?? 0);
        }

        return $total;
    }

    /**
     * Get current tahun ajaran
     */
    public static function getCurrentTahunAjaran(): string
    {
        $bulan = (int) date('n');
        $tahun = (int) date('Y');

        if ($bulan >= 7) {
            return $tahun . '/' . ($tahun + 1);
        }
        return ($tahun - 1) . '/' . $tahun;
    }

    /**
     * Get current semester
     */
    public static function getCurrentSemester(): string
    {
        $bulan = (int) date('n');
        return ($bulan >= 7 && $bulan <= 12) ? 'ganjil' : 'genap';
    }

    /**
     * Daftar bulan yang tersedia
     */
    public static function getAvailableBulan(): array
    {
        return [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];
    }
}
