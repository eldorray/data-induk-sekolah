<?php

namespace App\Livewire;

use App\Models\SuratUniversal;
use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithFileUploads;

class SuratUniversalForm extends Component
{
    use WithFileUploads;

    public ?int $suratId = null;
    public bool $isEditing = false;

    public string $jenis = '';
    public string $judul = '';
    public string $nomor_surat = '';
    public ?string $tanggal_surat = null;
    public string $jenjang = 'MI';
    public string $isi = '';
    public string $tempat = '';
    public string $ttd_jabatan = '';
    public string $ttd_nama = '';
    public string $ttd_nip = '';

    public $kopFile = null;
    public ?string $existingKopPath = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $surat = SuratUniversal::findOrFail($id);
            $this->suratId = $surat->id;
            $this->isEditing = true;
            $this->jenis = $surat->jenis;
            $this->judul = $surat->judul;
            $this->nomor_surat = $surat->nomor_surat;
            $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
            $this->jenjang = $surat->jenjang ?? 'MI';
            $this->isi = $surat->isi ?? '';
            $this->tempat = $surat->tempat ?? '';
            $this->ttd_jabatan = $surat->ttd_jabatan ?? '';
            $this->ttd_nama = $surat->ttd_nama ?? '';
            $this->ttd_nip = $surat->ttd_nip ?? '';
            $this->existingKopPath = $surat->kop_path;
            return;
        }

        $this->nomor_surat = SuratUniversal::generateNomorSurat();
        $this->tanggal_surat = date('Y-m-d');
        $this->tempat = SchoolSetting::get('kuitansi_kabupaten', '') ?? '';
        $this->ttd_jabatan = 'Kepala Madrasah';
        $this->ttd_nama = SchoolSetting::get('kuitansi_kepala_madrasah', '') ?? '';
    }

    protected function rules(): array
    {
        return [
            'jenis' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'jenjang' => 'required|string|max:50',
            'isi' => 'required|string',
            'tempat' => 'nullable|string|max:255',
            'ttd_jabatan' => 'nullable|string|max:255',
            'ttd_nama' => 'nullable|string|max:255',
            'ttd_nip' => 'nullable|string|max:100',
            'kopFile' => 'nullable|image|max:4096',
        ];
    }

    protected $messages = [
        'jenis.required' => 'Jenis surat wajib diisi.',
        'judul.required' => 'Judul surat wajib diisi.',
        'nomor_surat.required' => 'Nomor surat wajib diisi.',
        'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
        'isi.required' => 'Isi surat wajib diisi.',
        'kopFile.image' => 'Kop surat harus berupa gambar.',
        'kopFile.max' => 'Ukuran kop maksimal 4MB.',
    ];

    public function save()
    {
        $validated = $this->validate();
        $data = collect($validated)->except('kopFile')->toArray();

        if ($this->kopFile) {
            $data['kop_path'] = $this->kopFile->store('kop-surat', 'public');
        }

        if ($this->isEditing) {
            SuratUniversal::findOrFail($this->suratId)->update($data);
            session()->flash('success', 'Surat berhasil diperbarui.');
        } else {
            SuratUniversal::create($data);
            session()->flash('success', 'Surat berhasil ditambahkan.');
        }

        return $this->redirectRoute('surat-universal.index', navigate: true);
    }

    public function render()
    {
        $kop = SchoolSetting::get('kop_surat_path');

        return view('livewire.surat-universal-form', [
            'jenjangOptions' => SuratUniversal::JENJANG_OPTIONS,
            'defaultKopUrl' => $kop ? asset('storage/' . $kop) : null,
        ])->layout('layouts.admin', ['header' => $this->isEditing ? 'Edit Surat' : 'Buat Surat']);
    }
}
