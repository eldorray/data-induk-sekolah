<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\CocokkanEmisSiswa;
use App\Models\SiswaMi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class CocokkanEmisSiswaTest extends TestCase
{
    use RefreshDatabase;

    private const HEADER = ['No', 'Nama Lengkap', 'NISN', 'NIK', 'Tempat Lahir', 'Tanggal Lahir', 'Tingkat - Rombel', 'Umur',
        'Status', 'Jenis Kelamin', 'Alamat', 'No Telepon', 'Kebutuhan Khusus', 'Disabilitas', 'Nomor KIP/PIP',
        'Nama Ayah Kandung', 'Nama Ibu Kandung', 'Nama Wali'];

    public function test_halaman_hanya_untuk_admin(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]))
            ->get('/cocokkan-emis')->assertOk()->assertSee('Bandingkan');

        $this->actingAs(User::factory()->create(['role' => User::ROLE_GURU]))
            ->get('/cocokkan-emis')->assertRedirect(route('nilai-ijazah.index'));
    }

    public function test_bandingkan_lalu_terapkan_menyamakan_data_induk_dengan_emis(): void
    {
        $budi = SiswaMi::create(['nama_lengkap' => 'Budi', 'nisn' => '0011111111', 'nik' => '3603000000000001',
            'tingkat_rombel' => 'Kelas 4 - KELAS 4A', 'jenis_kelamin' => 'L', 'status' => 'Aktif', 'nama_ibu_kandung' => 'SITI']);
        SiswaMi::create(['nama_lengkap' => 'Ani', 'nik' => '3603000000000002',
            'tingkat_rombel' => 'Kelas 4 - KELAS 4B', 'jenis_kelamin' => 'P', 'status' => 'Aktif']);
        $hilang = SiswaMi::create(['nama_lengkap' => 'Citra', 'tingkat_rombel' => 'Kelas 4 - KELAS 4A', 'status' => 'Aktif']);
        $kelasLain = SiswaMi::create(['nama_lengkap' => 'Dodi', 'tingkat_rombel' => 'Kelas 5 - KELAS 5A', 'status' => 'Aktif']);
        $pindah = SiswaMi::create(['nama_lengkap' => 'Eka', 'nisn' => '0033333333',
            'tingkat_rombel' => 'Kelas 4 - KELAS 4A', 'status' => 'Pindah']);

        $file = $this->emisFile([
            'Kelas 4 - KELAS 4A' => [
                // nama beda; nama ibu kosong di EMIS -> data induk tetap
                ['BUDI SANTOSO', '0011111111', "'3603000000000001", 'Kelas 4 - KELAS 4A', 'Aktif', 'Laki-laki', ''],
                ['FAJAR', '0044444444', "'3603000000000004", 'Kelas 4 - KELAS 4A', 'Aktif', 'Laki-laki', 'NUR'],
            ],
            'Kelas 4 - KELAS 4B' => [
                ['Ani', '', "'3603000000000002", 'Kelas 4 - KELAS 4B', 'Aktif', 'Perempuan', ''], // cocok via NIK
            ],
            'Tidak Aktif' => [
                ['Eka', '0033333333', '', 'Kelas 4', 'Tidak Aktif', '', ''], // status Pindah tidak ditimpa
            ],
        ]);

        $component = Livewire::test(CocokkanEmisSiswa::class)
            ->set('file', $file)
            ->call('bandingkan')
            ->assertHasNoErrors();

        $hasil = $component->get('hasil');
        $this->assertSame(2, $hasil['sama']);
        $this->assertSame(['nama_lengkap' => ['induk' => 'Budi', 'emis' => 'BUDI SANTOSO']], $hasil['beda'][0]['perubahan']);
        $this->assertSame(['FAJAR'], array_column($hasil['baru'], 'nama_lengkap'));
        $this->assertSame([$hilang->id], array_column($hasil['tidak_di_emis'], 'id')); // Dodi kelas 5 tidak ikut

        $component->call('terapkan');

        $this->assertSame('BUDI SANTOSO', $budi->fresh()->nama_lengkap);
        $this->assertSame('SITI', $budi->fresh()->nama_ibu_kandung);
        $this->assertSame('Pindah', $pindah->fresh()->status);
        $this->assertNotNull($hilang->fresh());
        $this->assertNotNull($kelasLain->fresh());
        $fajar = SiswaMi::where('nisn', '0044444444')->firstOrFail();
        $this->assertSame(['3603000000000004', 'L', 'NUR'], [$fajar->nik, $fajar->jenis_kelamin, $fajar->nama_ibu_kandung]);
        $this->assertSame([], $component->get('hasil')['beda']);
    }

    /** @param array<string, list<array{0:string,1:string,2:string,3:string,4:string,5:string,6:string}>> $sheets */
    private function emisFile(array $sheets): UploadedFile
    {
        $book = new Spreadsheet;
        $book->removeSheetByIndex(0);
        foreach ($sheets as $title => $rows) {
            $ws = $book->createSheet()->setTitle($title);
            $data = [self::HEADER];
            foreach ($rows as $i => [$nama, $nisn, $nik, $rombel, $status, $jk, $ibu]) {
                $data[] = [$i + 1, $nama, $nisn, $nik, '', '', $rombel, '', $status, $jk, '', '', '', '', '', '', $ibu, ''];
            }
            $ws->fromArray($data, null, 'A1', true);
        }
        $path = tempnam(sys_get_temp_dir(), 'emis').'.xlsx';
        (new Xlsx($book))->save($path);

        return UploadedFile::fake()->createWithContent('Daftar_Siswa.xlsx', (string) file_get_contents($path));
    }
}
