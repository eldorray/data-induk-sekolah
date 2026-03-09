<?php

namespace App\Livewire;

use App\Models\MutasiSiswa;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class MutasiSiswaManagement extends Component
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
    public ?int $mutasiId = null;
    public ?int $siswa_id = null;
    public string $siswa_type = 'siswa_mi';
    public string $nomor_surat = '';
    public ?string $tanggal_surat = null;
    public ?string $tanggal_mutasi = null;
    public string $jenis_mutasi = 'pindah';
    public string $alasan_mutasi = '';
    public string $sekolah_tujuan = '';
    public string $npsn_tujuan = '';
    public string $alamat_tujuan = '';
    public string $status = 'draft';

    // Siswa search
    public string $searchSiswa = '';
    public string $filterJenjang = 'mi';
    public array $siswaResults = [];
    public ?array $selectedSiswa = null;

    protected function rules(): array
    {
        $siswaTable = $this->siswa_type === 'siswa_mi' ? 'siswa_mis' : 'siswa_smps';

        return [
            'siswa_id' => 'required|exists:' . $siswaTable . ',id',
            'siswa_type' => 'required|in:siswa_mi,siswa_smp',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_mutasi' => 'required|date',
            'jenis_mutasi' => 'required|in:pindah,keluar',
            'alasan_mutasi' => 'required|string',
            'sekolah_tujuan' => 'nullable|string|max:255',
            'npsn_tujuan' => 'nullable|string|max:20',
            'alamat_tujuan' => 'nullable|string',
            'status' => 'required|in:draft,disetujui,dibatalkan',
        ];
    }

    protected $messages = [
        'siswa_id.required' => 'Siswa wajib dipilih.',
        'nomor_surat.required' => 'Nomor surat wajib diisi.',
        'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
        'tanggal_mutasi.required' => 'Tanggal mutasi wajib diisi.',
        'alasan_mutasi.required' => 'Alasan mutasi wajib diisi.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterJenjang(): void
    {
        $this->siswaResults = [];
        $this->searchSiswa = '';
    }

    public function updatedSearchSiswa(): void
    {
        if (strlen($this->searchSiswa) >= 2) {
            $model = $this->filterJenjang === 'mi' ? SiswaMi::class : SiswaSmp::class;

            $this->siswaResults = $model::where('status', 'Aktif')
                ->where(function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->searchSiswa . '%')
                        ->orWhere('nisn', 'like', '%' . $this->searchSiswa . '%')
                        ->orWhere('nik', 'like', '%' . $this->searchSiswa . '%');
                })
                ->limit(10)
                ->get()
                ->map(fn($s) => [
                    'id' => $s->id,
                    'nama' => $s->nama_lengkap,
                    'nisn' => $s->nisn,
                    'kelas' => $s->tingkat_rombel,
                    'jenjang' => $this->filterJenjang,
                ])
                ->toArray();
        } else {
            $this->siswaResults = [];
        }
    }

    public function selectSiswa(int $id): void
    {
        $model = $this->filterJenjang === 'mi' ? SiswaMi::class : SiswaSmp::class;
        $siswa = $model::find($id);

        if ($siswa) {
            $this->siswa_id = $siswa->id;
            $this->siswa_type = $this->filterJenjang === 'mi' ? 'siswa_mi' : 'siswa_smp';
            $this->selectedSiswa = [
                'id' => $siswa->id,
                'nama' => $siswa->nama_lengkap,
                'nisn' => $siswa->nisn,
                'kelas' => $siswa->tingkat_rombel,
                'jenjang' => strtoupper($this->filterJenjang),
            ];
            $this->searchSiswa = '';
            $this->siswaResults = [];
        }
    }

    public function clearSiswa(): void
    {
        $this->siswa_id = null;
        $this->siswa_type = 'siswa_mi';
        $this->selectedSiswa = null;
        $this->searchSiswa = '';
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_surat = MutasiSiswa::generateNomorSurat();
        $this->tanggal_surat = date('Y-m-d');
        $this->tanggal_mutasi = date('Y-m-d');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $mutasi = MutasiSiswa::with('siswa')->findOrFail($id);
        $this->mutasiId = $mutasi->id;
        $this->siswa_id = $mutasi->siswa_id;
        $this->siswa_type = $mutasi->siswa_type ?? 'siswa_mi';
        $this->filterJenjang = in_array($mutasi->siswa_type, ['siswa_mi', 'App\\Models\\SiswaMi']) ? 'mi' : 'smp';
        $this->selectedSiswa = [
            'id' => $mutasi->siswa->id,
            'nama' => $mutasi->siswa->nama_lengkap,
            'nisn' => $mutasi->siswa->nisn,
            'kelas' => $mutasi->siswa->tingkat_rombel,
            'jenjang' => strtoupper($this->filterJenjang),
        ];
        $this->nomor_surat = $mutasi->nomor_surat;
        $this->tanggal_surat = $mutasi->tanggal_surat->format('Y-m-d');
        $this->tanggal_mutasi = $mutasi->tanggal_mutasi->format('Y-m-d');
        $this->jenis_mutasi = $mutasi->jenis_mutasi;
        $this->alasan_mutasi = $mutasi->alasan_mutasi;
        $this->sekolah_tujuan = $mutasi->sekolah_tujuan ?? '';
        $this->npsn_tujuan = $mutasi->npsn_tujuan ?? '';
        $this->alamat_tujuan = $mutasi->alamat_tujuan ?? '';
        $this->status = $mutasi->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $mutasi = MutasiSiswa::findOrFail($this->mutasiId);
            $mutasi->update($validated);

            // Update status siswa jika disetujui
            if ($validated['status'] === 'disetujui') {
                $model = $validated['siswa_type'] === 'siswa_mi' ? SiswaMi::class : SiswaSmp::class;
                $siswa = $model::find($validated['siswa_id']);
                if ($siswa) {
                    $siswa->update([
                        'status' => $validated['jenis_mutasi'] === 'pindah' ? 'Pindah' : 'Keluar'
                    ]);
                }
            }

            session()->flash('success', 'Data mutasi berhasil diperbarui.');
        } else {
            $mutasi = MutasiSiswa::create($validated);

            // Update status siswa jika langsung disetujui
            if ($validated['status'] === 'disetujui') {
                $model = $validated['siswa_type'] === 'siswa_mi' ? SiswaMi::class : SiswaSmp::class;
                $siswa = $model::find($validated['siswa_id']);
                if ($siswa) {
                    $siswa->update([
                        'status' => $validated['jenis_mutasi'] === 'pindah' ? 'Pindah' : 'Keluar'
                    ]);
                }
            }

            session()->flash('success', 'Data mutasi berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->mutasiId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $mutasi = MutasiSiswa::findOrFail($this->mutasiId);
        $mutasi->delete();
        $this->showDeleteModal = false;
        $this->mutasiId = null;
        session()->flash('success', 'Data mutasi berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->mutasiId = null;
        $this->siswa_id = null;
        $this->siswa_type = 'siswa_mi';
        $this->selectedSiswa = null;
        $this->nomor_surat = '';
        $this->tanggal_surat = null;
        $this->tanggal_mutasi = null;
        $this->jenis_mutasi = 'pindah';
        $this->alasan_mutasi = '';
        $this->sekolah_tujuan = '';
        $this->npsn_tujuan = '';
        $this->alamat_tujuan = '';
        $this->status = 'draft';
        $this->searchSiswa = '';
        $this->filterJenjang = 'mi';
        $this->siswaResults = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $mutasis = MutasiSiswa::with('siswa')
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%');
                })
                ->orWhere('nomor_surat', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.mutasi-siswa-management', [
            'mutasis' => $mutasis,
        ])->layout('layouts.admin', ['header' => 'Mutasi Siswa']);
    }
}
