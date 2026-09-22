<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnduhanDataSiswa extends Model
{
    protected $fillable = [
        'sekolah',
        'tingkat_rombel',
        'nama_pengisi',
        'ip_address',
    ];
}
