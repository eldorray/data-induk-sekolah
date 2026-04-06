<?php

namespace App\Livewire;

use App\Models\SuratRekapPkh;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use Livewire\Component;
use Livewire\WithPagination;

class SuratRekapPkhManagement extends Component
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
    public string $siswa_type = 'siswa_mi';
    public string $nomor_surat = '';
    public ?string $tanggal_surat = null;
    public string $tahun_ajaran = '';
    public string $semester = 'genap';
    public string $format_surat = 'rekap_absensi';
    public array $bulan_rekap = [];
    public array $data_absensi = [];
    public string $nama_wali_kelas = '';
    public string $nip_wali_kelas = '';
    public string $status = 'draft';

    // Siswa search
    public string $searchSiswa = '';
    public string $filterJenjang = 'mi';
    public array $siswaResults = [];
    public ?array $selectedSiswa = null;

    // Available months
    public array $availableBulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    protected function rules(): array
    {
        $siswaTable = $this->siswa_type === 'siswa_mi' ? 'siswa_mis' : 'siswa_smps';

        return [
            'siswa_id' => 'required|exists:' . $siswaTable . ',id',
            'siswa_type' => 'required|in:siswa_mi,siswa_smp',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tahun_ajaran' => 'nullable|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'format_surat' => 'required|in:rekap_absensi,surat_keterangan',
            'bulan_rekap' => 'required|array|min:1',
            'data_absensi' => 'required|array',
            'nama_wali_kelas' => 'nullable|string|max:255',
            'nip_wali_kelas' => 'nullable|string|max:50',
            'status' => 'required|in:draft,disetujui,dibatalkan',
        ];
    }

    protected $messages = [
        'siswa_id.required' => 'Siswa wajib dipilih.',
        'nomor_surat.required' => 'Nomor surat wajib diisi.',
        'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
        'bulan_rekap.required' => 'Pilih minimal satu bulan.',
        'bulan_rekap.min' => 'Pilih minimal satu bulan.',
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

    /**
     * Toggle bulan selection and manage data_absensi accordingly
     */
    public function toggleBulan(string $bulan): void
    {
        if (in_array($bulan, $this->bulan_rekap)) {
            // Remove the month
            $this->bulan_rekap = array_values(array_filter($this->bulan_rekap, fn($b) => $b !== $bulan));
            unset($this->data_absensi[$bulan]);
        } else {
            // Add the month
            $this->bulan_rekap[] = $bulan;
            if ($this->format_surat === 'surat_keterangan') {
                $this->data_absensi[$bulan] = ['hari_efektif' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0];
            } else {
                $this->data_absensi[$bulan] = ['sakit' => 0, 'izin' => 0, 'alfa' => 0];
            }
        }
    }

    /**
     * When format changes, rebuild data_absensi structure
     */
    public function updatedFormatSurat(): void
    {
        foreach ($this->bulan_rekap as $bulan) {
            $existing = $this->data_absensi[$bulan] ?? [];
            if ($this->format_surat === 'surat_keterangan') {
                $this->data_absensi[$bulan] = [
                    'hari_efektif' => $existing['hari_efektif'] ?? 0,
                    'sakit' => $existing['sakit'] ?? 0,
                    'izin' => $existing['izin'] ?? 0,
                    'alfa' => $existing['alfa'] ?? 0,
                ];
            } else {
                $this->data_absensi[$bulan] = [
                    'sakit' => $existing['sakit'] ?? 0,
                    'izin' => $existing['izin'] ?? 0,
                    'alfa' => $existing['alfa'] ?? 0,
                ];
            }
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_surat = SuratRekapPkh::generateNomorSurat();
        $this->tanggal_surat = date('Y-m-d');
        $this->tahun_ajaran = SuratRekapPkh::getCurrentTahunAjaran();
        $this->semester = SuratRekapPkh::getCurrentSemester();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $surat = SuratRekapPkh::with('siswa')->findOrFail($id);
        $this->suratId = $surat->id;
        $this->siswa_id = $surat->siswa_id;
        $this->siswa_type = $surat->siswa_type ?? 'siswa_mi';
        $this->filterJenjang = in_array($surat->siswa_type, ['siswa_mi', 'App\\Models\\SiswaMi']) ? 'mi' : 'smp';
        $this->selectedSiswa = [
            'id' => $surat->siswa->id,
            'nama' => $surat->siswa->nama_lengkap,
            'nisn' => $surat->siswa->nisn,
            'kelas' => $surat->siswa->tingkat_rombel,
            'jenjang' => strtoupper($this->filterJenjang),
        ];
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
        $this->tahun_ajaran = $surat->tahun_ajaran ?? '';
        $this->semester = $surat->semester ?? 'genap';
        $this->format_surat = $surat->format_surat ?? 'rekap_absensi';
        $this->bulan_rekap = $surat->bulan_rekap ?? [];
        $this->data_absensi = $surat->data_absensi ?? [];
        $this->nama_wali_kelas = $surat->nama_wali_kelas ?? '';
        $this->nip_wali_kelas = $surat->nip_wali_kelas ?? '';
        $this->status = $surat->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $surat = SuratRekapPkh::findOrFail($this->suratId);
            $surat->update($validated);
            session()->flash('success', 'Surat rekap PKH berhasil diperbarui.');
        } else {
            SuratRekapPkh::create($validated);
            session()->flash('success', 'Surat rekap PKH berhasil ditambahkan.');
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
        $surat = SuratRekapPkh::findOrFail($this->suratId);
        $surat->delete();
        $this->showDeleteModal = false;
        $this->suratId = null;
        session()->flash('success', 'Surat rekap PKH berhasil dihapus.');
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
        $this->siswa_type = 'siswa_mi';
        $this->selectedSiswa = null;
        $this->nomor_surat = '';
        $this->tanggal_surat = null;
        $this->tahun_ajaran = '';
        $this->semester = 'genap';
        $this->format_surat = 'rekap_absensi';
        $this->bulan_rekap = [];
        $this->data_absensi = [];
        $this->nama_wali_kelas = '';
        $this->nip_wali_kelas = '';
        $this->status = 'draft';
        $this->searchSiswa = '';
        $this->filterJenjang = 'mi';
        $this->siswaResults = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $surats = SuratRekapPkh::with('siswa')
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

        return view('livewire.surat-rekap-pkh-management', [
            'surats' => $surats,
        ])->layout('layouts.admin', ['header' => 'Surat Rekap PKH']);
    }
}
