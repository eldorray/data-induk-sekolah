<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LpjBosAttachment extends Model
{
    public const CATEGORY_FOTO = 'foto';

    public const CATEGORY_KWITANSI = 'kwitansi';

    public const CATEGORY_UNDANGAN = 'undangan';

    public const CATEGORIES = [
        self::CATEGORY_FOTO,
        self::CATEGORY_KWITANSI,
        self::CATEGORY_UNDANGAN,
    ];

    protected $fillable = [
        'lpj_bos_id',
        'kategori',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'keterangan',
        'sort_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    public function lpjBos(): BelongsTo
    {
        return $this->belongsTo(LpjBos::class, 'lpj_bos_id');
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
