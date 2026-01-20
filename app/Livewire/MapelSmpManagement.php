<?php

namespace App\Livewire;

use App\Models\MapelSmp;
use App\Exports\MapelSmpExport;
use App\Exports\MapelSmpTemplateExport;
use App\Imports\MapelSmpImport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class MapelSmpManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter
    public string $search = '';
    public string $sortField = 'sort_order';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDeleteAllModal = false;
    public bool $showImportModal = false;
    public bool $isEditing = false;

    // Form data
    public ?int $mapelId = null;
    public string $kode_mapel = '';
    public string $nama_mapel = '';
    public int $jam_per_minggu = 2;
    public string $kelompok = 'Umum';
    public string $jurusan = '';
    public bool $is_active = true;

    // Import
    public $importFile;
    public array $importErrors = [];

    protected function rules(): array
    {
        return [
            'kode_mapel' => ['nullable', 'string', 'max:20', Rule::unique('mapel_smps', 'kode_mapel')->ignore($this->mapelId)],
            'nama_mapel' => 'required|string|max:255',
            'jam_per_minggu' => 'nullable|integer|min:1|max:20',
            'kelompok' => 'required|in:PAI,Umum',
            'jurusan' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
        'kode_mapel.unique' => 'Kode mata pelajaran sudah terdaftar.',
        'kelompok.in' => 'Kelompok harus PAI atau Umum.',
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

    public function sortItem($itemId, $position): void
    {
        // Get all items ordered by current sort_order to maintain consistent ordering
        $items = MapelSmp::orderBy('sort_order')->orderBy('id')->pluck('id')->toArray();
        
        // Find current index of the item in the full list
        $currentIndex = array_search($itemId, $items);
        
        if ($currentIndex === false) {
            return;
        }

        // Calculate the actual target position based on page offset
        // The $position from Livewire is relative to the visible items on current page
        $page = $this->getPage();
        $offset = ($page - 1) * $this->perPage;
        $targetIndex = $offset + $position;

        // Ensure target is within bounds
        $targetIndex = max(0, min($targetIndex, count($items) - 1));

        if ($currentIndex === $targetIndex) {
            return;
        }
        
        // Remove item from current position and insert at new position
        array_splice($items, $currentIndex, 1);
        array_splice($items, $targetIndex, 0, [$itemId]);
        
        // Update all sort_order values
        foreach ($items as $index => $id) {
            MapelSmp::where('id', $id)->update(['sort_order' => $index]);
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
        $mapel = MapelSmp::findOrFail($id);
        $this->mapelId = $mapel->id;
        $this->kode_mapel = $mapel->kode_mapel ?? '';
        $this->nama_mapel = $mapel->nama_mapel;
        $this->jam_per_minggu = $mapel->jam_per_minggu ?? 2;
        $this->kelompok = $mapel->kelompok ?? 'Umum';
        $this->jurusan = $mapel->jurusan ?? '';
        $this->is_active = $mapel->is_active ?? true;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $mapel = MapelSmp::findOrFail($this->mapelId);
            $mapel->update($validated);
            session()->flash('success', 'Data mata pelajaran SMP berhasil diperbarui.');
        } else {
            $maxOrder = MapelSmp::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
            MapelSmp::create($validated);
            session()->flash('success', 'Data mata pelajaran SMP berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->mapelId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $mapel = MapelSmp::findOrFail($this->mapelId);
        $mapel->delete();
        $this->showDeleteModal = false;
        $this->mapelId = null;
        session()->flash('success', 'Data mata pelajaran SMP berhasil dihapus.');
    }

    public function openDeleteAllModal(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        $count = MapelSmp::count();
        MapelSmp::truncate();
        $this->showDeleteAllModal = false;
        session()->flash('success', "Semua data mata pelajaran SMP ($count data) berhasil dihapus.");
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
        $this->mapelId = null;
        $this->kode_mapel = '';
        $this->nama_mapel = '';
        $this->jam_per_minggu = 2;
        $this->kelompok = 'Umum';
        $this->jurusan = '';
        $this->is_active = true;
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
            $import = new MapelSmpImport();
            Excel::import($import, $this->importFile);

            if ($import->getErrors()) {
                $this->importErrors = $import->getErrors();
                session()->flash('warning', 'Import selesai dengan beberapa error. Silakan periksa detail error.');
            } else {
                session()->flash('success', 'Data mata pelajaran SMP berhasil diimport. Total: ' . $import->getRowCount() . ' data.');
                $this->closeImportModal();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new MapelSmpExport(), 'data-mapel-smp-' . date('Y-m-d-His') . '.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new MapelSmpTemplateExport(), 'template-import-mapel-smp.xlsx');
    }

    public function render()
    {
        $mapels = MapelSmp::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_mapel', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_mapel', 'like', '%' . $this->search . '%')
                        ->orWhere('kelompok', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.mapel-smp-management', [
            'mapels' => $mapels,
        ])->layout('layouts.admin', ['header' => 'Data Mata Pelajaran SMP']);
    }
}
