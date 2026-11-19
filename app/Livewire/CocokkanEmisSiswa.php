<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Services\EmisSiswaSync;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class CocokkanEmisSiswa extends Component
{
    use WithFileUploads;

    private const MODEL = ['mi' => SiswaMi::class, 'smp' => SiswaSmp::class];

    public string $jenjang = 'mi';

    public ?TemporaryUploadedFile $file = null;

    /** Hasil compare() untuk ditampilkan saja; terapkan() menghitung ulang dari file, bukan dari array ini. */
    public ?array $hasil = null;

    public function updated(string $name): void
    {
        if (in_array($name, ['jenjang', 'file'], true)) {
            $this->hasil = null;
        }
    }

    public function bandingkan(): void
    {
        $this->hasil = $this->hitung();
    }

    public function terapkan(): void
    {
        $sync = new EmisSiswaSync;

        try {
            $n = $sync->apply(self::MODEL[$this->jenjang], $this->hitung());
        } catch (QueryException $e) {
            report($e);
            session()->flash('error', 'Gagal menerapkan: ada NISN/NIK dari EMIS yang sudah dipakai siswa lain di data induk. Cek data ganda dulu.');

            return;
        }

        session()->flash('success', "Data induk disamakan dengan EMIS: {$n['diperbarui']} siswa diperbarui, {$n['ditambah']} siswa ditambahkan.");
        $this->hasil = $this->hitung();
    }

    private function hitung(): array
    {
        $this->validate([
            'jenjang' => 'required|in:mi,smp',
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'Pilih file unduhan Daftar Siswa dari EMIS.',
            'file.mimes' => 'File harus Excel (xlsx/xls) hasil unduhan EMIS.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $sync = new EmisSiswaSync;

        return $sync->compare(self::MODEL[$this->jenjang], $sync->readFile($this->file->getRealPath()));
    }

    public function render(): View
    {
        return view('livewire.cocokkan-emis-siswa')
            ->layout('layouts.admin', ['header' => 'Cocokkan Data Siswa dengan EMIS']);
    }
}
