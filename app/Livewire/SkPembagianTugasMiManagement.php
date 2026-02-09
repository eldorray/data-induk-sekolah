<?php

namespace App\Livewire;

use App\Models\SkPembagianTugasMi;
use App\Models\PembagianTugasDetailMi;
use App\Models\GuruMi;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SkPembagianTugasMiManagement extends Component
{
    use WithPagination;

    // Search and filter
    public string $search = '';
    public string $filterStatus = '';
    public int $perPage = 10;

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public bool $showAddGuruModal = false;
    public bool $isEditing = false;

    // Form data - SK Header
    public ?int $skId = null;
    public string $nomor_sk = '';
    public ?string $tanggal_sk = null;
    public string $tahun_pelajaran = '';
    public string $semester = '1';
    public string $penandatangan_nama = '';
    public ?string $penandatangan_nip = null;
    public string $penandatangan_jabatan = 'Kepala Madrasah';
    public string $tempat_penetapan = 'Tangerang';
    public ?string $tanggal_penetapan = null;
    public string $status = 'draft';

    // Form data - Detail Guru
    public array $tugasDetails = [];

    // Add Guru Modal
    public string $searchGuru = '';
    public array $guruResults = [];
    public ?int $selectedGuruId = null;
    public string $detail_jabatan = 'Guru';
    public string $detail_jenis_guru = 'Guru Kelas';
    public string $detail_tugas_mengajar = '';
    public ?int $detail_jumlah_jam = null;

    protected function rules(): array
    {
        return [
            'nomor_sk' => 'required|string|max:100',
            'tanggal_sk' => 'required|date',
            'tahun_pelajaran' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'penandatangan_nama' => 'required|string|max:255',
            'penandatangan_nip' => 'nullable|string|max:30',
            'penandatangan_jabatan' => 'required|string|max:100',
            'tempat_penetapan' => 'required|string|max:100',
            'tanggal_penetapan' => 'required|date',
            'status' => 'required|in:draft,aktif,tidak_aktif',
        ];
    }

    protected $messages = [
        'nomor_sk.required' => 'Nomor SK wajib diisi.',
        'tanggal_sk.required' => 'Tanggal SK wajib diisi.',
        'tahun_pelajaran.required' => 'Tahun pelajaran wajib diisi.',
        'penandatangan_nama.required' => 'Nama penandatangan wajib diisi.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSearchGuru(): void
    {
        if (strlen($this->searchGuru) >= 2) {
            // Get IDs of already added gurus
            $existingGuruIds = collect($this->tugasDetails)->pluck('guru_mi_id')->toArray();

            $this->guruResults = GuruMi::query()
                ->where('is_active', true)
                ->whereNotIn('id', $existingGuruIds)
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
                    'status' => $g->status_pegawai,
                ])
                ->toArray();
        } else {
            $this->guruResults = [];
        }
    }

    public function selectGuruForAdd(int $id): void
    {
        $this->selectedGuruId = $id;
        $guru = collect($this->guruResults)->firstWhere('id', $id);
        $this->searchGuru = $guru['nama'] ?? '';
        $this->guruResults = [];
    }

    public function addGuruToList(): void
    {
        $this->validate([
            'selectedGuruId' => 'required|exists:guru_mis,id',
            'detail_jabatan' => 'required|string|max:100',
            'detail_jenis_guru' => 'required|string|max:100',
            'detail_tugas_mengajar' => 'required|string|max:255',
            'detail_jumlah_jam' => 'nullable|integer|min:0|max:99',
        ], [
            'selectedGuruId.required' => 'Silakan pilih guru terlebih dahulu.',
            'detail_tugas_mengajar.required' => 'Tugas mengajar wajib diisi.',
        ]);

        $guru = GuruMi::find($this->selectedGuruId);

        $this->tugasDetails[] = [
            'guru_mi_id' => $guru->id,
            'guru_nama' => $guru->full_name_with_title,
            'jabatan' => $this->detail_jabatan,
            'jenis_guru' => $this->detail_jenis_guru,
            'tugas_mengajar' => $this->detail_tugas_mengajar,
            'jumlah_jam' => $this->detail_jumlah_jam,
            'sort_order' => count($this->tugasDetails) + 1,
        ];

        $this->closeAddGuruModal();
    }

    public function removeGuruFromList(int $index): void
    {
        unset($this->tugasDetails[$index]);
        $this->tugasDetails = array_values($this->tugasDetails);

        // Reorder
        foreach ($this->tugasDetails as $i => &$detail) {
            $detail['sort_order'] = $i + 1;
        }
    }

    public function moveGuruUp(int $index): void
    {
        if ($index > 0) {
            $temp = $this->tugasDetails[$index];
            $this->tugasDetails[$index] = $this->tugasDetails[$index - 1];
            $this->tugasDetails[$index - 1] = $temp;

            // Update sort_order
            $this->tugasDetails[$index]['sort_order'] = $index + 1;
            $this->tugasDetails[$index - 1]['sort_order'] = $index;
        }
    }

    public function moveGuruDown(int $index): void
    {
        if ($index < count($this->tugasDetails) - 1) {
            $temp = $this->tugasDetails[$index];
            $this->tugasDetails[$index] = $this->tugasDetails[$index + 1];
            $this->tugasDetails[$index + 1] = $temp;

            // Update sort_order
            $this->tugasDetails[$index]['sort_order'] = $index + 1;
            $this->tugasDetails[$index + 1]['sort_order'] = $index + 2;
        }
    }

    public function openAddGuruModal(): void
    {
        $this->selectedGuruId = null;
        $this->searchGuru = '';
        $this->guruResults = [];
        $this->detail_jabatan = 'Guru';
        $this->detail_jenis_guru = 'Guru Kelas';
        $this->detail_tugas_mengajar = '';
        $this->detail_jumlah_jam = null;
        $this->showAddGuruModal = true;
    }

    public function closeAddGuruModal(): void
    {
        $this->showAddGuruModal = false;
        $this->selectedGuruId = null;
        $this->searchGuru = '';
        $this->guruResults = [];
        $this->detail_jabatan = 'Guru';
        $this->detail_jenis_guru = 'Guru Kelas';
        $this->detail_tugas_mengajar = '';
        $this->detail_jumlah_jam = null;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_sk = SkPembagianTugasMi::generateNomorSk();
        $this->tanggal_sk = date('Y-m-d');
        $this->tahun_pelajaran = SkPembagianTugasMi::getCurrentTahunPelajaran();
        $this->semester = SkPembagianTugasMi::getCurrentSemester();
        $this->tanggal_penetapan = date('Y-m-d');
        $this->penandatangan_nama = SchoolSetting::get('nama_kepala', '');
        $this->penandatangan_nip = SchoolSetting::get('nip_kepala', '');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $sk = SkPembagianTugasMi::with(['details.guru'])->findOrFail($id);
        $this->skId = $sk->id;
        $this->nomor_sk = $sk->nomor_sk;
        $this->tanggal_sk = $sk->tanggal_sk?->format('Y-m-d');
        $this->tahun_pelajaran = $sk->tahun_pelajaran;
        $this->semester = $sk->semester;
        $this->penandatangan_nama = $sk->penandatangan_nama;
        $this->penandatangan_nip = $sk->penandatangan_nip;
        $this->penandatangan_jabatan = $sk->penandatangan_jabatan;
        $this->tempat_penetapan = $sk->tempat_penetapan;
        $this->tanggal_penetapan = $sk->tanggal_penetapan?->format('Y-m-d');
        $this->status = $sk->status;

        // Load details
        $this->tugasDetails = $sk->details->map(fn($d) => [
            'id' => $d->id,
            'guru_mi_id' => $d->guru_mi_id,
            'guru_nama' => $d->guru->full_name_with_title,
            'jabatan' => $d->jabatan,
            'jenis_guru' => $d->jenis_guru,
            'tugas_mengajar' => $d->tugas_mengajar,
            'jumlah_jam' => $d->jumlah_jam,
            'sort_order' => $d->sort_order,
        ])->toArray();

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function openDetailModal(int $id): void
    {
        $this->skId = $id;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->skId = null;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $sk = SkPembagianTugasMi::findOrFail($this->skId);
            $sk->update($validated);

            // Delete existing details
            $sk->details()->delete();
        } else {
            $sk = SkPembagianTugasMi::create($validated);
        }

        // Save details
        foreach ($this->tugasDetails as $detail) {
            PembagianTugasDetailMi::create([
                'sk_pembagian_tugas_mi_id' => $sk->id,
                'guru_mi_id' => $detail['guru_mi_id'],
                'jabatan' => $detail['jabatan'],
                'jenis_guru' => $detail['jenis_guru'],
                'tugas_mengajar' => $detail['tugas_mengajar'],
                'jumlah_jam' => $detail['jumlah_jam'],
                'sort_order' => $detail['sort_order'],
            ]);
        }

        session()->flash('success', $this->isEditing
            ? 'SK Pembagian Tugas berhasil diperbarui.'
            : 'SK Pembagian Tugas berhasil ditambahkan.');

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->skId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $sk = SkPembagianTugasMi::findOrFail($this->skId);
        $sk->delete();
        $this->showDeleteModal = false;
        $this->skId = null;
        session()->flash('success', 'SK Pembagian Tugas berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showAddGuruModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->skId = null;
        $this->nomor_sk = '';
        $this->tanggal_sk = null;
        $this->tahun_pelajaran = '';
        $this->semester = '1';
        $this->penandatangan_nama = '';
        $this->penandatangan_nip = null;
        $this->penandatangan_jabatan = 'Kepala Madrasah';
        $this->tempat_penetapan = 'Tangerang';
        $this->tanggal_penetapan = null;
        $this->status = 'draft';
        $this->tugasDetails = [];
        $this->searchGuru = '';
        $this->guruResults = [];
        $this->selectedGuruId = null;
        $this->resetValidation();
    }

    /**
     * Get jabatan options
     */
    public static function getJabatanOptions(): array
    {
        return [
            'Kepala Madrasah' => 'Kepala Madrasah',
            'Guru' => 'Guru',
            'Operator/Guru' => 'Operator/Guru',
            'TU' => 'TU',
        ];
    }

    /**
     * Get jenis guru options
     */
    public static function getJenisGuruOptions(): array
    {
        return [
            'Kamad' => 'Kamad',
            'Guru Kelas' => 'Guru Kelas',
            'Guru Bidang' => 'Guru Bidang',
            'Operator/Guru Bidang' => 'Operator/Guru Bidang',
            'TU' => 'TU',
        ];
    }

    public function render()
    {
        $skList = SkPembagianTugasMi::query()
            ->withCount('details')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_sk', 'like', '%' . $this->search . '%')
                        ->orWhere('tahun_pelajaran', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $detailSk = null;
        if ($this->showDetailModal && $this->skId) {
            $detailSk = SkPembagianTugasMi::with(['details.guru'])->find($this->skId);
        }

        return view('livewire.sk-pembagian-tugas-mi-management', [
            'skList' => $skList,
            'detailSk' => $detailSk,
            'jabatanOptions' => self::getJabatanOptions(),
            'jenisGuruOptions' => self::getJenisGuruOptions(),
        ])->layout('layouts.admin', ['header' => 'SK Pembagian Tugas Mengajar MI']);
    }
}
