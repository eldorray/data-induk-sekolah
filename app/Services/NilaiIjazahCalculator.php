<?php

namespace App\Services;

use App\Models\NilaiIjazahScore;

/**
 * Satu sumber kebenaran perhitungan nilai ijazah.
 *
 * Rumus:
 *   Rata-rata Raport = (K4S1 + K4S2 + K5S1 + K5S2 + K6S1) / 5
 *   Nilai Ijazah     = (Rata-rata Raport * 0.7) + (Nilai UM * 0.3)
 */
class NilaiIjazahCalculator
{
    /**
     * Hitung rata-rata nilai raport dari 5 komponen.
     * Return null jika ada komponen yang masih kosong.
     *
     * @param  array<int|string, mixed>  $nilaiRaport
     */
    public function rataRataRaport(array $nilaiRaport): ?float
    {
        $values = [];
        foreach ($nilaiRaport as $nilai) {
            if ($nilai === null || $nilai === '') {
                return null;
            }
            if (! is_numeric($nilai)) {
                return null;
            }
            $values[] = (float) $nilai;
        }

        if (count($values) === 0) {
            return null;
        }

        return round(array_sum($values) / count($values), 2);
    }

    /**
     * Hitung nilai ijazah dengan bobot 70% raport + 30% UM.
     * Return null jika salah satu komponen null.
     */
    public function nilaiIjazah(?float $rataRataRaport, ?float $nilaiUm): ?float
    {
        if ($rataRataRaport === null || $nilaiUm === null) {
            return null;
        }

        return round(($rataRataRaport * 0.7) + ($nilaiUm * 0.3), 2);
    }

    /**
     * Apakah sebuah record NilaiIjazahScore sudah lengkap (5 komponen + UM).
     */
    public function isComplete(NilaiIjazahScore $score): bool
    {
        $fields = [
            $score->kelas_4_semester_1,
            $score->kelas_4_semester_2,
            $score->kelas_5_semester_1,
            $score->kelas_5_semester_2,
            $score->kelas_6_semester_1,
            $score->nilai_um,
        ];

        foreach ($fields as $value) {
            if ($value === null || $value === '') {
                return false;
            }
        }

        return true;
    }
}
