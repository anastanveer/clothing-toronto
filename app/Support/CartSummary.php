<?php

namespace App\Support;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Collection;

class CartSummary
{
    /**
     * Build a cart summary with line items, totals, and loyalty/coupon metadata.
     *
     * @param  Collection<int, CartItem>  $cartItems
     */
    public static function build(User $user, Collection $cartItems, ?Coupon $coupon = null): array
    {
        $lines = $cartItems->map(function (CartItem $item) {
            $product = $item->product;
            $unitPrice = (float) ($product?->sale_price ?? $product?->price ?? 0);
            $lineTotal = $unitPrice * $item->quantity;

            return [
                'model' => $item,
                'product' => $product,
                'unit_price' => $unitPrice,
                'unit_price_formatted' => self::formatCurrency($unitPrice),
                'line_total' => $lineTotal,
                'line_total_formatted' => self::formatCurrency($lineTotal),
                'in_stock' => ($product?->stock ?? 0) > 0,
            ];
        });

        $subtotal = (float) $lines->sum('line_total');
        $shipping = $subtotal >= 250 ? 0 : ($subtotal > 0 ? 15 : 0);

        $lifetimeSpend = (float) $user->orders()->sum('total');
        $loyaltyDiscount = $lifetimeSpend >= 500 ? min(50, $subtotal) : 0;

        $loyaltyBanner = null;
        if ($loyaltyDiscount > 0) {
            $loyaltyBanner = 'Radiant Insider perk applied — $50 off this order.';
        } elseif ($subtotal > 0 && $lifetimeSpend < 500) {
            $loyaltyBanner = 'Spend $' . number_format(max(0, 500 - $lifetimeSpend), 0) . ' more lifetime to unlock $50 off every order.';
        }

        $couponDiscount = 0.0;
        $couponMessage = null;
        $appliedCoupon = null;

        if ($coupon) {
            if ($coupon->requires_assignment) {
                $assignment = $user->userCoupons()
                    ->where('coupon_id', $coupon->id)
                    ->first();

                if (! $assignment) {
                    $couponMessage = 'This perk is invitation-only.';
                    $coupon = null;
                } elseif (! $assignment->isAvailable()) {
                    $couponMessage = $assignment->available_at
                        ? 'This perk unlocks on ' . $assignment->available_at->format('M j, Y') . '.'
                        : 'This perk hasn’t unlocked yet.';
                    $coupon = null;
                }
            }

            if ($coupon) {
                if (! $coupon->isCurrentlyActive()) {
                    $couponMessage = 'This coupon is no longer active.';
                } elseif (! $coupon->meetsMinimum($subtotal)) {
                    $couponMessage = 'Add $' . number_format(max(0, $coupon->min_spend - $subtotal), 2) . ' more to use this coupon.';
                } else {
                    $couponDiscount = $coupon->discountFor($subtotal);
                    if ($couponDiscount <= 0) {
                        $couponMessage = 'Coupon does not apply to the current cart.';
                    } else {
                        $appliedCoupon = $coupon;
                    }
                }
            }
        }

        $discount = $loyaltyDiscount + $couponDiscount;
        $total = max(0, $subtotal + $shipping - $discount);

        return [
            'lines' => $lines,
            'summary' => [
                'subtotal' => $subtotal,
                'subtotal_formatted' => self::formatCurrency($subtotal),
                'shipping' => $shipping,
                'shipping_formatted' => $shipping > 0 ? self::formatCurrency($shipping) : 'Free',
                'loyalty_discount' => $loyaltyDiscount,
                'loyalty_discount_formatted' => $loyaltyDiscount > 0 ? '-' . self::formatCurrency($loyaltyDiscount) : null,
                'coupon_discount' => $couponDiscount,
                'coupon_discount_formatted' => $couponDiscount > 0 ? '-' . self::formatCurrency($couponDiscount) : null,
                'discount' => $discount,
                'discount_formatted' => $discount > 0 ? '-' . self::formatCurrency($discount) : '$0.00',
                'total' => $total,
                'total_formatted' => self::formatCurrency($total),
            ],
            'loyalty_banner' => $loyaltyBanner,
            'applied_coupon' => $appliedCoupon,
            'coupon_message' => $couponMessage,
        ];
    }

    protected static function formatCurrency(float $amount): string
    {
        return '$' . number_format($amount, 2);
    }
}
