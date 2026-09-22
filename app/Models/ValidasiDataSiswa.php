<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidasiDataSiswa extends Model
{
    public const STATUS_BELUM = 'belum';

    public const STATUS_LENGKAP = 'lengkap';

    public const STATUS_ADA_CATATAN = 'ada_catatan';

    public const STATUS_LABEL = [
        self::STATUS_BELUM => 'Belum Dicek',
        self::STATUS_LENGKAP => 'Lengkap',
        self::STATUS_ADA_CATATAN => 'Ada Catatan',
    ];

    protected $fillable = [
        'sekolah',
        'tingkat_rombel',
        'status',
        'nama_pengisi',
        'catatan',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    /**
     * Daftar kelas (tingkat_rombel) yang punya siswa aktif pada sekolah tertentu.
     *
     * @return array<int, string>
     */
    public static function kelasAktif(string $sekolah): array
    {
        $model = $sekolah === 'SMP' ? SiswaSmp::class : SiswaMi::class;

        return $model::query()
            ->where('status', 'Aktif')
            ->whereNotNull('tingkat_rombel')
            ->where('tingkat_rombel', '!=', '')
            ->distinct()
            ->orderBy('tingkat_rombel')
            ->pluck('tingkat_rombel')
            ->all();
    }
}
