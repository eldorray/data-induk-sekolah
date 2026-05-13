<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerAlumni extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'bersedia_dihubungi',
        'sumber_info',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'bersedia_dihubungi' => 'boolean',
        'kepuasan_pendidikan' => 'integer',
    ];
}