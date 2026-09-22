<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\UnduhanDataSiswa;
use App\Models\ValidasiDataSiswa;
use Illuminate\Support\Collection;
use Livewire\Component;

class ValidasiDataSiswaManagement extends Component
{
    public string $search = '';

    public string $filterSekolah = '';

    public string $filterStatus = '';

    public function tandaiSelesai(int $id): void
    {
        ValidasiDataSiswa::whereKey($id)->update([
            'status' => ValidasiDataSiswa::STATUS_BELUM,
            'catatan' => null,
        ]);

        session()->flash('success', 'Kelas ditandai selesai ditindaklanjuti.');
    }

    /**
     * Gabungan kelas yang punya siswa aktif + baris validasi yang sudah ada,
     * supaya kelas yang belum pernah dicek tetap kelihatan admin.
     */
    private function baris(): Collection
    {
        $tersimpan = ValidasiDataSiswa::all()->keyBy(
            fn (ValidasiDataSiswa $row) => $row->sekolah.'|'.$row->tingkat_rombel
        );

        $baris = collect();

        foreach (['MI', 'SMP'] as $sekolah) {
            foreach (ValidasiDataSiswa::kelasAktif($sekolah) as $kelas) {
                $row = $tersimpan->get($sekolah.'|'.$kelas);

                $baris->push([
                    'id' => $row?->id,
                    'sekolah' => $sekolah,
                    'tingkat_rombel' => $kelas,
                    'status' => $row?->status ?? ValidasiDataSiswa::STATUS_BELUM,
                    'nama_pengisi' => $row?->nama_pengisi,
                    'catatan' => $row?->catatan,
                    'checked_at' => $row?->checked_at,
                ]);

                $tersimpan->forget($sekolah.'|'.$kelas);
            }
        }

        // Baris validasi untuk kelas yang siswanya sudah tidak aktif/dihapus.
        foreach ($tersimpan as $row) {
            $baris->push([
                'id' => $row->id,
                'sekolah' => $row->sekolah,
                'tingkat_rombel' => $row->tingkat_rombel,
                'status' => $row->status,
                'nama_pengisi' => $row->nama_pengisi,
                'catatan' => $row->catatan,
                'checked_at' => $row->checked_at,
            ]);
        }

        return $baris
            ->when($this->filterSekolah !== '', fn (Collection $c) => $c->where('sekolah', $this->filterSekolah))
            ->when($this->filterStatus !== '', fn (Collection $c) => $c->where('status', $this->filterStatus))
            ->when($this->search !== '', fn (Collection $c) => $c->filter(
                fn (array $row) => str_contains(mb_strtolower($row['tingkat_rombel']), mb_strtolower($this->search))
            ))
            ->sortBy([['sekolah', 'asc'], ['tingkat_rombel', 'asc']])
            ->values();
    }

    public function render()
    {
        $baris = $this->baris();

        return view('livewire.validasi-data-siswa-management', [
            'baris' => $baris,
            'unduhan' => UnduhanDataSiswa::latest()->limit(50)->get(),
            'jumlahBelum' => $baris->where('status', ValidasiDataSiswa::STATUS_BELUM)->count(),
            'jumlahLengkap' => $baris->where('status', ValidasiDataSiswa::STATUS_LENGKAP)->count(),
            'jumlahCatatan' => $baris->where('status', ValidasiDataSiswa::STATUS_ADA_CATATAN)->count(),
        ])->layout('layouts.admin', ['header' => 'Validasi Data Siswa']);
    }
}
