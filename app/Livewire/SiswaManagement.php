<?php

namespace App\Livewire;

use App\Models\Siswa;
use App\Exports\SiswaExport;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class SiswaManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter
    public string $search = '';
    public string $sortField = 'nama_lengkap';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDeleteAllModal = false;
    public bool $showImportModal = false;
    public bool $isEditing = false;

    // Form data
    public ?int $siswaId = null;
    public string $nama_lengkap = '';
    public string $nisn = '';
    public string $nik = '';
    public string $tempat_lahir = '';
    public ?string $tanggal_lahir = null;
    public string $tingkat_rombel = '';
    public string $status = 'Aktif';
    public string $jenis_kelamin = '';
    public string $alamat = '';
    public string $no_telepon = '';
    public string $kebutuhan_khusus = '';
    public string $disabilitas = '';
    public string $nomor_kip_pip = '';
    public string $nama_ayah_kandung = '';
    public string $nama_ibu_kandung = '';
    public string $nama_wali = '';

    // Import
    public $importFile;
    public array $importErrors = [];

    protected function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nisn')->ignore($this->siswaId)],
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nik')->ignore($this->siswaId)],
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tingkat_rombel' => 'nullable|string|max:100',
            'status' => 'required|string|in:Aktif,Tidak Aktif,Lulus,Pindah,Keluar',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'kebutuhan_khusus' => 'nullable|string|max:255',
            'disabilitas' => 'nullable|string|max:255',
            'nomor_kip_pip' => 'nullable|string|max:50',
            'nama_ayah_kandung' => 'nullable|string|max:255',
            'nama_ibu_kandung' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nisn.unique' => 'NISN sudah terdaftar.',
        'nik.unique' => 'NIK sudah terdaftar.',
        'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
        'status.in' => 'Status tidak valid.',
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
        $siswa = Siswa::findOrFail($id);
        $this->siswaId = $siswa->id;
        $this->nama_lengkap = $siswa->nama_lengkap;
        $this->nisn = $siswa->nisn ?? '';
        $this->nik = $siswa->nik ?? '';
        $this->tempat_lahir = $siswa->tempat_lahir ?? '';
        $this->tanggal_lahir = $siswa->tanggal_lahir?->format('Y-m-d');
        $this->tingkat_rombel = $siswa->tingkat_rombel ?? '';
        $this->status = $siswa->status ?? 'Aktif';
        $this->jenis_kelamin = $siswa->jenis_kelamin ?? '';
        $this->alamat = $siswa->alamat ?? '';
        $this->no_telepon = $siswa->no_telepon ?? '';
        $this->kebutuhan_khusus = $siswa->kebutuhan_khusus ?? '';
        $this->disabilitas = $siswa->disabilitas ?? '';
        $this->nomor_kip_pip = $siswa->nomor_kip_pip ?? '';
        $this->nama_ayah_kandung = $siswa->nama_ayah_kandung ?? '';
        $this->nama_ibu_kandung = $siswa->nama_ibu_kandung ?? '';
        $this->nama_wali = $siswa->nama_wali ?? '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $siswa = Siswa::findOrFail($this->siswaId);
            $siswa->update($validated);
            session()->flash('success', 'Data siswa berhasil diperbarui.');
        } else {
            Siswa::create($validated);
            session()->flash('success', 'Data siswa berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->siswaId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $siswa = Siswa::findOrFail($this->siswaId);
        $siswa->delete();
        $this->showDeleteModal = false;
        $this->siswaId = null;
        session()->flash('success', 'Data siswa berhasil dihapus.');
    }

    public function openDeleteAllModal(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        $count = Siswa::count();
        Siswa::truncate();
        $this->showDeleteAllModal = false;
        session()->flash('success', "Semua data siswa ($count data) berhasil dihapus.");
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showDeleteAllModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->siswaId = null;
        $this->nama_lengkap = '';
        $this->nisn = '';
        $this->nik = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = null;
        $this->tingkat_rombel = '';
        $this->status = 'Aktif';
        $this->jenis_kelamin = '';
        $this->alamat = '';
        $this->no_telepon = '';
        $this->kebutuhan_khusus = '';
        $this->disabilitas = '';
        $this->nomor_kip_pip = '';
        $this->nama_ayah_kandung = '';
        $this->nama_ibu_kandung = '';
        $this->nama_wali = '';
        $this->resetValidation();
    }

    // Import/Export methods
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

    public function import(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'importFile.required' => 'File wajib dipilih.',
            'importFile.mimes' => 'File harus berformat Excel (xlsx, xls) atau CSV.',
            'importFile.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $import = new SiswaImport();
            Excel::import($import, $this->importFile);

            if ($import->getErrors()) {
                $this->importErrors = $import->getErrors();
                session()->flash('warning', 'Import selesai dengan beberapa error. Silakan periksa detail error.');
            } else {
                session()->flash('success', 'Data siswa berhasil diimport. Total: ' . $import->getRowCount() . ' data.');
                $this->closeImportModal();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new SiswaExport(), 'data-siswa-' . date('Y-m-d-His') . '.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport(), 'template-import-siswa.xlsx');
    }

    public function render()
    {
        $siswas = Siswa::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('tingkat_rombel', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.siswa-management', [
            'siswas' => $siswas,
        ])->layout('layouts.admin', ['header' => 'Data Siswa']);
    }
}
