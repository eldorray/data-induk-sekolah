<?php

namespace App\Livewire;

use App\Models\HariLibur;
use App\Models\LpjInsentif;
use Livewire\Component;

class AbsensiInsentif extends Component
{
    public ?int $lpjId = null;

    public int $bulan = 7;

    public int $tahun;

    public bool $showLiburModal = false;

    public string $liburTanggal = '';

    public string $liburNama = '';

    public function mount(): void
    {
        $lpj = LpjInsentif::latest('id')->first();
        $this->lpjId = $lpj?->id;
        $this->tahun = (int) ($lpj?->tahun ?? now()->year);
        $this->bulan = (int) now()->month;
    }

    public function updatedLpjId(): void
    {
        $lpj = LpjInsentif::find($this->lpjId);
        if ($lpj) {
            $this->tahun = (int) $lpj->tahun;
        }
    }

    public function openLiburModal(): void
    {
        $this->liburTanggal = sprintf('%04d-%02d-01', $this->tahun, $this->bulan);
        $this->liburNama = '';
        $this->showLiburModal = true;
    }

    public function closeLiburModal(): void
    {
        $this->showLiburModal = false;
        $this->liburTanggal = '';
        $this->liburNama = '';
        $this->resetErrorBag();
    }

    public function saveLibur(): void
    {
        $this->validate([
            'liburTanggal' => 'required|date',
            'liburNama' => 'required|string|max:100',
        ]);

        // Hari Minggu otomatis arsir — tak perlu dicatat
        if (\Illuminate\Support\Carbon::parse($this->liburTanggal)->isSunday()) {
            $this->addError('liburTanggal', 'Hari Minggu sudah otomatis diarsir.');
        } else {
            HariLibur::updateOrCreate(
                ['tanggal' => $this->liburTanggal],
                ['nama' => $this->liburNama],
            );
            session()->flash('success', 'Hari libur ditambahkan.');
        }

        $this->showLiburModal = false;
    }

    public function deleteLibur(int $id): void
    {
        HariLibur::findOrFail($id)->delete();
        session()->flash('success', 'Hari libur dihapus.');
    }

    public function render()
    {
        $lpjs = LpjInsentif::query()->orderByDesc('tahun')->orderByDesc('created_at')->get();

        $lpj = $this->lpjId ? LpjInsentif::with('penerimas')->find($this->lpjId) : null;

        $jumlahHari = HariLibur::jumlahHari($this->tahun, $this->bulan);

        $arsir = HariLibur::hariArsir($this->tahun, $this->bulan);

        $hariLibur = HariLibur::query()
            ->whereYear('tanggal', $this->tahun)
            ->whereMonth('tanggal', $this->bulan)
            ->orderBy('tanggal')
            ->get();

        return view('livewire.absensi-insentif', [
            'lpjs' => $lpjs,
            'lpj' => $lpj,
            'jumlahHari' => $jumlahHari,
            'arsir' => $arsir,
            'hariLibur' => $hariLibur,
            'namaBulan' => HariLibur::namaBulan($this->bulan),
            'tahunAjaran' => HariLibur::tahunAjaran($this->tahun, $this->bulan),
        ])->layout('layouts.admin', ['header' => 'Absensi Insentif']);
    }
}
