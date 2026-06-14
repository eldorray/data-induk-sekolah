<?php

namespace App\Livewire;

use App\Helpers\Terbilang;
use App\Models\Kuitansi;
use App\Models\SchoolSetting;
use App\Services\LpjBosImageCompressor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class KuitansiBosManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Search & pagination
    public string $search = '';

    public string $filterTahun = '';

    public int $perPage = 10;

    // Bulk selection (untuk "Cetak Terpilih")
    public array $selected = [];

    // Modal states
    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $showSettingsModal = false;

    public bool $isEditing = false;

    // Upload kuitansi yang sudah ditandatangani
    public bool $showSignedModal = false;

    public ?int $signedKuitansiId = null;

    public ?UploadedFile $signedFile = null;

    // Form data (per kuitansi)
    public ?int $kuitansiId = null;

    public string $nomor_bukti = '';

    public string $tahun_anggaran = '';

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
            'tahun_anggaran' => 'required|string|max:10',
            'penerima' => 'required|string|max:255',
            'jumlah_uang' => 'required|integer|min:1',
            'uraian_pembayaran' => 'required|string',
            'tanggal_lunas' => 'required|date',
        ];
    }

    protected $messages = [
        'nomor_bukti.required' => 'Nomor urut bukti wajib diisi.',
        'tahun_anggaran.required' => 'Tahun anggaran wajib diisi.',
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

    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
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
        if (! $this->tanggal_lunas || ! $this->tahun_anggaran) {
            return null;
        }

        $tahunLunas = substr($this->tanggal_lunas, 0, 4);

        if ($tahunLunas !== $this->tahun_anggaran) {
            return "Tahun tanggal lunas ({$tahunLunas}) berbeda dengan tahun anggaran ({$this->tahun_anggaran}). Pastikan ini disengaja.";
        }

        return null;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->tahun_anggaran = SchoolSetting::get('kuitansi_tahun_anggaran', (string) date('Y'));
        $this->tanggal_lunas = date('Y-m-d');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $k = Kuitansi::findOrFail($id);
        $this->kuitansiId = $k->id;
        $this->nomor_bukti = $k->nomor_bukti;
        $this->tahun_anggaran = $k->tahun_anggaran ?? '';
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

    /**
     * Salin (duplikat) kuitansi terpilih menjadi record baru.
     */
    public function copySelected(): void
    {
        $items = Kuitansi::whereIn('id', $this->selected)->get();

        if ($items->isEmpty()) {
            return;
        }

        foreach ($items as $item) {
            $item->replicate()->save();
        }

        $count = $items->count();
        $this->selected = [];
        session()->flash('success', "{$count} kuitansi berhasil disalin. Jangan lupa ubah nomor bukti pada salinan.");
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
        $this->tahun_anggaran = '';
        $this->penerima = '';
        $this->jumlah_uang = null;
        $this->uraian_pembayaran = '';
        $this->tanggal_lunas = null;
        $this->resetErrorBag();
    }

    // ===== Upload kuitansi yang sudah ditandatangani =====

    public function openSignedUploadModal(int $id): void
    {
        $this->signedKuitansiId = $id;
        $this->signedFile = null;
        $this->resetErrorBag();
        $this->showSignedModal = true;
    }

    public function closeSignedUploadModal(): void
    {
        $this->showSignedModal = false;
        $this->signedKuitansiId = null;
        $this->signedFile = null;
        $this->resetErrorBag();
    }

    public function uploadSignedFile(LpjBosImageCompressor $compressor): void
    {
        if (! $this->signedKuitansiId) {
            return;
        }

        $this->validate([
            'signedFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ], [
            'signedFile.required' => 'File kuitansi yang sudah ditandatangani wajib dipilih.',
            'signedFile.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF.',
            'signedFile.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        $kuitansi = Kuitansi::findOrFail($this->signedKuitansiId);
        $file = $this->signedFile;
        $isPdf = $file->getMimeType() === 'application/pdf';

        if ($isPdf && $file->getSize() > 5 * 1024 * 1024) {
            $this->addError('signedFile', 'File PDF maksimal 5 MB.');

            return;
        }

        // Hapus file lama bila ada (replace).
        if ($kuitansi->signed_file_path) {
            Storage::disk('public')->delete($kuitansi->signed_file_path);
        }

        $directory = 'kuitansi-bos/signed/'.$kuitansi->id;
        $path = $isPdf
            ? $file->storeAs($directory, Str::uuid()->toString().'.pdf', 'public')
            : $compressor->store($file, $directory);

        $kuitansi->update([
            'signed_file_path' => $path,
            'signed_original_name' => $file->getClientOriginalName(),
            'signed_mime_type' => $isPdf ? 'application/pdf' : 'image/jpeg',
            'signed_file_size' => Storage::disk('public')->size($path),
            'signed_uploaded_at' => now(),
        ]);

        $this->closeSignedUploadModal();
        session()->flash('success', 'Kuitansi yang sudah ditandatangani berhasil diupload.');
    }

    public function deleteSignedFile(int $id): void
    {
        $kuitansi = Kuitansi::findOrFail($id);

        if ($kuitansi->signed_file_path) {
            Storage::disk('public')->delete($kuitansi->signed_file_path);
            $kuitansi->update([
                'signed_file_path' => null,
                'signed_original_name' => null,
                'signed_mime_type' => null,
                'signed_file_size' => null,
                'signed_uploaded_at' => null,
            ]);
            session()->flash('success', 'File kuitansi yang sudah ditandatangani berhasil dihapus.');
        }
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
            ->with('lpjBos')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('penerima', 'like', '%'.$this->search.'%')
                        ->orWhere('nomor_bukti', 'like', '%'.$this->search.'%')
                        ->orWhere('uraian_pembayaran', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun_anggaran', $this->filterTahun);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $tahunOptions = Kuitansi::query()
            ->select('tahun_anggaran')
            ->whereNotNull('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran', 'desc')
            ->pluck('tahun_anggaran')
            ->all();

        return view('livewire.kuitansi-bos-management', [
            'kuitansis' => $kuitansis,
            'tahunOptions' => $tahunOptions,
            'settings' => SchoolSetting::getAll(),
        ])->layout('layouts.admin', ['header' => 'Kuitansi / Bukti Pembayaran BOS']);
    }
}
