<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LpjBos extends Model
{
    protected $table = 'lpj_bos';

    protected $fillable = [
        'kuitansi_id',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'lokasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    public function kuitansi(): BelongsTo
    {
        return $this->belongsTo(Kuitansi::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LpjBosAttachment::class, 'lpj_bos_id')->orderBy('kategori')->orderBy('sort_order');
    }

    public function fotoAttachments(): HasMany
    {
        return $this->hasMany(LpjBosAttachment::class, 'lpj_bos_id')
            ->where('kategori', LpjBosAttachment::CATEGORY_FOTO)
            ->orderBy('sort_order');
    }

    public function kwitansiAttachments(): HasMany
    {
        return $this->hasMany(LpjBosAttachment::class, 'lpj_bos_id')
            ->where('kategori', LpjBosAttachment::CATEGORY_KWITANSI)
            ->orderBy('sort_order');
    }

    public function undanganAttachments(): HasMany
    {
        return $this->hasMany(LpjBosAttachment::class, 'lpj_bos_id')
            ->where('kategori', LpjBosAttachment::CATEGORY_UNDANGAN)
            ->orderBy('sort_order');
    }

    public function getIsCompleteAttribute(): bool
    {
        return $this->attachments()->where('kategori', LpjBosAttachment::CATEGORY_FOTO)->exists()
            && $this->attachments()->where('kategori', LpjBosAttachment::CATEGORY_KWITANSI)->exists();
    }

    public function getCompletenessLabelAttribute(): string
    {
        return $this->is_complete ? 'Lengkap' : 'Belum lengkap';
    }

    public function attachmentCount(string $category): int
    {
        return $this->attachments()->where('kategori', $category)->count();
    }
}
