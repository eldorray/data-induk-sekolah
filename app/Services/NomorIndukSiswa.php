<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use Illuminate\Support\Facades\DB;
use OverflowException;

/**
 * Nomor induk siswa: 2 digit tahun masuk + 4 digit nomor urut (mis. 240001).
 * Bentuk ber-NSM tidak disimpan; dirakit saat tampil (lihat accessor nomor_induk_lengkap).
 */
final class NomorIndukSiswa
{
    private const URUT_MAKS = 9999;

    /**
     * Isi nomor induk siswa aktif di satu rombel yang belum punya nomor, urut abjad.
     * Nomor yang sudah ada tidak pernah ditimpa.
     *
     * @param  class-string<SiswaMi|SiswaSmp>  $model
     * @return int jumlah siswa yang diberi nomor
     */
    public static function generate(string $model, string $rombel, int $tahunMasuk): int
    {
        return DB::transaction(function () use ($model, $rombel, $tahunMasuk): int {
            $urut = self::urutTerakhir($model, $tahunMasuk);
            $siswas = self::tanpaNomor($model, $rombel)->orderBy('nama_lengkap')->orderBy('id')->get();

            foreach ($siswas as $siswa) {
                if (++$urut > self::URUT_MAKS) {
                    throw new OverflowException('Nomor urut tahun '.$tahunMasuk.' sudah habis (maksimal 9999).');
                }
                $siswa->nomor_induk = self::format($tahunMasuk, $urut);
                $siswa->save();
            }

            return $siswas->count();
        });
    }

    /**
     * @param  class-string<SiswaMi|SiswaSmp>  $model
     * @return array{jumlah:int, mulai:string, sampai:string}
     */
    public static function pratinjau(string $model, string $rombel, int $tahunMasuk): array
    {
        $jumlah = self::tanpaNomor($model, $rombel)->count();
        $urut = self::urutTerakhir($model, $tahunMasuk);

        return [
            'jumlah' => $jumlah,
            'mulai' => self::format($tahunMasuk, $urut + 1),
            'sampai' => self::format($tahunMasuk, $urut + max($jumlah, 1)),
        ];
    }

    /**
     * Tahun masuk bawaan: awal tahun ajaran berjalan (mulai Juli) dikurangi lama siswa bersekolah.
     * "Kelas 3 - KELAS 3A" di MI (tingkat awal 1) pada 2026/2027 berarti masuk 2024.
     */
    public static function tahunMasuk(string $rombel, int $tingkatAwal): int
    {
        $awalTahunAjaran = now()->month >= 7 ? now()->year : now()->year - 1;
        $tingkat = preg_match('/(\d+)/', $rombel, $m) ? (int) $m[1] : $tingkatAwal;

        return $awalTahunAjaran - max(0, $tingkat - $tingkatAwal);
    }

    private static function format(int $tahunMasuk, int $urut): string
    {
        return sprintf('%02d%04d', $tahunMasuk % 100, $urut);
    }

    /**
     * @param  class-string<SiswaMi|SiswaSmp>  $model
     */
    private static function urutTerakhir(string $model, int $tahunMasuk): int
    {
        // ponytail: max()+1, bukan counter. Kalau siswa bernomor tertinggi dihapus permanen,
        // nomornya bisa terpakai lagi; pindah ke counter di school_settings bila itu jadi masalah.
        $terakhir = $model::query()
            ->where('nomor_induk', 'like', sprintf('%02d', $tahunMasuk % 100).'____')
            ->max('nomor_induk');

        return (int) substr((string) $terakhir, 2);
    }

    /**
     * @param  class-string<SiswaMi|SiswaSmp>  $model
     * @return \Illuminate\Database\Eloquent\Builder<SiswaMi|SiswaSmp>
     */
    private static function tanpaNomor(string $model, string $rombel): \Illuminate\Database\Eloquent\Builder
    {
        return $model::query()
            ->where('tingkat_rombel', $rombel)
            ->where('status', 'Aktif')
            ->whereNull('nomor_induk');
    }
}
