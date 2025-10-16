<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'selected_size',
        'selected_color',
        'added_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'added_at' => 'datetime',
    ];

    public function getUnitPriceAttribute(): float
    {
        $product = $this->product;

        return (float) ($product?->sale_price ?? $product?->price ?? 0);
    }

    public function getLineTotalAttribute(): float
    {
        return (float) ($this->unit_price * $this->quantity);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
