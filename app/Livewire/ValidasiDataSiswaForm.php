<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Exports\SiswaKelasPublikExport;
use App\Models\SchoolSetting;
use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use App\Models\UnduhanDataSiswa;
use App\Models\ValidasiDataSiswa;
use Illuminate\Support\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ValidasiDataSiswaForm extends Component
{
    private const MAX_PERCOBAAN = 5;

    private const MENIT_KUNCI = 5;

    public string $pinInput = '';

    public bool $pinOk = false;

    public bool $terkunci = false;

    public string $sekolah = 'MI';

    public string $tingkatRombel = '';

    public string $namaPengisi = '';

    public string $catatan = '';

    public string $suksesPesan = '';

    /** Fitur baru terbuka kalau admin sudah menyetel PIN (fail closed). */
    public bool $pinTersedia = false;

    public function mount(): void
    {
        $this->pinTersedia = filled(SchoolSetting::get('validasi_data_siswa_pin'));
        $this->pinOk = $this->pinTersedia && (bool) session('validasi_pin_ok', false);
        $this->terkunci = $this->sedangTerkunci();
    }

    public function bukaAkses(): void
    {
        $this->suksesPesan = '';

        if ($this->sedangTerkunci()) {
            $this->terkunci = true;
            $this->addError('pinInput', 'Terlalu banyak percobaan. Coba lagi beberapa menit lagi.');

            return;
        }

        $pin = SchoolSetting::get('validasi_data_siswa_pin');

        if (blank($pin)) {
            $this->addError('pinInput', 'Fitur ini belum diaktifkan admin.');

            return;
        }

        if (! hash_equals((string) $pin, $this->pinInput)) {
            $this->catatPercobaanGagal();
            $this->addError('pinInput', 'PIN salah. Hubungi admin untuk mendapatkan PIN.');

            return;
        }

        session()->forget(['validasi_pin_attempts', 'validasi_pin_locked_until']);
        session(['validasi_pin_ok' => true]);

        $this->pinOk = true;
        $this->terkunci = false;
        $this->pinInput = '';
    }

    public function updatedSekolah(): void
    {
        $this->tingkatRombel = '';
        $this->suksesPesan = '';
    }

    public function updatedTingkatRombel(): void
    {
        $this->suksesPesan = '';
        $this->reset('catatan');
        $this->resetErrorBag();
    }

    public function tandaiLengkap(): void
    {
        $this->simpan(ValidasiDataSiswa::STATUS_LENGKAP, null);
    }

    public function kirimCatatan(): void
    {
        $this->validate(
            ['catatan' => 'required|string|max:2000'],
            ['catatan.required' => 'Tuliskan nama siswa yang belum ada di sistem.']
        );

        $this->simpan(ValidasiDataSiswa::STATUS_ADA_CATATAN, $this->catatan);
    }

    public function unduhExcel(): ?BinaryFileResponse
    {
        abort_unless($this->pinOk, 403);

        $this->validate([
            'sekolah' => 'required|in:MI,SMP',
            'tingkatRombel' => 'required|string|max:255',
            'namaPengisi' => 'required|string|max:255',
        ], [
            'namaPengisi.required' => 'Isi nama wali kelas sebelum mengunduh.',
        ]);

        // Status dicek ulang di server: menyembunyikan tombol saja tidak cukup.
        if (! $this->kelasSudahLengkap()) {
            $this->addError('tingkatRombel', 'Tandai kelas ini lengkap dulu sebelum mengunduh.');

            return null;
        }

        UnduhanDataSiswa::create([
            'sekolah' => $this->sekolah,
            'tingkat_rombel' => $this->tingkatRombel,
            'nama_pengisi' => $this->namaPengisi,
            'ip_address' => request()->ip(),
        ]);

        return Excel::download(
            new SiswaKelasPublikExport($this->sekolah, $this->tingkatRombel),
            'Data Siswa - '.$this->tingkatRombel.'.xlsx'
        );
    }

    private function kelasSudahLengkap(): bool
    {
        return ValidasiDataSiswa::where('sekolah', $this->sekolah)
            ->where('tingkat_rombel', $this->tingkatRombel)
            ->where('status', ValidasiDataSiswa::STATUS_LENGKAP)
            ->exists();
    }

    private function simpan(string $status, ?string $catatan): void
    {
        abort_unless($this->pinOk, 403);

        $this->validate([
            'sekolah' => 'required|in:MI,SMP',
            'tingkatRombel' => 'required|string|max:255',
            'namaPengisi' => 'required|string|max:255',
        ], [
            'tingkatRombel.required' => 'Pilih kelas terlebih dahulu.',
            'namaPengisi.required' => 'Nama wali kelas wajib diisi.',
        ]);

        ValidasiDataSiswa::updateOrCreate(
            ['sekolah' => $this->sekolah, 'tingkat_rombel' => $this->tingkatRombel],
            [
                'status' => $status,
                'nama_pengisi' => $this->namaPengisi,
                'catatan' => $catatan,
                'checked_at' => now(),
            ]
        );

        $this->catatan = '';
        $this->suksesPesan = $status === ValidasiDataSiswa::STATUS_LENGKAP
            ? 'Terima kasih, data kelas ini sudah ditandai lengkap.'
            : 'Catatan terkirim ke admin. Terima kasih.';
    }

    private function sedangTerkunci(): bool
    {
        $sampai = session('validasi_pin_locked_until');

        return $sampai !== null && now()->timestamp < (int) $sampai;
    }

    private function catatPercobaanGagal(): void
    {
        $percobaan = (int) session('validasi_pin_attempts', 0) + 1;
        session(['validasi_pin_attempts' => $percobaan]);

        if ($percobaan >= self::MAX_PERCOBAAN) {
            session(['validasi_pin_locked_until' => now()->addMinutes(self::MENIT_KUNCI)->timestamp]);
            $this->terkunci = true;
        }
    }

    /**
     * Hanya nama & tanggal lahir yang boleh keluar ke halaman publik.
     */
    private function daftarSiswa(): Collection
    {
        if ($this->tingkatRombel === '') {
            return collect();
        }

        $model = $this->sekolah === 'SMP' ? SiswaSmp::class : SiswaMi::class;

        return $model::query()
            ->where('status', 'Aktif')
            ->where('tingkat_rombel', $this->tingkatRombel)
            ->orderBy('nama_lengkap')
            ->get(['nama_lengkap', 'tanggal_lahir']);
    }

    public function render()
    {
        $kelasOptions = [];
        $siswa = collect();

        if ($this->pinOk && $this->pinTersedia) {
            $statusPerKelas = ValidasiDataSiswa::where('sekolah', $this->sekolah)
                ->pluck('status', 'tingkat_rombel');

            foreach (ValidasiDataSiswa::kelasAktif($this->sekolah) as $kelas) {
                $status = $statusPerKelas[$kelas] ?? ValidasiDataSiswa::STATUS_BELUM;
                $kelasOptions[] = [
                    'nama' => $kelas,
                    'status' => $status,
                    'label' => ValidasiDataSiswa::STATUS_LABEL[$status],
                ];
            }

            $siswa = $this->daftarSiswa();
        }

        return view('livewire.validasi-data-siswa-form', [
            'kelasOptions' => $kelasOptions,
            'siswa' => $siswa,
            'kelasLengkap' => $this->pinOk && $this->tingkatRombel !== '' && $this->kelasSudahLengkap(),
        ]);
    }
}
