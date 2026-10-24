<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LpjInsentifPenerima extends Model
{
    protected $table = 'lpj_insentif_penerimas';

    protected $fillable = [
        'lpj_insentif_id',
        'npwp',
        'nama',
        'nip_pegid_nuptk',
        'pangkat',
        'golongan',
        'status_pegawai',
        'jabatan',
        'tarif',
        'volume',
        'satuan',
        'pph21',
        'no_rekening',
        'sort_order',
    ];

    protected $casts = [
        'tarif' => 'integer',
        'volume' => 'integer',
        'pph21' => 'integer',
        'sort_order' => 'integer',
    ];

    public function lpj(): BelongsTo
    {
        return $this->belongsTo(LpjInsentif::class, 'lpj_insentif_id');
    }

    public function getJumlahUangAttribute(): int
    {
        return $this->tarif * $this->volume;
    }

    public function getJumlahDiterimaAttribute(): int
    {
        return max(0, $this->jumlah_uang - $this->pph21);
    }
}
