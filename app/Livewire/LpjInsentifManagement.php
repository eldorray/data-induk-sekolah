<?php

namespace App\Livewire;

use App\Models\LpjInsentif;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class LpjInsentifManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterTahun = '';

    public int $perPage = 10;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $isEditing = false;

    public ?int $lpjId = null;

    public string $periode = '';

    public string $tahun = '';

    public string $kota = 'TANGERANG';

    public string $bulan_ttd = '';

    public string $nama_kepala = '';

    public string $jabatan_kepala = 'KEPALA MADRASAH';

    public string $nama_bendahara = '';

    public string $jabatan_bendahara = 'BENDAHARA SEKOLAH';

    public ?string $catatan = null;

    protected function rules(): array
    {
        return [
            'periode' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'kota' => 'required|string|max:100',
            'bulan_ttd' => 'required|string|max:50',
            'nama_kepala' => 'required|string|max:255',
            'jabatan_kepala' => 'required|string|max:255',
            'nama_bendahara' => 'required|string|max:255',
            'jabatan_bendahara' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->tahun = (string) now()->year;
        $this->kota = strtoupper(SchoolSetting::where('key', 'kota')->value('value') ?? 'TANGERANG');
        $this->nama_kepala = SchoolSetting::where('key', 'nama_kepala')->value('value') ?? '';
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $lpjId): void
    {
        $lpj = LpjInsentif::findOrFail($lpjId);
        $this->lpjId = $lpj->id;
        $this->periode = $lpj->periode;
        $this->tahun = $lpj->tahun;
        $this->kota = $lpj->kota;
        $this->bulan_ttd = $lpj->bulan_ttd;
        $this->nama_kepala = $lpj->nama_kepala;
        $this->jabatan_kepala = $lpj->jabatan_kepala;
        $this->nama_bendahara = $lpj->nama_bendahara;
        $this->jabatan_bendahara = $lpj->jabatan_bendahara;
        $this->catatan = $lpj->catatan;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            LpjInsentif::findOrFail($this->lpjId)->update($validated);
            session()->flash('success', 'LPJ Insentif berhasil diperbarui.');
        } else {
            LpjInsentif::create($validated);
            session()->flash('success', 'LPJ Insentif berhasil dibuat.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $lpjId): void
    {
        $this->lpjId = $lpjId;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        LpjInsentif::findOrFail($this->lpjId)->delete();
        $this->showDeleteModal = false;
        $this->lpjId = null;
        session()->flash('success', 'LPJ Insentif berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->lpjId = null;
        $this->periode = '';
        $this->tahun = '';
        $this->kota = 'TANGERANG';
        $this->bulan_ttd = '';
        $this->nama_kepala = '';
        $this->jabatan_kepala = 'KEPALA MADRASAH';
        $this->nama_bendahara = '';
        $this->jabatan_bendahara = 'BENDAHARA SEKOLAH';
        $this->catatan = null;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $lpjs = LpjInsentif::query()
            ->withCount('penerimas')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('periode', 'like', '%'.$this->search.'%')
                        ->orWhere('tahun', 'like', '%'.$this->search.'%')
                        ->orWhere('nama_bendahara', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterTahun, fn ($query) => $query->where('tahun', $this->filterTahun))
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        $tahunOptions = LpjInsentif::query()
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->all();

        return view('livewire.lpj-insentif-management', [
            'lpjs' => $lpjs,
            'tahunOptions' => $tahunOptions,
        ])->layout('layouts.admin', ['header' => 'LPJ Insentif']);
    }
}
