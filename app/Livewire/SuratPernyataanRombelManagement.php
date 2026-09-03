<?php

namespace App\Livewire;

use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use Livewire\Component;

class SuratPernyataanRombelManagement extends Component
{
    public string $nomor_surat = '';

    public string $tanggal_surat = '';

    public string $tahun_pelajaran = '';

    public string $nama_madrasah = '';

    public string $nama_kepala = '';

    public string $kota = '';

    public string $nama_pengawas = '';

    public string $nip_pengawas = '';

    protected function rules(): array
    {
        return [
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tahun_pelajaran' => 'required|string|max:20',
            'nama_madrasah' => 'required|string|max:150',
            'nama_kepala' => 'required|string|max:150',
            'kota' => 'required|string|max:100',
            'nama_pengawas' => 'nullable|string|max:150',
            'nip_pengawas' => 'nullable|string|max:30',
        ];
    }

    public function mount(): void
    {
        $bulan = (int) date('n');
        $tahun = (int) date('Y');
        $defaultTp = $bulan >= 7 ? $tahun.'/'.($tahun + 1) : ($tahun - 1).'/'.$tahun;

        $this->nomor_surat = SchoolSetting::get('rombel_nomor_surat', '') ?? '';
        $this->tanggal_surat = SchoolSetting::get('rombel_tanggal_surat', date('Y-m-d')) ?: date('Y-m-d');
        $this->tahun_pelajaran = SchoolSetting::get('rombel_tahun_pelajaran', $defaultTp) ?: $defaultTp;
        $this->nama_madrasah = SchoolSetting::get('rombel_nama_madrasah', null)
            ?: (SchoolSetting::get('kuitansi_nama_madrasah', '') ?? '');
        $this->nama_kepala = SchoolSetting::get('rombel_nama_kepala', null)
            ?: (SchoolSetting::get('kuitansi_kepala_madrasah', '') ?? '');
        $this->kota = SchoolSetting::get('rombel_kota', null)
            ?: (SchoolSetting::get('kuitansi_kabupaten', '') ?? '');
        $this->nama_pengawas = SchoolSetting::get('rombel_nama_pengawas', '') ?? '';
        $this->nip_pengawas = SchoolSetting::get('rombel_nip_pengawas', '') ?? '';
    }

    public function save(): void
    {
        $data = $this->validate();

        foreach ($data as $key => $value) {
            SchoolSetting::set('rombel_'.$key, (string) $value);
        }
        SchoolSetting::clearCache();

        session()->flash('success', 'Data surat pernyataan rombel disimpan.');
    }

    public function render()
    {
        return view('livewire.surat-pernyataan-rombel-management', [
            'rekap' => SiswaMi::rekapRombel(),
        ])->layout('layouts.admin', ['header' => 'Surat Pernyataan Rombel']);
    }
}
