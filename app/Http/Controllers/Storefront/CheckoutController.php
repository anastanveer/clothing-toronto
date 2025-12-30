<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Concerns\InteractsWithCart;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Support\CartSummary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    use InteractsWithCart;

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        $items = $this->scopedCartItems($request, ['product.brand'])
            ->latest('added_at')
            ->get();

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('status', 'Your bag is empty. Add something beautiful before checking out.');
        }

        $couponCode = $request->session()->get('cart.coupon');
        $coupon = $couponCode ? Coupon::where('code', $couponCode)->first() : null;

        $summary = CartSummary::build($user, $items, $coupon);

        $couponNotice = null;

        if ($couponCode && ! $coupon) {
            $couponNotice = 'The saved coupon is no longer available.';
            $request->session()->forget('cart.coupon');
        } elseif ($coupon && ! $summary['applied_coupon'] && $summary['coupon_message']) {
            $couponNotice = $summary['coupon_message'];
            $request->session()->forget('cart.coupon');
        }

        return view('pages.checkout', [
            'lines' => $summary['lines'],
            'summary' => $summary['summary'],
            'appliedCoupon' => $summary['applied_coupon'],
            'couponMessage' => $summary['coupon_message'],
            'couponNotice' => $couponNotice,
            'couponCode' => $summary['applied_coupon']?->code ?? $couponCode,
            'loyaltyBanner' => $summary['loyalty_banner'],
            'customer' => $user,
        ]);
    }
}
