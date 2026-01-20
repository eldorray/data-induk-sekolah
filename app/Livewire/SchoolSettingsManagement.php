<?php

namespace App\Livewire;

use App\Models\SchoolSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SchoolSettingsManagement extends Component
{
    use WithFileUploads;

    // Text settings
    public string $nama_sekolah = '';
    public string $nama_yayasan = '';
    public string $npsn = '';
    public string $nsm = '';
    public string $alamat = '';
    public string $kelurahan = '';
    public string $kecamatan = '';
    public string $kota = '';
    public string $provinsi = '';
    public string $kode_pos = '';
    public string $telepon = '';
    public string $email = '';
    public string $nama_kepala = '';
    public string $nip_kepala = '';
    public string $kode_surat = '';

    // File uploads
    public $kop_surat;
    public $stempel;
    public $ttd_kepala;

    // Current file paths
    public string $current_kop_surat = '';
    public string $current_stempel = '';
    public string $current_ttd_kepala = '';

    public function mount(): void
    {
        $settings = SchoolSetting::getAll();
        
        $this->nama_sekolah = $settings['nama_sekolah'] ?? '';
        $this->nama_yayasan = $settings['nama_yayasan'] ?? '';
        $this->npsn = $settings['npsn'] ?? '';
        $this->nsm = $settings['nsm'] ?? '';
        $this->alamat = $settings['alamat'] ?? '';
        $this->kelurahan = $settings['kelurahan'] ?? '';
        $this->kecamatan = $settings['kecamatan'] ?? '';
        $this->kota = $settings['kota'] ?? '';
        $this->provinsi = $settings['provinsi'] ?? '';
        $this->kode_pos = $settings['kode_pos'] ?? '';
        $this->telepon = $settings['telepon'] ?? '';
        $this->email = $settings['email'] ?? '';
        $this->nama_kepala = $settings['nama_kepala'] ?? '';
        $this->nip_kepala = $settings['nip_kepala'] ?? '';
        $this->kode_surat = $settings['kode_surat'] ?? 'MIDH';
        
        $this->current_kop_surat = $settings['kop_surat_path'] ?? '';
        $this->current_stempel = $settings['stempel_path'] ?? '';
        $this->current_ttd_kepala = $settings['ttd_kepala_path'] ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'nama_sekolah' => 'required|string|max:255',
            'kop_surat' => 'nullable|image|max:2048',
            'stempel' => 'nullable|image|max:1024',
            'ttd_kepala' => 'nullable|image|max:1024',
        ]);

        // Save text settings
        SchoolSetting::set('nama_sekolah', $this->nama_sekolah);
        SchoolSetting::set('nama_yayasan', $this->nama_yayasan);
        SchoolSetting::set('npsn', $this->npsn);
        SchoolSetting::set('nsm', $this->nsm);
        SchoolSetting::set('alamat', $this->alamat);
        SchoolSetting::set('kelurahan', $this->kelurahan);
        SchoolSetting::set('kecamatan', $this->kecamatan);
        SchoolSetting::set('kota', $this->kota);
        SchoolSetting::set('provinsi', $this->provinsi);
        SchoolSetting::set('kode_pos', $this->kode_pos);
        SchoolSetting::set('telepon', $this->telepon);
        SchoolSetting::set('email', $this->email);
        SchoolSetting::set('nama_kepala', $this->nama_kepala);
        SchoolSetting::set('nip_kepala', $this->nip_kepala);
        SchoolSetting::set('kode_surat', $this->kode_surat);

        // Handle file uploads
        if ($this->kop_surat) {
            // Delete old file
            if ($this->current_kop_surat) {
                Storage::disk('public')->delete($this->current_kop_surat);
            }
            $path = $this->kop_surat->store('school', 'public');
            SchoolSetting::set('kop_surat_path', $path);
            $this->current_kop_surat = $path;
        }

        if ($this->stempel) {
            if ($this->current_stempel) {
                Storage::disk('public')->delete($this->current_stempel);
            }
            $path = $this->stempel->store('school', 'public');
            SchoolSetting::set('stempel_path', $path);
            $this->current_stempel = $path;
        }

        if ($this->ttd_kepala) {
            if ($this->current_ttd_kepala) {
                Storage::disk('public')->delete($this->current_ttd_kepala);
            }
            $path = $this->ttd_kepala->store('school', 'public');
            SchoolSetting::set('ttd_kepala_path', $path);
            $this->current_ttd_kepala = $path;
        }

        // Clear cache
        SchoolSetting::clearCache();

        // Reset file inputs
        $this->kop_surat = null;
        $this->stempel = null;
        $this->ttd_kepala = null;

        session()->flash('success', 'Pengaturan sekolah berhasil disimpan.');
    }

    public function deleteKop(): void
    {
        if ($this->current_kop_surat) {
            Storage::disk('public')->delete($this->current_kop_surat);
            SchoolSetting::set('kop_surat_path', '');
            $this->current_kop_surat = '';
            SchoolSetting::clearCache();
            session()->flash('success', 'KOP surat berhasil dihapus.');
        }
    }

    public function deleteStempel(): void
    {
        if ($this->current_stempel) {
            Storage::disk('public')->delete($this->current_stempel);
            SchoolSetting::set('stempel_path', '');
            $this->current_stempel = '';
            SchoolSetting::clearCache();
            session()->flash('success', 'Stempel berhasil dihapus.');
        }
    }

    public function deleteTtd(): void
    {
        if ($this->current_ttd_kepala) {
            Storage::disk('public')->delete($this->current_ttd_kepala);
            SchoolSetting::set('ttd_kepala_path', '');
            $this->current_ttd_kepala = '';
            SchoolSetting::clearCache();
            session()->flash('success', 'Tanda tangan berhasil dihapus.');
        }
    }

    public function render()
    {
        return view('livewire.school-settings-management')
            ->layout('layouts.admin', ['header' => 'Pengaturan Sekolah']);
    }
}
