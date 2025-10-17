<?php

namespace App\Support;

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponAllocator
{
    public function assignWelcomeBundle(User $user): void
    {
        $config = config('coupons.welcome_bundle');

        if (! ($config['enabled'] ?? false)) {
            return;
        }

        if ($user->userCoupons()->exists()) {
            return;
        }

        $definitions = collect($config['coupons'] ?? []);

        if ($definitions->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($definitions, $user) {
            foreach ($definitions as $definition) {
                $code = $definition['code'] ?? null;

                if (! $code) {
                    continue;
                }

                /** @var \App\Models\Coupon|null $coupon */
                $coupon = Coupon::where('code', $code)->first();

                if (! $coupon) {
                    Log::warning('[CouponAllocator] Missing coupon definition', [
                        'code' => $code,
                    ]);
                    continue;
                }

                $limit = $definition['max_assignments'] ?? null;

                if ($limit !== null && $coupon->userCoupons()->count() >= $limit) {
                    continue;
                }

                $status = $definition['status'] ?? UserCoupon::STATUS_AVAILABLE;
                $availableInDays = (int) ($definition['available_in_days'] ?? 0);
                $availableAt = $availableInDays > 0 ? now()->addDays($availableInDays) : null;

                $user->userCoupons()->firstOrCreate(
                    ['coupon_id' => $coupon->id],
                    [
                        'status' => $status,
                        'assigned_at' => now(),
                        'available_at' => $availableAt,
                    ]
                );
            }
        });
    }
}
