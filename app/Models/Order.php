<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'status',
        'payment_status',
        'fulfillment_status',
        'subtotal',
        'discount_total',
        'shipping_total',
        'tax_total',
        'total',
        'shipping_address',
        'billing_address',
        'placed_at',
        'fulfilled_at',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeRecent($query): void
    {
        $query->latest('placed_at');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'delivered' => 'Delivered',
            'shipped' => 'On the way',
            'processing' => 'Processing',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status ?? 'Processing'),
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'delivered' => 'status-pill delivered',
            'shipped' => 'status-pill shipped',
            'processing' => 'status-pill processing',
            'cancelled' => 'status-pill cancelled',
            default => 'status-pill processing',
        };
    }
}
