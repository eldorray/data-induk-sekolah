<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratUniversal extends Model
{
    protected $fillable = [
        'jenis',
        'judul',
        'nomor_surat',
        'tanggal_surat',
        'jenjang',
        'kop_path',
        'isi',
        'tempat',
        'signers',
        'ttd_atas',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'signers' => 'array',
    ];

    public const JENJANG_OPTIONS = ['MI', 'SMP', 'RA/TK', 'MTs', 'MA', 'Lainnya'];

    /**
     * Generate nomor surat: [urut]/[KODE]/SU/[bulan_romawi]/[tahun]
     */
    public static function generateNomorSurat(): string
    {
        $kode = SchoolSetting::get('kode_surat', 'MIDH');
        $tahun = date('Y');
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulan = $romawi[(int) date('n') - 1];

        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', date('n'))
            ->count() + 1;

        return sprintf('%03d/%s/SU/%s/%s', $count, $kode, $bulan, $tahun);
    }
}
