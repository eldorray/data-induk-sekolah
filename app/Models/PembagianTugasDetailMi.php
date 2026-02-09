<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembagianTugasDetailMi extends Model
{
    protected $table = 'pembagian_tugas_detail_mis';

    protected $fillable = [
        'sk_pembagian_tugas_mi_id',
        'guru_mi_id',
        'jabatan',
        'jenis_guru',
        'tugas_mengajar',
        'jumlah_jam',
        'sort_order',
    ];

    protected $casts = [
        'jumlah_jam' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Relasi ke SK Pembagian Tugas
     */
    public function skPembagianTugas(): BelongsTo
    {
        return $this->belongsTo(SkPembagianTugasMi::class, 'sk_pembagian_tugas_mi_id');
    }

    /**
     * Relasi ke Guru MI
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(GuruMi::class, 'guru_mi_id');
    }

    /**
     * Get jabatan options
     */
    public static function getJabatanOptions(): array
    {
        return [
            'Kepala Madrasah' => 'Kepala Madrasah',
            'Guru' => 'Guru',
            'Operator/Guru' => 'Operator/Guru',
            'TU' => 'TU',
        ];
    }

    /**
     * Get jenis guru options
     */
    public static function getJenisGuruOptions(): array
    {
        return [
            'Kamad' => 'Kamad',
            'Guru Kelas' => 'Guru Kelas',
            'Guru Bidang' => 'Guru Bidang',
            'Operator/Guru Bidang' => 'Operator/Guru Bidang',
            'TU' => 'TU',
        ];
    }
}
