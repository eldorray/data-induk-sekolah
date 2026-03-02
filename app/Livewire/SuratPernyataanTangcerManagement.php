<?php

namespace App\Livewire;

use App\Models\SuratPernyataanTangcer;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SuratPernyataanTangcerManagement extends Component
{
    use WithPagination;

    // Tab: 'buat' or 'riwayat'
    public string $activeTab = 'buat';

    // Search for siswa list
    public string $searchSiswa = '';
    public string $filterJenjang = 'mi';

    // Shared form fields
    public string $nomor_surat = '';
    public string $tahun_anggaran = '2025';
    public string $semester = 'Genap';
    public string $isi_surat = '';
    public string $isi_tujuan = '';
    public ?string $tanggal_surat = null;

    // Selected siswa IDs
    public array $selectedSiswaIds = [];
    public bool $selectAll = false;

    // Riwayat search & filter
    public string $searchRiwayat = '';
    public string $filterTahunAnggaran = '';

    // Delete
    public bool $showDeleteModal = false;
    public bool $showDeleteAllModal = false;
    public ?int $deleteId = null;

    // Edit
    public bool $showEditModal = false;
    public ?int $editId = null;
    public string $edit_nomor_surat = '';
    public string $edit_tahun_anggaran = '';
    public string $edit_semester = '';
    public string $edit_isi_surat = '';
    public string $edit_isi_tujuan = '';
    public ?string $edit_tanggal_surat = null;

    public function mount(): void
    {
        $settings = SchoolSetting::getAll();
        $namaSekolah = $settings['nama_sekolah'] ?? 'Madrasah';
        $this->tanggal_surat = date('Y-m-d');

        // Default isi surat
        $this->isi_surat = "Dengan ini menerangkan bahwa nama-nama siswa tersebut dalam lampiran dibawah ini Adalah benar siswa {$namaSekolah} yang ditetapkan sebagai Siswa Madrasah Penerima Bantuan Sosial Program Tangerang Cerdas (TANGCER) Tahun Anggaran {$this->tahun_anggaran} Semester {$this->semester}";

        $this->isi_tujuan = "Demikian surat keterangan ini dibuat untuk digunakan sebagai salah satu persyaratan pembuatan Buku Rekening BJB untuk mencairkan Dana Bantuan Sosial Program Tangerang Cerdas (TANGCER) pada Bank Penyalur.";
    }

    public function updatedFilterJenjang(): void
    {
        $this->selectedSiswaIds = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingSearchSiswa(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $model = $this->filterJenjang === 'mi' ? SiswaMi::class : SiswaSmp::class;
            $this->selectedSiswaIds = $model::query()
                ->when($this->searchSiswa, function ($query) {
                    $query->where(function ($q) {
                        $q->where('nama_lengkap', 'like', '%' . $this->searchSiswa . '%')
                            ->orWhere('nisn', 'like', '%' . $this->searchSiswa . '%');
                    });
                })
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedSiswaIds = [];
        }
    }

    public function generateSurat(): void
    {
        $this->validate([
            'nomor_surat' => 'required|string|max:100',
            'tahun_anggaran' => 'required|string|max:50',
            'semester' => 'required|string|max:50',
            'isi_surat' => 'required|string',
            'isi_tujuan' => 'required|string',
            'tanggal_surat' => 'required|date',
        ]);

        if (empty($this->selectedSiswaIds)) {
            session()->flash('error', 'Pilih minimal satu siswa.');
            return;
        }

        $siswa_type = $this->filterJenjang === 'mi' ? 'siswa_mi' : 'siswa_smp';
        $count = 0;

        foreach ($this->selectedSiswaIds as $siswaId) {
            SuratPernyataanTangcer::create([
                'siswa_id' => $siswaId,
                'siswa_type' => $siswa_type,
                'nomor_surat' => $this->nomor_surat,
                'tahun_anggaran' => $this->tahun_anggaran,
                'semester' => $this->semester,
                'isi_surat' => $this->isi_surat,
                'isi_tujuan' => $this->isi_tujuan,
                'tanggal_surat' => $this->tanggal_surat,
            ]);
            $count++;
        }

        $this->selectedSiswaIds = [];
        $this->selectAll = false;
        session()->flash('success', "Berhasil membuat {$count} surat pernyataan TANGCER.");
        $this->activeTab = 'riwayat';
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SuratPernyataanTangcer::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        session()->flash('success', 'Surat pernyataan TANGCER berhasil dihapus.');
    }

    public function openDeleteAllModal(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        $query = SuratPernyataanTangcer::query()
            ->when($this->filterTahunAnggaran, fn($q) => $q->where('tahun_anggaran', $this->filterTahunAnggaran));
        $count = $query->count();
        $query->delete();
        $this->showDeleteAllModal = false;
        session()->flash('success', "Berhasil menghapus {$count} surat pernyataan TANGCER.");
    }

    public function closeModal(): void
    {
        $this->showDeleteModal = false;
        $this->showDeleteAllModal = false;
        $this->showEditModal = false;
        $this->deleteId = null;
        $this->editId = null;
    }

    public function openEditModal(int $id): void
    {
        $surat = SuratPernyataanTangcer::findOrFail($id);
        $this->editId = $id;
        $this->edit_nomor_surat = $surat->nomor_surat;
        $this->edit_tahun_anggaran = $surat->tahun_anggaran;
        $this->edit_semester = $surat->semester;
        $this->edit_isi_surat = $surat->isi_surat ?? '';
        $this->edit_isi_tujuan = $surat->isi_tujuan ?? '';
        $this->edit_tanggal_surat = $surat->tanggal_surat?->format('Y-m-d');
        $this->showEditModal = true;
    }

    public function updateSurat(): void
    {
        $this->validate([
            'edit_nomor_surat' => 'required|string|max:100',
            'edit_tahun_anggaran' => 'required|string|max:50',
            'edit_semester' => 'required|string|max:50',
            'edit_isi_surat' => 'required|string',
            'edit_isi_tujuan' => 'required|string',
            'edit_tanggal_surat' => 'required|date',
        ]);

        $surat = SuratPernyataanTangcer::findOrFail($this->editId);
        $surat->update([
            'nomor_surat' => $this->edit_nomor_surat,
            'tahun_anggaran' => $this->edit_tahun_anggaran,
            'semester' => $this->edit_semester,
            'isi_surat' => $this->edit_isi_surat,
            'isi_tujuan' => $this->edit_isi_tujuan,
            'tanggal_surat' => $this->edit_tanggal_surat,
        ]);

        $this->showEditModal = false;
        $this->editId = null;
        session()->flash('success', 'Surat pernyataan TANGCER berhasil diperbarui.');
    }

    public function render()
    {
        // Siswa list for "buat" tab
        $model = $this->filterJenjang === 'mi' ? SiswaMi::class : SiswaSmp::class;
        $siswas = $model::query()
            ->when($this->searchSiswa, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->searchSiswa . '%')
                        ->orWhere('nisn', 'like', '%' . $this->searchSiswa . '%');
                });
            })
            ->orderBy('nama_lengkap')
            ->get();

        // Riwayat for "riwayat" tab
        $riwayat = SuratPernyataanTangcer::query()
            ->when($this->filterTahunAnggaran, fn($q) => $q->where('tahun_anggaran', $this->filterTahunAnggaran))
            ->when($this->searchRiwayat, function ($query) {
                $query->where('nomor_surat', 'like', '%' . $this->searchRiwayat . '%')
                    ->orWhere('tahun_anggaran', 'like', '%' . $this->searchRiwayat . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Eager-load siswa names for riwayat
        $riwayat->transform(function ($surat) {
            $surat->siswa_nama = $surat->siswa_model?->nama_lengkap ?? '-';
            return $surat;
        });

        // Get distinct tahun_anggaran values for filter dropdown
        $tahunAnggaranOptions = SuratPernyataanTangcer::distinct()->pluck('tahun_anggaran')->sort()->values();

        return view('livewire.surat-pernyataan-tangcer-management', [
            'siswas' => $siswas,
            'riwayat' => $riwayat,
            'tahunAnggaranOptions' => $tahunAnggaranOptions,
        ])->layout('layouts.admin', ['header' => 'Surat Pernyataan TANGCER']);
    }
}
