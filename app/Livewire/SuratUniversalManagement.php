<?php

namespace App\Livewire;

use App\Models\SuratUniversal;
use Livewire\Component;
use Livewire\WithPagination;

class SuratUniversalManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterJenjang = '';
    public int $perPage = 10;

    public bool $showDeleteModal = false;
    public ?int $suratId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openDeleteModal(int $id): void
    {
        $this->suratId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SuratUniversal::findOrFail($this->suratId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'Surat berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showDeleteModal = false;
        $this->suratId = null;
    }

    public function render()
    {
        $surats = SuratUniversal::query()
            ->when($this->search, fn($q) => $q->where(fn($sub) => $sub
                ->where('judul', 'like', '%' . $this->search . '%')
                ->orWhere('jenis', 'like', '%' . $this->search . '%')
                ->orWhere('nomor_surat', 'like', '%' . $this->search . '%')))
            ->when($this->filterJenjang, fn($q) => $q->where('jenjang', $this->filterJenjang))
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.surat-universal-management', [
            'surats' => $surats,
            'jenjangOptions' => SuratUniversal::JENJANG_OPTIONS,
        ])->layout('layouts.admin', ['header' => 'Surat']);
    }
}
