<?php

namespace Tests\Unit;

use App\Helpers\Terbilang;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TerbilangTest extends TestCase
{
    #[DataProvider('angkaProvider')]
    public function test_terbilang_sesuai_kasus_uji_resmi(int $angka, string $harapan): void
    {
        $this->assertSame($harapan, Terbilang::make($angka));
    }

    public static function angkaProvider(): array
    {
        return [
            [47880000, 'Empat puluh tujuh juta delapan ratus delapan puluh ribu rupiah'],
            [15960000, 'Lima belas juta sembilan ratus enam puluh ribu rupiah'],
            [4500000, 'Empat juta lima ratus ribu rupiah'],
            [1500000, 'Satu juta lima ratus ribu rupiah'],
            [1500, 'Seribu lima ratus rupiah'],
        ];
    }

    public function test_nol_dan_negatif(): void
    {
        $this->assertSame('Nol rupiah', Terbilang::make(0));
        $this->assertSame('Seribu lima ratus rupiah', Terbilang::make(-1500));
    }
}
