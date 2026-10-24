<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class HariLibur extends Model
{
    protected $table = 'hari_liburs';

    protected $fillable = [
        'tanggal',
        'nama',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public static function namaBulan(int $bulan): string
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ][$bulan] ?? (string) $bulan;
    }

    public static function tahunAjaran(int $tahun, int $bulan): string
    {
        // Tahun ajaran baru mulai Juli
        return $bulan >= 7 ? $tahun.'/'.($tahun + 1) : ($tahun - 1).'/'.$tahun;
    }

    public static function jumlahHari(int $tahun, int $bulan): int
    {
        return Carbon::create($tahun, $bulan, 1)->daysInMonth;
    }

    /**
     * Tanggal-tanggal yang diarsir abu-abu: hari Minggu + hari libur
     * yang tercatat pada bulan tersebut.
     *
     * @return array<int, string>  key = tanggal (1..31), value = alasan
     */
    public static function hariArsir(int $tahun, int $bulan): array
    {
        $jumlah = self::jumlahHari($tahun, $bulan);

        $libur = self::query()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $hasil = [];

        for ($tanggal = 1; $tanggal <= $jumlah; $tanggal++) {
            $date = Carbon::create($tahun, $bulan, $tanggal);

            if ($date->isSunday()) {
                $hasil[$tanggal] = 'Minggu';
            }
        }

        foreach ($libur as $h) {
            $hasil[$h->tanggal->day] = $h->nama;
        }

        ksort($hasil);

        return $hasil;
    }
}
