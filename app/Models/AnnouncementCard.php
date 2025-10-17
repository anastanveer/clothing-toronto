<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'category',
        'body',
        'cta_label',
        'cta_url',
        'is_featured',
        'is_published',
        'starts_at',
        'ends_at',
        'meta',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'meta' => 'array',
    ];

    public function scopeActive($query)
    {
        $now = now();

        return $query
            ->where('is_published', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }
}
