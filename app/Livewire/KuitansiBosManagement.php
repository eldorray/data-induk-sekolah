<?php

namespace App\Livewire;

use App\Helpers\Terbilang;
use App\Models\Kuitansi;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithPagination;

class KuitansiBosManagement extends Component
{
    use WithPagination;

    // Search & pagination
    public string $search = '';
    public int $perPage = 10;

    // Bulk selection (untuk "Cetak Terpilih")
    public array $selected = [];

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showSettingsModal = false;
    public bool $isEditing = false;

    // Form data (per kuitansi)
    public ?int $kuitansiId = null;
    public string $nomor_bukti = '';
    public string $penerima = '';
    public ?int $jumlah_uang = null;
    public string $uraian_pembayaran = '';
    public ?string $tanggal_lunas = null;

    // Pengaturan lembaga (konstanta)
    public string $set_tahun_anggaran = '';
    public string $set_nama_madrasah = '';
    public string $set_desa_kecamatan = '';
    public string $set_kabupaten = '';
    public string $set_provinsi = '';
    public string $set_sumber_dana = '';
    public string $set_format_nomor = '';
    public string $set_sudah_terima_dari = '';
    public string $set_kepala_madrasah = '';
    public string $set_bendahara_madrasah = '';

    protected function rules(): array
    {
        return [
            'nomor_bukti' => 'required|string|max:20',
            'penerima' => 'required|string|max:255',
            'jumlah_uang' => 'required|integer|min:1',
            'uraian_pembayaran' => 'required|string',
            'tanggal_lunas' => 'required|date',
        ];
    }

    protected $messages = [
        'nomor_bukti.required' => 'Nomor urut bukti wajib diisi.',
        'penerima.required' => 'Nama penerima wajib diisi.',
        'jumlah_uang.required' => 'Jumlah uang wajib diisi.',
        'jumlah_uang.min' => 'Jumlah uang harus lebih dari 0.',
        'uraian_pembayaran.required' => 'Uraian pembayaran wajib diisi.',
        'tanggal_lunas.required' => 'Tanggal lunas wajib diisi.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Preview terbilang live mengikuti jumlah_uang.
     */
    public function getTerbilangPreviewProperty(): string
    {
        return Terbilang::make($this->jumlah_uang ?? 0);
    }

    /**
     * Preview nomor bukti lengkap mengikuti nomor urut + format pengaturan.
     */
    public function getNomorBuktiPreviewProperty(): string
    {
        return Kuitansi::formatNomorBukti($this->nomor_bukti);
    }

    /**
     * Peringatan (bukan blok) bila tahun tanggal_lunas tidak sama dengan tahun anggaran.
     */
    public function getTahunWarningProperty(): ?string
    {
        if (! $this->tanggal_lunas) {
            return null;
        }

        $tahunAnggaran = SchoolSetting::get('kuitansi_tahun_anggaran', (string) date('Y'));
        $tahunLunas = substr($this->tanggal_lunas, 0, 4);

        if ($tahunLunas !== (string) $tahunAnggaran) {
            return "Tahun tanggal lunas ({$tahunLunas}) berbeda dengan tahun anggaran ({$tahunAnggaran}). Pastikan ini disengaja.";
        }

        return null;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->tanggal_lunas = date('Y-m-d');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $k = Kuitansi::findOrFail($id);
        $this->kuitansiId = $k->id;
        $this->nomor_bukti = $k->nomor_bukti;
        $this->penerima = $k->penerima;
        $this->jumlah_uang = $k->jumlah_uang;
        $this->uraian_pembayaran = $k->uraian_pembayaran;
        $this->tanggal_lunas = $k->tanggal_lunas->format('Y-m-d');
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            Kuitansi::findOrFail($this->kuitansiId)->update($validated);
            session()->flash('success', 'Kuitansi berhasil diperbarui.');
        } else {
            Kuitansi::create($validated);
            session()->flash('success', 'Kuitansi berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function openDeleteModal(int $id): void
    {
        $this->kuitansiId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        Kuitansi::findOrFail($this->kuitansiId)->delete();
        $this->selected = array_values(array_filter($this->selected, fn ($id) => (int) $id !== $this->kuitansiId));
        $this->showDeleteModal = false;
        $this->kuitansiId = null;
        session()->flash('success', 'Kuitansi berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->kuitansiId = null;
        $this->nomor_bukti = '';
        $this->penerima = '';
        $this->jumlah_uang = null;
        $this->uraian_pembayaran = '';
        $this->tanggal_lunas = null;
        $this->resetErrorBag();
    }

    // ===== Pengaturan lembaga =====

    public function openSettingsModal(): void
    {
        $s = SchoolSetting::getAll();
        $this->set_tahun_anggaran = $s['kuitansi_tahun_anggaran'] ?? '2026';
        $this->set_nama_madrasah = $s['kuitansi_nama_madrasah'] ?? '';
        $this->set_desa_kecamatan = $s['kuitansi_desa_kecamatan'] ?? '';
        $this->set_kabupaten = $s['kuitansi_kabupaten'] ?? '';
        $this->set_provinsi = $s['kuitansi_provinsi'] ?? '';
        $this->set_sumber_dana = $s['kuitansi_sumber_dana'] ?? '';
        $this->set_format_nomor = $s['kuitansi_format_nomor'] ?? '.../T1/MIDH/2026';
        $this->set_sudah_terima_dari = $s['kuitansi_sudah_terima_dari'] ?? '';
        $this->set_kepala_madrasah = $s['kuitansi_kepala_madrasah'] ?? '';
        $this->set_bendahara_madrasah = $s['kuitansi_bendahara_madrasah'] ?? '';
        $this->showSettingsModal = true;
    }

    public function saveSettings(): void
    {
        $this->validate([
            'set_tahun_anggaran' => 'required|string|max:10',
            'set_nama_madrasah' => 'required|string|max:255',
            'set_format_nomor' => 'required|string|max:100',
            'set_kepala_madrasah' => 'required|string|max:255',
            'set_bendahara_madrasah' => 'required|string|max:255',
        ]);

        SchoolSetting::set('kuitansi_tahun_anggaran', $this->set_tahun_anggaran);
        SchoolSetting::set('kuitansi_nama_madrasah', $this->set_nama_madrasah);
        SchoolSetting::set('kuitansi_desa_kecamatan', $this->set_desa_kecamatan);
        SchoolSetting::set('kuitansi_kabupaten', $this->set_kabupaten);
        SchoolSetting::set('kuitansi_provinsi', $this->set_provinsi);
        SchoolSetting::set('kuitansi_sumber_dana', $this->set_sumber_dana);
        SchoolSetting::set('kuitansi_format_nomor', $this->set_format_nomor);
        SchoolSetting::set('kuitansi_sudah_terima_dari', $this->set_sudah_terima_dari);
        SchoolSetting::set('kuitansi_kepala_madrasah', $this->set_kepala_madrasah);
        SchoolSetting::set('kuitansi_bendahara_madrasah', $this->set_bendahara_madrasah);
        SchoolSetting::clearCache();

        $this->showSettingsModal = false;
        session()->flash('success', 'Pengaturan kuitansi berhasil disimpan.');
    }

    public function closeSettingsModal(): void
    {
        $this->showSettingsModal = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $kuitansis = Kuitansi::query()
            ->when($this->search, function ($query) {
                $query->where('penerima', 'like', '%' . $this->search . '%')
                    ->orWhere('nomor_bukti', 'like', '%' . $this->search . '%')
                    ->orWhere('uraian_pembayaran', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.kuitansi-bos-management', [
            'kuitansis' => $kuitansis,
            'settings' => SchoolSetting::getAll(),
        ])->layout('layouts.admin', ['header' => 'Kuitansi / Bukti Pembayaran BOS']);
    }
}
