<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class MutasiSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'siswa_type',
        'siswa_snapshot',
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
        'siswa_snapshot' => 'array',
    ];

    /** Field siswa yang tercetak di surat mutasi. */
    public const SNAPSHOT_FIELDS = ['nama_lengkap', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'tingkat_rombel'];

    /**
     * Relasi polymorphic ke Siswa (MI atau SMP)
     */
    public function siswa(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Identitas siswa untuk ditampilkan dan dicetak.
     *
     * Data siswa yang masih ada selalu menang supaya koreksi data ikut terbawa;
     * snapshot dipakai bila siswanya sudah dihapus. Mengembalikan null bila
     * keduanya tidak tersedia (baris lama sebelum kolom snapshot ada).
     *
     * @return array<string, mixed>|null
     */
    public function getSiswaDataAttribute(): ?array
    {
        $siswa = $this->siswa;

        if ($siswa !== null) {
            $data = [];
            foreach (self::SNAPSHOT_FIELDS as $field) {
                $data[$field] = $siswa->{$field};
            }

            return $data;
        }

        if (empty($this->siswa_snapshot)) {
            return null;
        }

        $data = array_merge(array_fill_keys(self::SNAPSHOT_FIELDS, null), $this->siswa_snapshot);
        $data['tanggal_lahir'] = $data['tanggal_lahir'] ? Carbon::parse($data['tanggal_lahir']) : null;

        return $data;
    }

    /** Rekam identitas siswa saat ini ke kolom snapshot. */
    public function captureSiswaSnapshot(): void
    {
        $siswa = $this->siswa;

        if ($siswa === null) {
            return;
        }

        $snapshot = [];
        foreach (self::SNAPSHOT_FIELDS as $field) {
            $snapshot[$field] = $field === 'tanggal_lahir' ? $siswa->tanggal_lahir?->format('Y-m-d') : $siswa->{$field};
        }

        $this->update(['siswa_snapshot' => $snapshot]);
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
        return match ($this->jenis_mutasi) {
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
        return match ($this->status) {
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
