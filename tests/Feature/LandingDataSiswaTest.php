<?php

namespace Tests\Feature;

use App\Models\SchoolSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingDataSiswaTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_faq_diganti_menjadi_data_siswa(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('#data-siswa', false)
            ->assertDontSee('#faq', false)
            ->assertSee('Data Siswa');
    }

    public function test_section_data_siswa_meminta_pin_sebelum_menampilkan_apapun(): void
    {
        SchoolSetting::set('validasi_data_siswa_pin', '1234');

        $this->get('/')
            ->assertOk()
            ->assertSee('Masukkan PIN Akses');
    }

    public function test_section_tertutup_saat_pin_belum_disetel(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Fitur belum diaktifkan.');
    }
}
