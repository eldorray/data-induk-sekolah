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
    ];

    protected $casts = [
        'tanggal_lunas' => 'date',
        'jumlah_uang' => 'integer',
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
