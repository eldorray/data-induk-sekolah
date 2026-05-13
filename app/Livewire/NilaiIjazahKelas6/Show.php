<?php

namespace App\Livewire\NilaiIjazahKelas6;

use App\Models\MapelMi;
use App\Models\NilaiIjazahScore;
use App\Models\NilaiIjazahTahunAjaran;
use App\Models\SiswaMi;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin', ['header' => 'Detail Nilai Ijazah Kelas 6'])]
class Show extends Component
{
    public NilaiIjazahTahunAjaran $tahunAjaran;

    #[Url(as: 'tab', keep: true)]
    public string $tab = 'raport';

    /**
     * Semester yang sedang diinput di tab raport.
     * Pilihan: kelas_4_semester_1, kelas_4_semester_2, kelas_5_semester_1,
     *          kelas_5_semester_2, kelas_6_semester_1
     */
    #[Url(as: 'smt', keep: true)]
    public string $semesterTab = 'kelas_4_semester_1';

    // Search siswa (dipakai untuk kedua tab input)
    public string $searchSiswa = '';

    /**
     * Struktur data grid:
     * $grid[siswa_id][mapel_id] = [
     *   'kelas_4_semester_1' => ..., 'kelas_4_semester_2' => ...,
     *   'kelas_5_semester_1' => ..., 'kelas_5_semester_2' => ...,
     *   'kelas_6_semester_1' => ...,
     *   'nilai_um' => ...,
     * ]
     */
    public array $grid = [];

    /**
     * Daftar pilihan semester untuk tab raport.
     */
    public array $semesterOptions = [
        'kelas_4_semester_1' => 'Kelas 4 Semester 1',
        'kelas_4_semester_2' => 'Kelas 4 Semester 2',
        'kelas_5_semester_1' => 'Kelas 5 Semester 1',
        'kelas_5_semester_2' => 'Kelas 5 Semester 2',
        'kelas_6_semester_1' => 'Kelas 6 Semester 1',
    ];

    public function mount(NilaiIjazahTahunAjaran $tahunAjaran): void
    {
        $this->tahunAjaran = $tahunAjaran;
        $this->loadGrid();
    }

    public function updatedTab(): void
    {
        $this->loadGrid();
    }

    public function updatedSemesterTab(): void
    {
        // Semester berubah, tidak perlu reload grid karena data semester lain
        // tetap ada di $grid.
    }

    public function updatedSearchSiswa(): void
    {
        // Filter di sisi render, tidak perlu reload grid
    }

    /**
     * Pilih semester untuk tab raport.
     */
    public function selectSemester(string $semester): void
    {
        if (array_key_exists($semester, $this->semesterOptions)) {
            $this->semesterTab = $semester;
        }
    }

    /**
     * Muat seluruh score existing ke struktur grid $this->grid.
     */
    public function loadGrid(): void
    {
        $this->grid = [];

        $scores = NilaiIjazahScore::query()
            ->where('nilai_ijazah_tahun_ajaran_id', $this->tahunAjaran->id)
            ->get();

        foreach ($scores as $score) {
            $this->grid[$score->siswa_id][$score->mapel_id] = [
                'kelas_4_semester_1' => $score->kelas_4_semester_1,
                'kelas_4_semester_2' => $score->kelas_4_semester_2,
                'kelas_5_semester_1' => $score->kelas_5_semester_1,
                'kelas_5_semester_2' => $score->kelas_5_semester_2,
                'kelas_6_semester_1' => $score->kelas_6_semester_1,
                'nilai_um' => $score->nilai_um,
            ];
        }
    }

    /**
     * Simpan nilai raport untuk semester yang sedang aktif saja.
     */
    public function saveRaport(): void
    {
        if (! array_key_exists($this->semesterTab, $this->semesterOptions)) {
            session()->flash('error', 'Semester tidak valid.');

            return;
        }

        $this->persistGrid([$this->semesterTab]);

        session()->flash(
            'success',
            'Nilai '.$this->semesterOptions[$this->semesterTab].' berhasil disimpan.'
        );
    }

    /**
     * Simpan seluruh nilai UM di grid.
     */
    public function saveUm(): void
    {
        $this->persistGrid(['nilai_um']);

        session()->flash('success', 'Nilai UM berhasil disimpan.');
    }

    /**
     * Persist grid values ke database dalam transaction dengan upsert.
     *
     * @param  array<int, string>  $fields  Kolom yang akan disimpan
     */
    private function persistGrid(array $fields): void
    {
        // Validasi terlebih dahulu: setiap nilai numeric 0..100
        $errors = [];
        foreach ($this->grid as $siswaId => $mapels) {
            if (! is_array($mapels)) {
                continue;
            }
            foreach ($mapels as $mapelId => $values) {
                if (! is_array($values)) {
                    continue;
                }
                foreach ($fields as $field) {
                    $value = $values[$field] ?? null;
                    if ($value === null || $value === '') {
                        continue;
                    }
                    if (! is_numeric($value)) {
                        $errors[] = "Nilai harus berupa angka (siswa #{$siswaId}, mapel #{$mapelId}).";

                        continue;
                    }
                    $num = (float) $value;
                    if ($num < 0 || $num > 100) {
                        $errors[] = "Nilai harus 0-100 (siswa #{$siswaId}, mapel #{$mapelId}).";
                    }
                }
            }
        }

        if (! empty($errors)) {
            session()->flash('error', implode(' ', array_slice($errors, 0, 3)));

            return;
        }

        DB::transaction(function () use ($fields) {
            foreach ($this->grid as $siswaId => $mapels) {
                if (! is_array($mapels)) {
                    continue;
                }
                foreach ($mapels as $mapelId => $values) {
                    if (! is_array($values)) {
                        continue;
                    }

                    $hasAny = false;
                    foreach ($fields as $field) {
                        if (isset($values[$field]) && $values[$field] !== '' && $values[$field] !== null) {
                            $hasAny = true;
                            break;
                        }
                    }

                    $existing = NilaiIjazahScore::where('nilai_ijazah_tahun_ajaran_id', $this->tahunAjaran->id)
                        ->where('siswa_id', $siswaId)
                        ->where('siswa_type', 'siswa_mi')
                        ->where('mapel_id', $mapelId)
                        ->where('mapel_type', 'mapel_mi')
                        ->first();

                    // Tidak ada existing & tidak ada input → skip
                    if (! $existing && ! $hasAny) {
                        continue;
                    }

                    $payload = [];
                    foreach ($fields as $field) {
                        $val = $values[$field] ?? null;
                        $payload[$field] = ($val === '' || $val === null) ? null : (float) $val;
                    }

                    if ($existing) {
                        $existing->update($payload);
                    } else {
                        NilaiIjazahScore::create(array_merge([
                            'nilai_ijazah_tahun_ajaran_id' => $this->tahunAjaran->id,
                            'siswa_id' => $siswaId,
                            'siswa_type' => 'siswa_mi',
                            'mapel_id' => $mapelId,
                            'mapel_type' => 'mapel_mi',
                        ], $payload));
                    }
                }
            }
        });

        $this->loadGrid();
    }

    /**
     * Ambil siswa kelas 6 MI (kolom tingkat_rombel bebas format, deteksi angka 6 / "VI").
     * TODO: kalau project menambahkan kolom khusus kelas, ganti filter ini.
     */
    private function siswaKelas6Query()
    {
        return SiswaMi::query()
            ->where('status', 'Aktif')
            ->where(function ($query) {
                $query->where('tingkat_rombel', 'like', '%6%')
                    ->orWhere('tingkat_rombel', 'like', '%VI%');
            })
            ->when($this->searchSiswa, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->searchSiswa.'%')
                        ->orWhere('nisn', 'like', '%'.$this->searchSiswa.'%');
                });
            })
            ->orderBy('nama_lengkap');
    }

    public function render()
    {
        $siswas = $this->siswaKelas6Query()->get();

        $mapels = MapelMi::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('nama_mapel')
            ->get();

        return view('livewire.nilai-ijazah-kelas-6.show', [
            'siswas' => $siswas,
            'mapels' => $mapels,
        ]);
    }
}