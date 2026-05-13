<?php

namespace App\Livewire\NilaiIjazahKelas6;

use App\Models\NilaiIjazahTahunAjaran;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['header' => 'Nilai Ijazah Kelas 6'])]
#[Title('Nilai Ijazah Kelas 6')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    // Modal state
    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $isEditing = false;

    // Form data
    public ?int $tahunAjaranId = null;

    public string $nama_tahun_ajaran = '';

    public bool $status = true;

    public string $keterangan = '';

    protected function rules(): array
    {
        return [
            'nama_tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
                'unique:nilai_ijazah_tahun_ajarans,nama_tahun_ajaran,'.($this->tahunAjaranId ?? 'NULL').',id',
            ],
            'status' => 'boolean',
            'keterangan' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'nama_tahun_ajaran.required' => 'Nama tahun ajaran wajib diisi.',
        'nama_tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY, contoh 2025/2026.',
        'nama_tahun_ajaran.unique' => 'Tahun ajaran ini sudah ada.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nama_tahun_ajaran = $this->guessCurrentTahunAjaran();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $tahun = NilaiIjazahTahunAjaran::findOrFail($id);
        $this->tahunAjaranId = $tahun->id;
        $this->nama_tahun_ajaran = $tahun->nama_tahun_ajaran;
        $this->status = (bool) $tahun->status;
        $this->keterangan = $tahun->keterangan ?? '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $tahun = NilaiIjazahTahunAjaran::findOrFail($this->tahunAjaranId);
            $tahun->update($validated);
            session()->flash('success', 'Tahun ajaran berhasil diperbarui.');
        } else {
            NilaiIjazahTahunAjaran::create($validated);
            session()->flash('success', 'Tahun ajaran berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->tahunAjaranId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $tahun = NilaiIjazahTahunAjaran::withCount('scores')->findOrFail($this->tahunAjaranId);

        if ($tahun->scores_count > 0) {
            session()->flash('error', 'Tahun ajaran tidak bisa dihapus karena sudah memiliki data nilai.');
            $this->showDeleteModal = false;
            $this->tahunAjaranId = null;

            return;
        }

        $tahun->delete();
        $this->showDeleteModal = false;
        $this->tahunAjaranId = null;
        session()->flash('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->tahunAjaranId = null;
        $this->nama_tahun_ajaran = '';
        $this->status = true;
        $this->keterangan = '';
        $this->resetErrorBag();
    }

    private function guessCurrentTahunAjaran(): string
    {
        $bulan = (int) date('n');
        $tahun = (int) date('Y');
        if ($bulan >= 7) {
            return $tahun.'/'.($tahun + 1);
        }

        return ($tahun - 1).'/'.$tahun;
    }

    public function render()
    {
        $tahunAjarans = NilaiIjazahTahunAjaran::query()
            ->withCount([
                'scores as jumlah_siswa_count' => function ($query) {
                    $query->select(DB::raw('count(distinct siswa_id)'));
                },
            ])
            ->when($this->search, function ($query) {
                $query->where('nama_tahun_ajaran', 'like', '%'.$this->search.'%')
                    ->orWhere('keterangan', 'like', '%'.$this->search.'%');
            })
            ->orderBy('nama_tahun_ajaran', 'desc')
            ->paginate($this->perPage);

        return view('livewire.nilai-ijazah-kelas-6.index', [
            'tahunAjarans' => $tahunAjarans,
        ]);
    }
}
