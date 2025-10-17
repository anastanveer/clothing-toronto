<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'status',
        'assigned_at',
        'available_at',
        'redeemed_at',
        'meta',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'available_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'meta' => 'array',
    ];

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_PENDING = 'pending';
    public const STATUS_REDEEMED = 'redeemed';
    public const STATUS_EXPIRED = 'expired';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function isAvailable(): bool
    {
        if ($this->status === self::STATUS_PENDING && $this->available_at && $this->available_at->isPast()) {
            $this->status = self::STATUS_AVAILABLE;
            $this->save();
        }

        if ($this->status !== self::STATUS_AVAILABLE) {
            return false;
        }

        if ($this->available_at && $this->available_at->isFuture()) {
            return false;
        }

        if ($this->redeemed_at) {
            return false;
        }

        return true;
    }
}
