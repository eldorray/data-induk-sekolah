<?php

namespace App\Models;

use App\Services\NilaiIjazahCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NilaiIjazahScore extends Model
{
    use HasFactory;

    protected $table = 'nilai_ijazah_scores';

    protected $fillable = [
        'nilai_ijazah_tahun_ajaran_id',
        'siswa_id',
        'siswa_type',
        'mapel_id',
        'mapel_type',
        'kelas_4_semester_1',
        'kelas_4_semester_2',
        'kelas_5_semester_1',
        'kelas_5_semester_2',
        'kelas_6_semester_1',
        'nilai_um',
    ];

    protected $casts = [
        'kelas_4_semester_1' => 'decimal:2',
        'kelas_4_semester_2' => 'decimal:2',
        'kelas_5_semester_1' => 'decimal:2',
        'kelas_5_semester_2' => 'decimal:2',
        'kelas_6_semester_1' => 'decimal:2',
        'nilai_um' => 'decimal:2',
    ];

    protected $appends = [
        'rata_rata_raport',
        'nilai_ijazah',
        'is_complete',
    ];

    /**
     * Relasi ke tahun ajaran.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(
            NilaiIjazahTahunAjaran::class,
            'nilai_ijazah_tahun_ajaran_id'
        );
    }

    /**
     * Relasi polymorphic ke Siswa (SiswaMi / SiswaSmp).
     */
    public function siswa(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relasi polymorphic ke Mapel (MapelMi / MapelSmp).
     */
    public function mapel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Accessor rata-rata nilai raport kelas 4, 5, 6 semester 1.
     * Null jika ada komponen yang belum diisi.
     */
    public function getRataRataRaportAttribute(): ?float
    {
        return app(NilaiIjazahCalculator::class)->rataRataRaport([
            $this->kelas_4_semester_1,
            $this->kelas_4_semester_2,
            $this->kelas_5_semester_1,
            $this->kelas_5_semester_2,
            $this->kelas_6_semester_1,
        ]);
    }

    /**
     * Accessor nilai ijazah final. Null jika salah satu komponen belum lengkap.
     */
    public function getNilaiIjazahAttribute(): ?float
    {
        return app(NilaiIjazahCalculator::class)->nilaiIjazah(
            $this->rata_rata_raport,
            $this->nilai_um !== null ? (float) $this->nilai_um : null,
        );
    }

    /**
     * Apakah data nilai sudah lengkap (5 komponen raport + UM).
     */
    public function getIsCompleteAttribute(): bool
    {
        return app(NilaiIjazahCalculator::class)->isComplete($this);
    }
}
