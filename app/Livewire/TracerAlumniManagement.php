<?php

namespace App\Livewire;

use App\Models\TracerAlumni;
use Livewire\Component;
use Livewire\WithPagination;

class TracerAlumniManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterJenjang = '';
    public string $filterStatus = '';
    public string $filterTahunLulus = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public bool $showDetailModal = false;
    public bool $showDeleteModal = false;
    public ?int $selectedId = null;
    public ?TracerAlumni $selectedData = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterJenjang(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahunLulus(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openDetail(int $id): void
    {
        $this->selectedId = $id;
        $this->selectedData = TracerAlumni::find($id);
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->selectedId = null;
        $this->selectedData = null;
    }

    public function openDeleteModal(int $id): void
    {
        $this->selectedId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->selectedId) {
            TracerAlumni::where('id', $this->selectedId)->delete();
            session()->flash('success', 'Data tracer alumni berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->selectedId = null;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->selectedId = null;
    }

    public function exportCsv()
    {
        $filename = 'tracer-alumni-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $data = TracerAlumni::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJenjang, fn($q) => $q->where('jenjang', $this->filterJenjang))
            ->when($this->filterStatus, fn($q) => $q->where('status_sekarang', $this->filterStatus))
            ->when($this->filterTahunLulus, fn($q) => $q->where('tahun_lulus', $this->filterTahunLulus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $callback = function () use ($data) {
            $out = fopen('php://output', 'w');
            // BOM for Excel UTF-8 compatibility
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'Nama Lengkap', 'NISN', 'Jenjang', 'Tahun Lulus', 'Jenis Kelamin',
                'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'No. Telepon', 'Email',
                'Status Sekarang', 'Nama Institusi', 'Jurusan/Bidang', 'Tahun Masuk',
                'Kepuasan Pendidikan', 'Kesan Pesan', 'Bersedia Dihubungi', 'Sumber Info',
                'Tanggal Isi',
            ]);
            foreach ($data as $row) {
                fputcsv($out, [
                    $row->nama_lengkap,
                    $row->nisn,
                    $row->jenjang,
                    $row->tahun_lulus,
                    $row->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    $row->tempat_lahir,
                    $row->tanggal_lahir?->format('Y-m-d'),
                    $row->alamat,
                    $row->no_telepon,
                    $row->email,
                    $row->status_sekarang,
                    $row->nama_institusi,
                    $row->jurusan_bidang,
                    $row->tahun_masuk,
                    $row->kepuasan_pendidikan,
                    $row->kesan_pesan,
                    $row->bersedia_dihubungi ? 'Ya' : 'Tidak',
                    $row->sumber_info,
                    $row->created_at?->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $query = TracerAlumni::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_institusi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJenjang, fn($q) => $q->where('jenjang', $this->filterJenjang))
            ->when($this->filterStatus, fn($q) => $q->where('status_sekarang', $this->filterStatus))
            ->when($this->filterTahunLulus, fn($q) => $q->where('tahun_lulus', $this->filterTahunLulus));

        $data = (clone $query)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Statistics for analytics
        $total = TracerAlumni::count();
        $totalMi = TracerAlumni::where('jenjang', 'MI')->count();
        $totalSmp = TracerAlumni::where('jenjang', 'SMP')->count();

        $statusDistribution = TracerAlumni::query()
            ->selectRaw('status_sekarang, COUNT(*) as total')
            ->groupBy('status_sekarang')
            ->pluck('total', 'status_sekarang')
            ->toArray();

        $avgKepuasan = TracerAlumni::whereNotNull('kepuasan_pendidikan')
            ->avg('kepuasan_pendidikan');

        $tahunLulusOptions = TracerAlumni::query()
            ->select('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus')
            ->toArray();

        return view('livewire.tracer-alumni-management', [
            'data' => $data,
            'total' => $total,
            'totalMi' => $totalMi,
            'totalSmp' => $totalSmp,
            'statusDistribution' => $statusDistribution,
            'avgKepuasan' => $avgKepuasan,
            'tahunLulusOptions' => $tahunLulusOptions,
        ])->layout('layouts.admin', ['header' => 'Tracer Alumni']);
    }
}