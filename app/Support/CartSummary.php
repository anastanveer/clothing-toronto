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
    public static function build(?User $user, Collection $cartItems, ?Coupon $coupon = null): array
    {
        $lines = $cartItems->map(function (CartItem $item) {
            $product = $item->product;
            $baseUnitPrice = (float) ($product?->sale_price ?? $product?->price ?? 0);
            $unitPrice = Money::convertToDisplay($baseUnitPrice);
            $lineTotal = $unitPrice * $item->quantity;

            return [
                'model' => $item,
                'product' => $product,
                'unit_price' => $unitPrice,
                'unit_price_formatted' => Money::format($baseUnitPrice),
                'line_total' => $lineTotal,
                'line_total_formatted' => Money::format($lineTotal, false),
                'in_stock' => ($product?->stock ?? 0) > 0,
            ];
        });

        $subtotal = (float) $lines->sum('line_total');
        $shipping = $subtotal >= 250 ? 0 : ($subtotal > 0 ? 15 : 0);

        $lifetimeSpend = $user ? (float) $user->orders()->sum('total') : 0.0;
        $loyaltyDiscount = $user ? ($lifetimeSpend >= 500 ? min(50, $subtotal) : 0) : 0;

        $loyaltyBanner = null;
        if ($loyaltyDiscount > 0) {
            $loyaltyBanner = 'Radiant Insider perk applied — $50 off this order.';
        } elseif ($subtotal > 0 && $user) {
            $loyaltyBanner = 'Spend $' . number_format(max(0, 500 - $lifetimeSpend), 0) . ' more lifetime to unlock $50 off every order.';
        } elseif ($subtotal > 0 && ! $user) {
            $loyaltyBanner = 'Log in to unlock 50 Glamer reward points on this purchase.';
        }

        $couponDiscount = 0.0;
        $couponMessage = null;
        $appliedCoupon = null;

        if ($coupon) {
            if ($coupon->requires_assignment) {
                if (! $user) {
                    $couponMessage = 'Sign in to redeem invitation-only rewards.';
                    $coupon = null;
                } else {
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
        $taxable = max(0, $subtotal - $discount);
        $taxRate = (float) config('commerce.tax_rate', 0);
        $tax = round($taxable * $taxRate, 2);
        $total = max(0, $taxable + $tax + $shipping);

        return [
            'lines' => $lines,
            'summary' => [
                'subtotal' => $subtotal,
                'subtotal_formatted' => Money::format($subtotal, false),
                'shipping' => $shipping,
                'shipping_formatted' => $shipping > 0 ? Money::format($shipping, false) : 'Free',
                'loyalty_discount' => $loyaltyDiscount,
                'loyalty_discount_formatted' => $loyaltyDiscount > 0 ? '-' . Money::format($loyaltyDiscount, false) : null,
                'coupon_discount' => $couponDiscount,
                'coupon_discount_formatted' => $couponDiscount > 0 ? '-' . Money::format($couponDiscount, false) : null,
                'discount' => $discount,
                'discount_formatted' => $discount > 0 ? '-' . Money::format($discount, false) : Money::format(0, false),
                'tax' => $tax,
                'tax_formatted' => $tax > 0 ? Money::format($tax, false) : Money::format(0, false),
                'total' => $total,
                'total_formatted' => Money::format($total, false),
            ],
            'loyalty_banner' => $loyaltyBanner,
            'applied_coupon' => $appliedCoupon,
            'coupon_message' => $couponMessage,
        ];
    }
}
