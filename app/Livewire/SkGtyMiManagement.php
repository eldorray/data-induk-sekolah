<?php

namespace App\Livewire;

use App\Models\SkGtyMi;
use App\Models\GuruMi;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SkGtyMiManagement extends Component
{
    use WithPagination;

    // Search and filter
    public string $search = '';
    public string $filterStatus = '';
    public int $perPage = 10;

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $isEditing = false;

    // Form data
    public ?int $skId = null;
    public ?int $guru_mi_id = null;
    public string $nomor_sk = '';
    public ?string $tanggal_sk = null;
    public ?string $tempat_lahir = null;
    public ?string $tanggal_lahir = null;
    public ?string $nuptk = null;
    public ?string $pendidikan_terakhir = null;
    public ?string $jabatan = null;
    public ?string $berlaku_mulai = null;
    public ?string $berlaku_sampai = null;
    public string $penandatangan_nama = '';
    public string $penandatangan_jabatan = 'Ketua Yayasan';
    public string $tempat_penetapan = 'Tangerang';
    public ?string $tanggal_penetapan = null;
    public string $status = 'draft';

    // Guru search
    public string $searchGuru = '';
    public array $guruResults = [];
    public ?array $selectedGuru = null;

    protected function rules(): array
    {
        return [
            'guru_mi_id' => 'required|exists:guru_mis,id',
            'nomor_sk' => 'required|string|max:100',
            'tanggal_sk' => 'required|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'nuptk' => 'nullable|string|max:30',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'required|date|after_or_equal:berlaku_mulai',
            'penandatangan_nama' => 'required|string|max:255',
            'penandatangan_jabatan' => 'required|string|max:100',
            'tempat_penetapan' => 'required|string|max:100',
            'tanggal_penetapan' => 'required|date',
            'status' => 'required|in:draft,aktif,tidak_aktif',
        ];
    }

    protected $messages = [
        'guru_mi_id.required' => 'Guru wajib dipilih.',
        'nomor_sk.required' => 'Nomor SK wajib diisi.',
        'tanggal_sk.required' => 'Tanggal SK wajib diisi.',
        'berlaku_mulai.required' => 'Tanggal mulai berlaku wajib diisi.',
        'berlaku_sampai.required' => 'Tanggal berakhir wajib diisi.',
        'penandatangan_nama.required' => 'Nama penandatangan wajib diisi.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSearchGuru(): void
    {
        if (strlen($this->searchGuru) >= 2) {
            $this->guruResults = GuruMi::query()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->where('full_name', 'like', '%' . $this->searchGuru . '%')
                        ->orWhere('nuptk', 'like', '%' . $this->searchGuru . '%')
                        ->orWhere('nik', 'like', '%' . $this->searchGuru . '%');
                })
                ->limit(10)
                ->get()
                ->map(fn($g) => [
                    'id' => $g->id,
                    'nama' => $g->full_name_with_title,
                    'nuptk' => $g->nuptk,
                    'pob' => $g->pob,
                    'dob' => $g->dob?->format('Y-m-d'),
                    'status' => $g->status_pegawai,
                ])
                ->toArray();
        } else {
            $this->guruResults = [];
        }
    }

    public function selectGuru(int $id): void
    {
        $guru = GuruMi::find($id);

        if ($guru) {
            $this->guru_mi_id = $guru->id;
            $this->selectedGuru = [
                'id' => $guru->id,
                'nama' => $guru->full_name_with_title,
                'nuptk' => $guru->nuptk,
                'status' => $guru->status_pegawai_label,
            ];
            $this->tempat_lahir = $guru->pob;
            $this->tanggal_lahir = $guru->dob?->format('Y-m-d');
            $this->nuptk = $guru->nuptk;
            $this->searchGuru = '';
            $this->guruResults = [];
        }
    }

    public function clearGuru(): void
    {
        $this->guru_mi_id = null;
        $this->selectedGuru = null;
        $this->searchGuru = '';
        $this->tempat_lahir = null;
        $this->tanggal_lahir = null;
        $this->nuptk = null;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_sk = SkGtyMi::generateNomorSk();
        $this->tanggal_sk = date('Y-m-d');
        $this->tanggal_penetapan = date('Y-m-d');
        $this->penandatangan_nama = SchoolSetting::get('nama_kepala', '');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $sk = SkGtyMi::with('guru')->findOrFail($id);
        $this->skId = $sk->id;
        $this->guru_mi_id = $sk->guru_mi_id;
        $this->selectedGuru = [
            'id' => $sk->guru->id,
            'nama' => $sk->guru->full_name_with_title,
            'nuptk' => $sk->guru->nuptk,
            'status' => $sk->guru->status_pegawai_label,
        ];
        $this->nomor_sk = $sk->nomor_sk;
        $this->tanggal_sk = $sk->tanggal_sk?->format('Y-m-d');
        $this->tempat_lahir = $sk->tempat_lahir;
        $this->tanggal_lahir = $sk->tanggal_lahir?->format('Y-m-d');
        $this->nuptk = $sk->nuptk;
        $this->pendidikan_terakhir = $sk->pendidikan_terakhir;
        $this->jabatan = $sk->jabatan;
        $this->berlaku_mulai = $sk->berlaku_mulai?->format('Y-m-d');
        $this->berlaku_sampai = $sk->berlaku_sampai?->format('Y-m-d');
        $this->penandatangan_nama = $sk->penandatangan_nama;
        $this->penandatangan_jabatan = $sk->penandatangan_jabatan;
        $this->tempat_penetapan = $sk->tempat_penetapan;
        $this->tanggal_penetapan = $sk->tanggal_penetapan?->format('Y-m-d');
        $this->status = $sk->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $sk = SkGtyMi::findOrFail($this->skId);
            $sk->update($validated);
            session()->flash('success', 'SK GTY berhasil diperbarui.');
        } else {
            SkGtyMi::create($validated);
            session()->flash('success', 'SK GTY berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->skId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $sk = SkGtyMi::findOrFail($this->skId);
        $sk->delete();
        $this->showDeleteModal = false;
        $this->skId = null;
        session()->flash('success', 'SK GTY berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->skId = null;
        $this->guru_mi_id = null;
        $this->nomor_sk = '';
        $this->tanggal_sk = null;
        $this->tempat_lahir = null;
        $this->tanggal_lahir = null;
        $this->nuptk = null;
        $this->pendidikan_terakhir = null;
        $this->jabatan = null;
        $this->berlaku_mulai = null;
        $this->berlaku_sampai = null;
        $this->penandatangan_nama = '';
        $this->penandatangan_jabatan = 'Ketua Yayasan';
        $this->tempat_penetapan = 'Tangerang';
        $this->tanggal_penetapan = null;
        $this->status = 'draft';
        $this->searchGuru = '';
        $this->guruResults = [];
        $this->selectedGuru = null;
        $this->resetValidation();
    }

    public function render()
    {
        $skList = SkGtyMi::query()
            ->with('guru')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_sk', 'like', '%' . $this->search . '%')
                        ->orWhereHas('guru', function ($q2) {
                            $q2->where('full_name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.sk-gty-mi-management', [
            'skList' => $skList,
        ])->layout('layouts.admin', ['header' => 'SK Guru Tetap Yayasan (GTY) MI']);
    }
}
