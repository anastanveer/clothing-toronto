<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'type',
        'value',
        'max_discount',
        'min_spend',
        'is_active',
        'starts_at',
        'expires_at',
        'requires_assignment',
        'max_assignments',
        'priority',
        'audience_tag',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'requires_assignment' => 'boolean',
        'max_assignments' => 'integer',
        'priority' => 'integer',
    ];

    public function userCoupons(): HasMany
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function availableAssignmentsCount(): int
    {
        if ($this->max_assignments === null) {
            return PHP_INT_MAX;
        }

        $assigned = $this->userCoupons()->count();

        return max(0, (int) ($this->max_assignments - $assigned));
    }

    public function isAssignable(): bool
    {
        if (! $this->requires_assignment) {
            return true;
        }

        return $this->availableAssignmentsCount() > 0;
    }

    public function isAssignedTo(User $user, ?string $status = null): bool
    {
        $query = $this->userCoupons()->where('user_id', $user->id);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->exists();
    }

    public function allowForUser(User $user): bool
    {
        if (! $this->requires_assignment) {
            return true;
        }

        return $this->userCoupons()
            ->where('user_id', $user->id)
            ->whereIn('status', [UserCoupon::STATUS_AVAILABLE])
            ->exists();
    }

    public function scopeActive($query)
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }

    public function isCurrentlyActive(?CarbonInterface $moment = null): bool
    {
        $moment = $moment ?? now();

        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $moment->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $moment->gt($this->expires_at)) {
            return false;
        }

        return true;
    }

    public function meetsMinimum(float $subtotal): bool
    {
        return $subtotal >= (float) $this->min_spend;
    }

    public function discountFor(float $subtotal): float
    {
        if (! $this->meetsMinimum($subtotal)) {
            return 0.0;
        }

        $discount = 0.0;

        if ($this->type === 'percent') {
            $discount = $subtotal * ((float) $this->value / 100);
            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = (float) $this->value;
        }

        return min($discount, $subtotal);
    }
}
