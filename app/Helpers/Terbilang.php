<?php

namespace App\Helpers;

class Terbilang
{
    /**
     * Ubah angka rupiah menjadi terbilang Bahasa Indonesia.
     * Contoh: 47880000 => "Empat puluh tujuh juta delapan ratus delapan puluh ribu rupiah".
     *
     * Algoritma sudah diverifikasi terhadap kasus uji resmi (lihat TerbilangTest).
     */
    public static function make($n): string
    {
        $n = (int) floor(abs($n));

        $satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        $toWords = function ($x) use (&$toWords, $satuan) {
            if ($x < 12)                    $s = $satuan[$x];
            elseif ($x < 20)                $s = $toWords($x - 10) . ' belas';
            elseif ($x < 100)               $s = $toWords(intdiv($x, 10)) . ' puluh' . ($x % 10 ? ' ' . $toWords($x % 10) : '');
            elseif ($x < 200)               $s = 'seratus' . ($x - 100 ? ' ' . $toWords($x - 100) : '');
            elseif ($x < 1000)              $s = $toWords(intdiv($x, 100)) . ' ratus' . ($x % 100 ? ' ' . $toWords($x % 100) : '');
            elseif ($x < 2000)              $s = 'seribu' . ($x - 1000 ? ' ' . $toWords($x - 1000) : '');
            elseif ($x < 1000000)           $s = $toWords(intdiv($x, 1000)) . ' ribu' . ($x % 1000 ? ' ' . $toWords($x % 1000) : '');
            elseif ($x < 1000000000)        $s = $toWords(intdiv($x, 1000000)) . ' juta' . ($x % 1000000 ? ' ' . $toWords($x % 1000000) : '');
            elseif ($x < 1000000000000)     $s = $toWords(intdiv($x, 1000000000)) . ' miliar' . ($x % 1000000000 ? ' ' . $toWords($x % 1000000000) : '');
            else                            $s = $toWords(intdiv($x, 1000000000000)) . ' triliun' . ($x % 1000000000000 ? ' ' . $toWords($x % 1000000000000) : '');

            return trim($s);
        };

        if ($n === 0) {
            return 'Nol rupiah';
        }

        $w = preg_replace('/\s+/', ' ', trim($toWords($n)));

        return ucfirst($w) . ' rupiah';
    }
}
