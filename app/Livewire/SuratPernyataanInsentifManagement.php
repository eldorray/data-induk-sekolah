<?php

namespace App\Livewire;

use App\Models\SuratPernyataanInsentif;
use App\Models\GuruMi;
use App\Models\GuruSmp;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SuratPernyataanInsentifManagement extends Component
{
    use WithPagination;

    // Tab: 'buat' or 'riwayat'
    public string $activeTab = 'buat';

    // Search for guru list
    public string $searchGuru = '';
    public string $filterJenjang = 'mi';

    // Shared form fields
    public string $jabatan = 'Guru';
    public string $unit_kerja = '';
    public string $alamat_unit_kerja = '';
    public string $sumber_insentif = 'APBD Kota Tangerang';
    public string $bulan_tahun = '';
    public ?string $tanggal_surat = null;

    // Selected guru IDs
    public array $selectedGuruIds = [];
    public bool $selectAll = false;

    // Riwayat search & filter
    public string $searchRiwayat = '';
    public string $filterBulanTahun = '';

    // Delete
    public bool $showDeleteModal = false;
    public bool $showDeleteAllModal = false;
    public ?int $deleteId = null;

    public function mount(): void
    {
        $settings = SchoolSetting::getAll();
        $this->unit_kerja = $settings['nama_sekolah'] ?? '';
        $this->alamat_unit_kerja = trim(($settings['alamat'] ?? '') . ', ' . ($settings['kelurahan'] ?? '') . ' ' . ($settings['kecamatan'] ?? '') . ' ' . ($settings['kota'] ?? ''));
        $this->tanggal_surat = date('Y-m-d');

        $bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $this->bulan_tahun = $bulanNames[date('n') - 1] . ' ' . date('Y');
    }

    public function updatedFilterJenjang(): void
    {
        $this->selectedGuruIds = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatingSearchGuru(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $model = $this->filterJenjang === 'mi' ? GuruMi::class : GuruSmp::class;
            $this->selectedGuruIds = $model::active()
                ->when($this->searchGuru, function ($query) {
                    $query->where(function ($q) {
                        $q->where('full_name', 'like', '%' . $this->searchGuru . '%')
                            ->orWhere('nip', 'like', '%' . $this->searchGuru . '%')
                            ->orWhere('nuptk', 'like', '%' . $this->searchGuru . '%');
                    });
                })
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedGuruIds = [];
        }
    }

    public function generateSurat(): void
    {
        $this->validate([
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:255',
            'sumber_insentif' => 'required|string|max:255',
            'bulan_tahun' => 'required|string|max:50',
            'tanggal_surat' => 'required|date',
        ]);

        if (empty($this->selectedGuruIds)) {
            session()->flash('error', 'Pilih minimal satu guru.');
            return;
        }

        $guru_type = $this->filterJenjang === 'mi' ? 'guru_mi' : 'guru_smp';
        $count = 0;

        foreach ($this->selectedGuruIds as $guruId) {
            SuratPernyataanInsentif::create([
                'guru_id' => $guruId,
                'guru_type' => $guru_type,
                'jabatan' => $this->jabatan,
                'unit_kerja' => $this->unit_kerja,
                'alamat_unit_kerja' => $this->alamat_unit_kerja,
                'sumber_insentif' => $this->sumber_insentif,
                'bulan_tahun' => $this->bulan_tahun,
                'tanggal_surat' => $this->tanggal_surat,
            ]);
            $count++;
        }

        $this->selectedGuruIds = [];
        $this->selectAll = false;
        session()->flash('success', "Berhasil membuat {$count} surat pernyataan insentif.");
        $this->activeTab = 'riwayat';
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SuratPernyataanInsentif::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        session()->flash('success', 'Surat pernyataan insentif berhasil dihapus.');
    }

    public function openDeleteAllModal(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        $query = SuratPernyataanInsentif::query()
            ->when($this->filterBulanTahun, fn($q) => $q->where('bulan_tahun', $this->filterBulanTahun));
        $count = $query->count();
        $query->delete();
        $this->showDeleteAllModal = false;
        session()->flash('success', "Berhasil menghapus {$count} surat pernyataan insentif.");
    }

    public function closeModal(): void
    {
        $this->showDeleteModal = false;
        $this->showDeleteAllModal = false;
        $this->deleteId = null;
    }

    public function render()
    {
        // Guru list for "buat" tab
        $model = $this->filterJenjang === 'mi' ? GuruMi::class : GuruSmp::class;
        $gurus = $model::active()
            ->when($this->searchGuru, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', '%' . $this->searchGuru . '%')
                        ->orWhere('nip', 'like', '%' . $this->searchGuru . '%')
                        ->orWhere('nuptk', 'like', '%' . $this->searchGuru . '%');
                });
            })
            ->orderBy('full_name')
            ->get();

        // Riwayat for "riwayat" tab
        $riwayat = SuratPernyataanInsentif::query()
            ->when($this->filterBulanTahun, fn($q) => $q->where('bulan_tahun', $this->filterBulanTahun))
            ->when($this->searchRiwayat, function ($query) {
                $query->where('unit_kerja', 'like', '%' . $this->searchRiwayat . '%')
                    ->orWhere('bulan_tahun', 'like', '%' . $this->searchRiwayat . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Eager-load guru names for riwayat
        $riwayat->transform(function ($surat) {
            $surat->guru_nama = $surat->guru_model?->full_name_with_title ?? '-';
            return $surat;
        });

        // Get distinct bulan_tahun values for filter dropdown
        $bulanTahunOptions = SuratPernyataanInsentif::distinct()->pluck('bulan_tahun')->sort()->values();

        return view('livewire.surat-pernyataan-insentif-management', [
            'gurus' => $gurus,
            'riwayat' => $riwayat,
            'bulanTahunOptions' => $bulanTahunOptions,
        ])->layout('layouts.admin', ['header' => 'Surat Pernyataan Insentif']);
    }
}
