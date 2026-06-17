<?php

namespace App\Livewire;

use App\Models\SuratTerimaPindahan;
use Livewire\Component;
use Livewire\WithPagination;

class SuratTerimaPindahanManagement extends Component
{
    use WithPagination;

    // Search and filter
    public string $search = '';
    public string $filterStatus = '';
    public int $perPage = 10;

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showCetakModal = false;
    public bool $isEditing = false;

    // Cetak dokumen state
    public array $selectedIds = [];
    public bool $selectAll = false;

    // Form data
    public ?int $suratId = null;
    public string $nama_siswa = '';
    public string $tempat_lahir = '';
    public ?string $tanggal_lahir = null;
    public string $kelas = '';
    public string $jenis_kelamin = 'L';
    public string $asal_sekolah = '';
    public string $nama_orang_tua = '';
    public string $alamat_rumah = '';
    public string $nomor_surat = '';
    public ?string $tanggal_surat = null;
    public string $status = 'draft';

    protected function rules(): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'kelas' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'asal_sekolah' => 'nullable|string|max:255',
            'nama_orang_tua' => 'nullable|string|max:255',
            'alamat_rumah' => 'nullable|string|max:500',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'status' => 'required|in:draft,disetujui,dibatalkan',
        ];
    }

    protected $messages = [
        'nama_siswa.required' => 'Nama siswa wajib diisi.',
        'nomor_surat.required' => 'Nomor surat wajib diisi.',
        'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
    ];

    protected $listeners = ['cetakPdfSelesai' => 'cetakPdfSelesai'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_surat = SuratTerimaPindahan::generateNomorSurat();
        $this->tanggal_surat = date('Y-m-d');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $surat = SuratTerimaPindahan::findOrFail($id);
        $this->suratId = $surat->id;
        $this->nama_siswa = $surat->nama_siswa;
        $this->tempat_lahir = $surat->tempat_lahir ?? '';
        $this->tanggal_lahir = $surat->tanggal_lahir?->format('Y-m-d');
        $this->kelas = $surat->kelas ?? '';
        $this->jenis_kelamin = $surat->jenis_kelamin ?? 'L';
        $this->asal_sekolah = $surat->asal_sekolah ?? '';
        $this->nama_orang_tua = $surat->nama_orang_tua ?? '';
        $this->alamat_rumah = $surat->alamat_rumah ?? '';
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
        $this->status = $surat->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $surat = SuratTerimaPindahan::findOrFail($this->suratId);
            $surat->update($validated);
            session()->flash('success', 'Surat menerima siswa pindahan berhasil diperbarui.');
        } else {
            SuratTerimaPindahan::create($validated);
            session()->flash('success', 'Surat menerima siswa pindahan berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->suratId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $surat = SuratTerimaPindahan::findOrFail($this->suratId);
        $surat->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'Surat menerima siswa pindahan berhasil dihapus.');
    }

    public function openCetakModal(): void
    {
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->showCetakModal = true;
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = SuratTerimaPindahan::where('status', 'disetujui')
                ->orderBy('tanggal_surat', 'asc')
                ->orderBy('id', 'asc')
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function cetakDokumen(): void
    {
        if (empty($this->selectedIds)) {
            session()->flash('error', 'Pilih minimal satu surat untuk dicetak.');
            return;
        }

        $url = route('surat-terima-pindahan.cetak-dokumen', ['ids' => $this->selectedIds]);
        $this->dispatch('open-cetak-pdf', url: $url);
        $this->showCetakModal = false;
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showCetakModal = false;
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->suratId = null;
        $this->nama_siswa = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = null;
        $this->kelas = '';
        $this->jenis_kelamin = 'L';
        $this->asal_sekolah = '';
        $this->nama_orang_tua = '';
        $this->alamat_rumah = '';
        $this->nomor_surat = '';
        $this->tanggal_surat = null;
        $this->status = 'draft';
        $this->resetErrorBag();
    }

    public function render()
    {
        $surats = SuratTerimaPindahan::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                        ->orWhere('asal_sekolah', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_orang_tua', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_surat', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $disetujuiSurats = SuratTerimaPindahan::where('status', 'disetujui')
            ->orderBy('tanggal_surat', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('livewire.surat-terima-pindahan-management', [
            'surats' => $surats,
            'disetujuiSurats' => $disetujuiSurats,
        ])->layout('layouts.admin', ['header' => 'Surat Menerima Siswa Pindahan']);
    }
}
