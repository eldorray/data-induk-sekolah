<?php

namespace App\Livewire;

use App\Models\SuratUniversal;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SuratUniversalManagement extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterJenjang = '';
    public int $perPage = 10;

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $isEditing = false;

    public ?int $suratId = null;
    public string $jenis = '';
    public string $judul = '';
    public string $nomor_surat = '';
    public ?string $tanggal_surat = null;
    public string $jenjang = 'MI';
    public string $isi = '';
    public string $tempat = '';
    public string $ttd_jabatan = '';
    public string $ttd_nama = '';
    public string $ttd_nip = '';

    public $kopFile = null;           // upload baru
    public ?string $existingKopPath = null; // kop tersimpan (saat edit)

    protected function rules(): array
    {
        return [
            'jenis' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'jenjang' => 'required|string|max:50',
            'isi' => 'required|string',
            'tempat' => 'nullable|string|max:255',
            'ttd_jabatan' => 'nullable|string|max:255',
            'ttd_nama' => 'nullable|string|max:255',
            'ttd_nip' => 'nullable|string|max:100',
            'kopFile' => 'nullable|image|max:4096', // 4MB
        ];
    }

    protected $messages = [
        'jenis.required' => 'Jenis surat wajib diisi.',
        'judul.required' => 'Judul surat wajib diisi.',
        'nomor_surat.required' => 'Nomor surat wajib diisi.',
        'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
        'isi.required' => 'Isi surat wajib diisi.',
        'kopFile.image' => 'Kop surat harus berupa gambar.',
        'kopFile.max' => 'Ukuran kop maksimal 4MB.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_surat = SuratUniversal::generateNomorSurat();
        $this->tanggal_surat = date('Y-m-d');
        $this->tempat = SchoolSetting::get('kuitansi_kabupaten', '') ?? '';
        $this->ttd_jabatan = 'Kepala Madrasah';
        $this->ttd_nama = SchoolSetting::get('kuitansi_kepala_madrasah', '') ?? '';
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $surat = SuratUniversal::findOrFail($id);
        $this->suratId = $surat->id;
        $this->jenis = $surat->jenis;
        $this->judul = $surat->judul;
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
        $this->jenjang = $surat->jenjang ?? 'MI';
        $this->isi = $surat->isi ?? '';
        $this->tempat = $surat->tempat ?? '';
        $this->ttd_jabatan = $surat->ttd_jabatan ?? '';
        $this->ttd_nama = $surat->ttd_nama ?? '';
        $this->ttd_nip = $surat->ttd_nip ?? '';
        $this->existingKopPath = $surat->kop_path;
        $this->kopFile = null;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = collect($validated)->except('kopFile')->toArray();

        if ($this->kopFile) {
            $data['kop_path'] = $this->kopFile->store('kop-surat', 'public');
        }

        if ($this->isEditing) {
            $surat = SuratUniversal::findOrFail($this->suratId);
            $surat->update($data);
            session()->flash('success', 'Surat berhasil diperbarui.');
        } else {
            SuratUniversal::create($data);
            session()->flash('success', 'Surat berhasil ditambahkan.');
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
        SuratUniversal::findOrFail($this->suratId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'Surat berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'suratId', 'jenis', 'judul', 'nomor_surat', 'tanggal_surat',
            'isi', 'tempat', 'ttd_jabatan', 'ttd_nama', 'ttd_nip',
            'kopFile', 'existingKopPath',
        ]);
        $this->jenjang = 'MI';
        $this->resetErrorBag();
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

        $kop = SchoolSetting::get('kop_surat_path');

        return view('livewire.surat-universal-management', [
            'surats' => $surats,
            'jenjangOptions' => SuratUniversal::JENJANG_OPTIONS,
            'defaultKopUrl' => $kop ? asset('storage/' . $kop) : null,
        ])->layout('layouts.admin', ['header' => 'Surat']);
    }
}
