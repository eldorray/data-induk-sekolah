<?php

namespace App\Livewire;

use App\Models\TracerAlumni;
use Livewire\Component;

class TracerAlumniForm extends Component
{
    public string $nama_lengkap = '';
    public string $nisn = '';
    public string $jenjang = '';
    public string $tahun_lulus = '';
    public string $jenis_kelamin = '';
    public string $tempat_lahir = '';
    public ?string $tanggal_lahir = null;
    public string $alamat = '';
    public string $no_telepon = '';
    public string $email = '';

    public string $status_sekarang = '';
    public string $nama_institusi = '';
    public string $jurusan_bidang = '';
    public string $tahun_masuk = '';

    public ?int $kepuasan_pendidikan = null;
    public string $kesan_pesan = '';
    public bool $bersedia_dihubungi = true;
    public string $sumber_info = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'jenjang' => 'required|in:MI,SMP',
            'tahun_lulus' => 'required|string|max:4',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status_sekarang' => 'required|string|in:Bekerja,Kuliah,Wirausaha,Belum Bekerja,Lainnya',
            'nama_institusi' => 'nullable|string|max:255',
            'jurusan_bidang' => 'nullable|string|max:255',
            'tahun_masuk' => 'nullable|string|max:4',
            'kepuasan_pendidikan' => 'nullable|integer|min:1|max:5',
            'kesan_pesan' => 'nullable|string|max:2000',
            'bersedia_dihubungi' => 'boolean',
            'sumber_info' => 'nullable|string|max:100',
        ];
    }

    protected $messages = [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'jenjang.required' => 'Jenjang wajib dipilih.',
        'jenjang.in' => 'Jenjang harus MI atau SMP.',
        'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
        'status_sekarang.required' => 'Status saat ini wajib dipilih.',
        'email.email' => 'Format email tidak valid.',
        'kepuasan_pendidikan.min' => 'Nilai kepuasan minimal 1.',
        'kepuasan_pendidikan.max' => 'Nilai kepuasan maksimal 5.',
    ];

    public function submit(): void
    {
        $validated = $this->validate();

        TracerAlumni::create($validated);

        $this->submitted = true;
        $this->reset([
            'nama_lengkap',
            'nisn',
            'jenjang',
            'tahun_lulus',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'no_telepon',
            'email',
            'status_sekarang',
            'nama_institusi',
            'jurusan_bidang',
            'tahun_masuk',
            'kepuasan_pendidikan',
            'kesan_pesan',
            'sumber_info',
        ]);
        $this->bersedia_dihubungi = true;
    }

    public function resetForm(): void
    {
        $this->submitted = false;
    }

    public function render()
    {
        return view('livewire.tracer-alumni-form')
            ->layout('layouts.app', ['title' => 'Form Tracer Alumni']);
    }
}