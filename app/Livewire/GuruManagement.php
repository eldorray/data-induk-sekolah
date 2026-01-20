<?php

namespace App\Livewire;

use App\Models\Guru;
use App\Exports\GuruExport;
use App\Exports\GuruTemplateExport;
use App\Imports\GuruImport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class GuruManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter
    public string $search = '';
    public string $filterStatus = '';
    public int $perPage = 10;
    public string $sortField = 'full_name';
    public string $sortDirection = 'asc';

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showImportModal = false;
    public bool $showDeleteAllModal = false;
    public bool $isEditing = false;

    // Form data
    public ?int $guruId = null;
    public ?string $nip = null;
    public ?string $nuptk = null;
    public ?string $npk = null;
    public string $nik = '';
    public ?string $front_title = null;
    public string $full_name = '';
    public ?string $back_title = null;
    public string $gender = 'L';
    public ?string $pob = null;
    public ?string $dob = null;
    public ?string $phone_number = null;
    public ?string $address = null;
    public string $status_pegawai = 'GTY';
    public bool $is_active = true;

    // File uploads
    public $sk_awal;
    public $sk_akhir;
    public ?string $current_sk_awal = null;
    public ?string $current_sk_akhir = null;

    // Import
    public $importFile;
    public array $importErrors = [];

    protected function rules(): array
    {
        $uniqueRule = $this->isEditing 
            ? 'required|string|size:16|unique:gurus,nik,' . $this->guruId
            : 'required|string|size:16|unique:gurus,nik';

        return [
            'nip' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'npk' => 'nullable|string|max:30',
            'nik' => $uniqueRule,
            'front_title' => 'nullable|string|max:20',
            'full_name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:20',
            'gender' => 'required|in:L,P',
            'pob' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'status_pegawai' => 'required|string|max:20',
            'is_active' => 'boolean',
            'sk_awal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sk_akhir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    protected $messages = [
        'nik.required' => 'NIK wajib diisi.',
        'nik.size' => 'NIK harus 16 digit.',
        'nik.unique' => 'NIK sudah terdaftar.',
        'full_name.required' => 'Nama lengkap wajib diisi.',
        'gender.required' => 'Jenis kelamin wajib dipilih.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $guru = Guru::findOrFail($id);
        $this->guruId = $guru->id;
        $this->nip = $guru->nip;
        $this->nuptk = $guru->nuptk;
        $this->npk = $guru->npk;
        $this->nik = $guru->nik;
        $this->front_title = $guru->front_title;
        $this->full_name = $guru->full_name;
        $this->back_title = $guru->back_title;
        $this->gender = $guru->gender;
        $this->pob = $guru->pob;
        $this->dob = $guru->dob?->format('Y-m-d');
        $this->phone_number = $guru->phone_number;
        $this->address = $guru->address;
        $this->status_pegawai = $guru->status_pegawai;
        $this->is_active = $guru->is_active;
        $this->current_sk_awal = $guru->sk_awal_path;
        $this->current_sk_akhir = $guru->sk_akhir_path;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = collect($validated)->except(['sk_awal', 'sk_akhir'])->toArray();

        // Handle file uploads
        if ($this->sk_awal) {
            if ($this->current_sk_awal) {
                Storage::disk('public')->delete($this->current_sk_awal);
            }
            $data['sk_awal_path'] = $this->sk_awal->store('guru/sk', 'public');
        }

        if ($this->sk_akhir) {
            if ($this->current_sk_akhir) {
                Storage::disk('public')->delete($this->current_sk_akhir);
            }
            $data['sk_akhir_path'] = $this->sk_akhir->store('guru/sk', 'public');
        }

        if ($this->isEditing) {
            $guru = Guru::findOrFail($this->guruId);
            $guru->update($data);
            session()->flash('success', 'Data guru berhasil diperbarui.');
        } else {
            Guru::create($data);
            session()->flash('success', 'Data guru berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->guruId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $guru = Guru::findOrFail($this->guruId);
        
        // Delete associated files
        if ($guru->sk_awal_path) {
            Storage::disk('public')->delete($guru->sk_awal_path);
        }
        if ($guru->sk_akhir_path) {
            Storage::disk('public')->delete($guru->sk_akhir_path);
        }
        
        $guru->delete();
        $this->showDeleteModal = false;
        $this->guruId = null;
        session()->flash('success', 'Data guru berhasil dihapus.');
    }

    public function openDeleteAllModal(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        // Delete all SK files
        $gurus = Guru::whereNotNull('sk_awal_path')->orWhereNotNull('sk_akhir_path')->get();
        foreach ($gurus as $guru) {
            if ($guru->sk_awal_path) {
                Storage::disk('public')->delete($guru->sk_awal_path);
            }
            if ($guru->sk_akhir_path) {
                Storage::disk('public')->delete($guru->sk_akhir_path);
            }
        }
        
        Guru::truncate();
        $this->showDeleteAllModal = false;
        session()->flash('success', 'Semua data guru berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showImportModal = false;
        $this->showDeleteAllModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->guruId = null;
        $this->nip = null;
        $this->nuptk = null;
        $this->npk = null;
        $this->nik = '';
        $this->front_title = null;
        $this->full_name = '';
        $this->back_title = null;
        $this->gender = 'L';
        $this->pob = null;
        $this->dob = null;
        $this->phone_number = null;
        $this->address = null;
        $this->status_pegawai = 'GTY';
        $this->is_active = true;
        $this->sk_awal = null;
        $this->sk_akhir = null;
        $this->current_sk_awal = null;
        $this->current_sk_akhir = null;
        $this->importFile = null;
        $this->importErrors = [];
        $this->resetValidation();
    }

    public function deleteSKAwal(): void
    {
        if ($this->current_sk_awal && $this->isEditing) {
            $guru = Guru::find($this->guruId);
            if ($guru) {
                Storage::disk('public')->delete($this->current_sk_awal);
                $guru->update(['sk_awal_path' => null]);
                $this->current_sk_awal = null;
                session()->flash('success', 'SK Awal berhasil dihapus.');
            }
        }
    }

    public function deleteSKAkhir(): void
    {
        if ($this->current_sk_akhir && $this->isEditing) {
            $guru = Guru::find($this->guruId);
            if ($guru) {
                Storage::disk('public')->delete($this->current_sk_akhir);
                $guru->update(['sk_akhir_path' => null]);
                $this->current_sk_akhir = null;
                session()->flash('success', 'SK Akhir berhasil dihapus.');
            }
        }
    }

    // Export Excel
    public function export()
    {
        return Excel::download(new GuruExport, 'data-guru-' . date('Y-m-d') . '.xlsx');
    }

    // Download Template
    public function downloadTemplate()
    {
        return Excel::download(new GuruTemplateExport, 'template-import-guru.xlsx');
    }

    // Import Modal
    public function openImportModal(): void
    {
        $this->importFile = null;
        $this->importErrors = [];
        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->importFile = null;
        $this->importErrors = [];
    }

    // Import Excel
    public function import(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new GuruImport;
            Excel::import($import, $this->importFile->getRealPath());

            $errors = $import->getErrors();
            if (count($errors) > 0) {
                $this->importErrors = $errors;
            } else {
                $this->closeImportModal();
                session()->flash('success', 'Data guru berhasil diimport.');
            }
        } catch (\Exception $e) {
            $this->importErrors = ['Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    public function render()
    {
        $gurus = Guru::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('nip', 'like', '%' . $this->search . '%')
                        ->orWhere('nuptk', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                if ($this->filterStatus === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->filterStatus === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.guru-management', [
            'gurus' => $gurus,
        ])->layout('layouts.admin', ['header' => 'Data Guru']);
    }
}
