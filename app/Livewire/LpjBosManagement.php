<?php

namespace App\Livewire;

use App\Models\Kuitansi;
use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class LpjBosManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterTahun = '';

    public ?string $tanggalAwal = null;

    public ?string $tanggalAkhir = null;

    public string $filterKelengkapan = '';

    public int $perPage = 10;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $isEditing = false;

    public ?int $kuitansiId = null;

    public ?int $lpjId = null;

    public string $nama_kegiatan = '';

    public ?string $tanggal_kegiatan = null;

    public string $lokasi = '';

    public ?string $catatan = null;

    protected function rules(): array
    {
        return [
            'kuitansiId' => 'required|exists:kuitansis,id',
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'required|string|max:255',
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

    public function updatingTanggalAwal(): void
    {
        $this->resetPage();
    }

    public function updatingTanggalAkhir(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKelengkapan(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(int $kuitansiId): void
    {
        $this->resetForm();
        $this->kuitansiId = $kuitansiId;
        $this->tanggal_kegiatan = date('Y-m-d');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $lpjId): void
    {
        $lpj = LpjBos::findOrFail($lpjId);
        $this->lpjId = $lpj->id;
        $this->kuitansiId = $lpj->kuitansi_id;
        $this->nama_kegiatan = $lpj->nama_kegiatan;
        $this->tanggal_kegiatan = $lpj->tanggal_kegiatan->format('Y-m-d');
        $this->lokasi = $lpj->lokasi;
        $this->catatan = $lpj->catatan;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'kuitansi_id' => $validated['kuitansiId'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'lokasi' => $validated['lokasi'],
            'catatan' => $validated['catatan'] ?? null,
        ];

        if ($this->isEditing) {
            LpjBos::findOrFail($this->lpjId)->update($data);
            session()->flash('success', 'LPJ BOS berhasil diperbarui.');
        } else {
            LpjBos::create($data);
            session()->flash('success', 'LPJ BOS berhasil dibuat.');
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
        $lpj = LpjBos::with('attachments')->findOrFail($this->lpjId);

        foreach ($lpj->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $lpj->delete();
        $this->showDeleteModal = false;
        $this->lpjId = null;
        session()->flash('success', 'LPJ BOS berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->kuitansiId = null;
        $this->lpjId = null;
        $this->nama_kegiatan = '';
        $this->tanggal_kegiatan = null;
        $this->lokasi = '';
        $this->catatan = null;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $kuitansis = Kuitansi::query()
            ->with(['lpjBos.attachments'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_bukti', 'like', '%'.$this->search.'%')
                        ->orWhere('penerima', 'like', '%'.$this->search.'%')
                        ->orWhere('uraian_pembayaran', 'like', '%'.$this->search.'%')
                        ->orWhereHas('lpjBos', function ($lpjQuery) {
                            $lpjQuery->where('nama_kegiatan', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->filterTahun, fn ($query) => $query->where('tahun_anggaran', $this->filterTahun))
            ->when($this->tanggalAwal, fn ($query) => $query->whereHas('lpjBos', fn ($q) => $q->whereDate('tanggal_kegiatan', '>=', $this->tanggalAwal)))
            ->when($this->tanggalAkhir, fn ($query) => $query->whereHas('lpjBos', fn ($q) => $q->whereDate('tanggal_kegiatan', '<=', $this->tanggalAkhir)))
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        if ($this->filterKelengkapan === 'lengkap' || $this->filterKelengkapan === 'belum_lengkap') {
            $filtered = $kuitansis->getCollection()->filter(function (Kuitansi $kuitansi) {
                $isComplete = $kuitansi->lpjBos?->is_complete ?? false;

                return $this->filterKelengkapan === 'lengkap' ? $isComplete : ! $isComplete;
            })->values();
            $kuitansis->setCollection($filtered);
        }

        $tahunOptions = Kuitansi::query()
            ->select('tahun_anggaran')
            ->whereNotNull('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran', 'desc')
            ->pluck('tahun_anggaran')
            ->all();

        return view('livewire.lpj-bos-management', [
            'kuitansis' => $kuitansis,
            'tahunOptions' => $tahunOptions,
            'categories' => LpjBosAttachment::CATEGORIES,
        ])->layout('layouts.admin', ['header' => 'LPJ BOS']);
    }
}
