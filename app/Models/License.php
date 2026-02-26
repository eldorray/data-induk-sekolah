<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class License extends Model
{
    protected $fillable = [
        'license_key',
        'school_name',
        'domain',
        'status',
        'max_activations',
        'activated_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'max_activations' => 'integer',
    ];

    /**
     * Generate a random license key in XXXX-XXXX-XXXX-XXXX format.
     */
    public static function generateKey(): string
    {
        do {
            $key = strtoupper(
                Str::random(4) . '-' .
                Str::random(4) . '-' .
                Str::random(4) . '-' .
                Str::random(4)
            );
        } while (static::where('license_key', $key)->exists());

        return $key;
    }

    /**
     * Check if this license is currently active.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Scope for active licenses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get formatted expiry label.
     */
    public function getExpiresLabelAttribute(): string
    {
        if (!$this->expires_at) {
            return 'Selamanya';
        }

        return $this->expires_at->format('d M Y');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => $this->isActive() ? 'green' : 'yellow',
            'expired' => 'yellow',
            'revoked' => 'red',
            default => 'gray',
        };
    }
}
