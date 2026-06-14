<?php

namespace App\Models;

use App\Helpers\Terbilang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kuitansi extends Model
{
    protected $fillable = [
        'nomor_bukti',
        'tahun_anggaran',
        'penerima',
        'jumlah_uang',
        'uraian_pembayaran',
        'tanggal_lunas',
        'signed_file_path',
        'signed_original_name',
        'signed_mime_type',
        'signed_file_size',
        'signed_uploaded_at',
    ];

    protected $casts = [
        'tanggal_lunas' => 'date',
        'jumlah_uang' => 'integer',
        'signed_file_size' => 'integer',
        'signed_uploaded_at' => 'datetime',
    ];

    public function lpjBos(): HasOne
    {
        return $this->hasOne(LpjBos::class);
    }

    /**
     * Terbilang otomatis dari jumlah_uang (tidak pernah diinput manual).
     */
    public function getTerbilangAttribute(): string
    {
        return Terbilang::make($this->jumlah_uang ?? 0);
    }

    /**
     * Nomor bukti lengkap: gabungkan nomor urut dengan format tetap dari pengaturan.
     * Template ".../T1/MIDH/2026" — bagian "..." diganti nomor urut.
     */
    public function getNomorBuktiLengkapAttribute(): string
    {
        return self::formatNomorBukti($this->nomor_bukti);
    }

    /**
     * Jumlah uang terformat rupiah, mis. "Rp 47.880.000".
     */
    public function getJumlahFormatAttribute(): string
    {
        return self::rupiah($this->jumlah_uang ?? 0);
    }

    /**
     * Apakah kuitansi ini sudah punya file hasil tanda tangan yang diupload.
     */
    public function getHasSignedFileAttribute(): bool
    {
        return ! empty($this->signed_file_path);
    }

    /**
     * URL publik untuk file kuitansi yang sudah ditandatangani.
     */
    public function getSignedFileUrlAttribute(): ?string
    {
        if (! $this->signed_file_path) {
            return null;
        }

        return asset('storage/'.ltrim($this->signed_file_path, '/'));
    }

    /**
     * Apakah file yang diupload berupa gambar (untuk preview di UI).
     */
    public function getSignedFileIsImageAttribute(): bool
    {
        return $this->signed_mime_type && str_starts_with($this->signed_mime_type, 'image/');
    }

    /**
     * Apakah file yang diupload berupa PDF.
     */
    public function getSignedFileIsPdfAttribute(): bool
    {
        return $this->signed_mime_type === 'application/pdf';
    }

    /**
     * Ukuran file yang mudah dibaca (KB/MB).
     */
    public function getSignedFileSizeHumanAttribute(): string
    {
        $bytes = (int) $this->signed_file_size;

        if ($bytes <= 0) {
            return '-';
        }

        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / (1024 * 1024), 2).' MB';
        }

        return number_format($bytes / 1024, 1).' KB';
    }

    /**
     * Bangun nomor bukti lengkap dari nomor urut + template pengaturan.
     */
    public static function formatNomorBukti(?string $nomorUrut): string
    {
        $template = SchoolSetting::get('kuitansi_format_nomor', '.../T1/MIDH/2026');
        $nomorUrut = trim((string) $nomorUrut);

        return str_replace('...', $nomorUrut, $template);
    }

    /**
     * Format integer rupiah ke "Rp 47.880.000".
     */
    public static function rupiah($angka): string
    {
        return 'Rp '.number_format((int) $angka, 0, ',', '.');
    }
}
