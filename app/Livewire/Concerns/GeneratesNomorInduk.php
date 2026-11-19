<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Models\ValidasiDataSiswa;
use App\Services\NomorIndukSiswa;
use Illuminate\Validation\Rule;
use OverflowException;

/**
 * Modal "Generate nomor induk" untuk halaman data siswa MI dan SMP.
 */
trait GeneratesNomorInduk
{
    public bool $showGenerateModal = false;

    public string $genKelas = '';

    public ?int $genTahunMasuk = null;

    /** 'MI' atau 'SMP' */
    abstract protected function sekolah(): string;

    public function openGenerateModal(): void
    {
        $this->reset('genKelas', 'genTahunMasuk');
        $this->resetValidation();
        $this->showGenerateModal = true;
    }

    public function closeGenerateModal(): void
    {
        $this->showGenerateModal = false;
    }

    public function updatedGenKelas(string $kelas): void
    {
        $this->genTahunMasuk = $kelas === ''
            ? null
            : NomorIndukSiswa::tahunMasuk($kelas, $this->sekolah() === 'SMP' ? 7 : 1);
    }

    public function generateNomorInduk(): void
    {
        $this->validate([
            'genKelas' => ['required', Rule::in($this->kelasNomorInduk())],
            'genTahunMasuk' => 'required|integer|between:2000,2099',
        ], [
            'genKelas.required' => 'Pilih kelas dulu.',
            'genKelas.in' => 'Kelas tidak ditemukan.',
            'genTahunMasuk.required' => 'Tahun masuk wajib diisi.',
            'genTahunMasuk.between' => 'Tahun masuk harus antara 2000 dan 2099.',
        ]);

        try {
            $jumlah = NomorIndukSiswa::generate($this->siswaModel(), $this->genKelas, (int) $this->genTahunMasuk);
        } catch (OverflowException $e) {
            $this->addError('genTahunMasuk', $e->getMessage());

            return;
        }

        $this->showGenerateModal = false;
        session()->flash('success', $jumlah > 0
            ? "Nomor induk dibuat untuk {$jumlah} siswa di {$this->genKelas}."
            : "Semua siswa aktif di {$this->genKelas} sudah punya nomor induk.");
    }

    /**
     * @return list<string>
     */
    public function kelasNomorInduk(): array
    {
        return ValidasiDataSiswa::kelasAktif($this->sekolah());
    }

    /**
     * @return array{jumlah:int, mulai:string, sampai:string}|null
     */
    public function pratinjauNomorInduk(): ?array
    {
        if ($this->genKelas === '' || $this->genTahunMasuk === null || $this->genTahunMasuk < 2000 || $this->genTahunMasuk > 2099) {
            return null;
        }

        return NomorIndukSiswa::pratinjau($this->siswaModel(), $this->genKelas, $this->genTahunMasuk);
    }

    /**
     * @return class-string<SiswaMi|SiswaSmp>
     */
    private function siswaModel(): string
    {
        return $this->sekolah() === 'SMP' ? SiswaSmp::class : SiswaMi::class;
    }
}
