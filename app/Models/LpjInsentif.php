<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LpjInsentif extends Model
{
    protected $table = 'lpj_insentifs';

    protected $fillable = [
        'periode',
        'tahun',
        'kota',
        'bulan_ttd',
        'nama_kepala',
        'jabatan_kepala',
        'nama_bendahara',
        'jabatan_bendahara',
        'catatan',
    ];

    public function penerimas(): HasMany
    {
        return $this->hasMany(LpjInsentifPenerima::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getTotalJumlahUangAttribute(): int
    {
        return $this->penerimas()->get()->sum(fn ($p) => $p->jumlah_uang);
    }

    public function getTotalPph21Attribute(): int
    {
        return $this->penerimas()->get()->sum(fn ($p) => $p->pph21);
    }

    public function getTotalDiterimaAttribute(): int
    {
        return $this->penerimas()->get()->sum(fn ($p) => $p->jumlah_diterima);
    }

    public function getLabelAttribute(): string
    {
        return 'LPJ Insentif '.$this->periode.' '.$this->tahun;
    }
}
