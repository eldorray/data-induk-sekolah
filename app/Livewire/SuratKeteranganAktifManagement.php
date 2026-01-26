<?php

namespace App\Livewire;

use App\Models\SuratKeteranganAktif;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use Livewire\Component;
use Livewire\WithPagination;

class SuratKeteranganAktifManagement extends Component
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
    public ?int $suratId = null;
    public ?int $siswa_id = null;
    public string $siswa_type = 'App\\Models\\SiswaMi';
    public string $nomor_surat = '';
    public ?string $tanggal_surat = null;
    public string $keperluan = '';
    public string $tahun_pelajaran = '';
    public string $semester = 'ganjil';
    public string $status = 'draft';

    // Siswa search
    public string $searchSiswa = '';
    public string $filterJenjang = 'mi'; // mi atau smp
    public array $siswaResults = [];
    public ?array $selectedSiswa = null;

    protected function rules(): array
    {
        $siswaTable = $this->siswa_type === 'App\\Models\\SiswaMi' ? 'siswa_mis' : 'siswa_smps';

        return [
            'siswa_id' => 'required|exists:' . $siswaTable . ',id',
            'siswa_type' => 'required|in:App\\Models\\SiswaMi,App\\Models\\SiswaSmp',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'keperluan' => 'nullable|string|max:255',
            'tahun_pelajaran' => 'nullable|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:draft,disetujui,dibatalkan',
        ];
    }

    protected $messages = [
        'siswa_id.required' => 'Siswa wajib dipilih.',
        'nomor_surat.required' => 'Nomor surat wajib diisi.',
        'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
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
            $this->siswa_type = $this->filterJenjang === 'mi' ? 'App\\Models\\SiswaMi' : 'App\\Models\\SiswaSmp';
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
        $this->siswa_type = 'App\\Models\\SiswaMi';
        $this->selectedSiswa = null;
        $this->searchSiswa = '';
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_surat = SuratKeteranganAktif::generateNomorSurat();
        $this->tanggal_surat = date('Y-m-d');
        $this->tahun_pelajaran = SuratKeteranganAktif::getCurrentTahunPelajaran();
        $this->semester = SuratKeteranganAktif::getCurrentSemester();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $surat = SuratKeteranganAktif::with('siswa')->findOrFail($id);
        $this->suratId = $surat->id;
        $this->siswa_id = $surat->siswa_id;
        $this->siswa_type = $surat->siswa_type;
        $this->filterJenjang = $surat->siswa_type === 'App\\Models\\SiswaMi' ? 'mi' : 'smp';
        $this->selectedSiswa = [
            'id' => $surat->siswa->id,
            'nama' => $surat->siswa->nama_lengkap,
            'nisn' => $surat->siswa->nisn,
            'kelas' => $surat->siswa->tingkat_rombel,
            'jenjang' => strtoupper($this->filterJenjang),
        ];
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
        $this->keperluan = $surat->keperluan ?? '';
        $this->tahun_pelajaran = $surat->tahun_pelajaran ?? '';
        $this->semester = $surat->semester ?? 'ganjil';
        $this->status = $surat->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $surat = SuratKeteranganAktif::findOrFail($this->suratId);
            $surat->update($validated);
            session()->flash('success', 'Surat keterangan aktif berhasil diperbarui.');
        } else {
            SuratKeteranganAktif::create($validated);
            session()->flash('success', 'Surat keterangan aktif berhasil ditambahkan.');
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
        $surat = SuratKeteranganAktif::findOrFail($this->suratId);
        $surat->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'Surat keterangan aktif berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->suratId = null;
        $this->siswa_id = null;
        $this->siswa_type = 'App\\Models\\SiswaMi';
        $this->selectedSiswa = null;
        $this->nomor_surat = '';
        $this->tanggal_surat = null;
        $this->keperluan = '';
        $this->tahun_pelajaran = '';
        $this->semester = 'ganjil';
        $this->status = 'draft';
        $this->searchSiswa = '';
        $this->filterJenjang = 'mi';
        $this->siswaResults = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $surats = SuratKeteranganAktif::with('siswa')
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

        return view('livewire.surat-keterangan-aktif-management', [
            'surats' => $surats,
        ])->layout('layouts.admin', ['header' => 'Surat Keterangan Aktif']);
    }
}
