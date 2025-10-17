<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\UserCoupon;
use App\Support\CartSummary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()
            ->cartItems()
            ->with('product.brand')
            ->latest('added_at')
            ->get();

        $couponCode = $request->session()->get('cart.coupon');
        $coupon = $couponCode ? Coupon::where('code', $couponCode)->first() : null;
        $summary = CartSummary::build($request->user(), $items, $coupon);

        $couponNotice = null;

        if ($couponCode && ! $coupon) {
            $couponNotice = 'The saved coupon is no longer available.';
            $request->session()->forget('cart.coupon');
        } elseif ($coupon && ! $summary['applied_coupon'] && $summary['coupon_message']) {
            $couponNotice = $summary['coupon_message'];
            $request->session()->forget('cart.coupon');
        }

        return view('pages.cart', [
            'lines' => $summary['lines'],
            'summary' => $summary['summary'],
            'loyaltyBanner' => $summary['loyalty_banner'],
            'appliedCoupon' => $summary['applied_coupon'],
            'couponNotice' => $couponNotice,
            'couponMessage' => $summary['coupon_message'],
            'couponCode' => $summary['applied_coupon']?->code ?? $couponCode,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->where('status', 'published')
            ->findOrFail($data['product_id']);

        $quantity = $data['quantity'] ?? 1;
        $user = $request->user();

        $cartItem = CartItem::query()
            ->firstOrNew([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'selected_size' => null,
                'selected_color' => null,
            ]);

        $cartItem->quantity = min(10, ($cartItem->exists ? $cartItem->quantity : 0) + $quantity);
        $cartItem->added_at = now();
        $cartItem->save();

        $message = $quantity > 1
            ? 'Updated your bag with ' . $quantity . ' more piece' . ($quantity > 1 ? 's' : '') . '.'
            : 'Added to your bag.';

        if ($cartItem->quantity >= 10) {
            $message = 'Bag limit reached — 10 units max per style.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'added',
                'message' => $message,
                'in_cart' => true,
                'quantity' => $cartItem->quantity,
            ]);
        }

        return back()->with('status', $message);
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse|JsonResponse
    {
        $this->ensureOwnsCartItem($request, $cartItem);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cartItem->update([
            'quantity' => $data['quantity'],
            'added_at' => now(),
        ]);

        $message = 'Updated your bag.';

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'updated',
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse|JsonResponse
    {
        $this->ensureOwnsCartItem($request, $cartItem);
        $cartItem->delete();

        $message = 'Removed from your bag.';

        if ($request->expectsJson()) {
            $items = $request->user()
                ->cartItems()
                ->with('product')
                ->latest('added_at')
                ->get();

            $couponCode = $request->session()->get('cart.coupon');
            $coupon = $couponCode ? Coupon::where('code', $couponCode)->first() : null;
            $summary = CartSummary::build($request->user(), $items, $coupon);
            $lines = $summary['lines'];

            if ($couponCode && ! $summary['applied_coupon']) {
                $request->session()->forget('cart.coupon');
            }

            return response()->json([
                'status' => 'removed',
                'message' => $message,
                'summary' => $summary['summary'],
                'coupon_message' => $summary['coupon_message'],
                'loyalty_banner' => $summary['loyalty_banner'],
                'coupon_applied' => (bool) $summary['applied_coupon'],
                'coupon_code' => optional($summary['applied_coupon'])->code,
                'coupon_title' => optional($summary['applied_coupon'])->title,
                'coupon_description' => optional($summary['applied_coupon'])->description,
                'lines_count' => $lines->count(),
            ]);
        }

        return back()->with('status', $message);
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $items = $request->user()
            ->cartItems()
            ->with('product')
            ->get();

        if ($items->isEmpty()) {
            return back()
                ->withInput($request->only('code'))
                ->with('coupon_status', 'Add items to your bag before applying a coupon.');
        }

        $user = $request->user();

        $code = strtoupper($data['code']);
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return back()
                ->withInput($request->only('code'))
                ->with('coupon_status', 'We couldn’t find that coupon. Double-check the code and try again.');
        }

        $assignment = null;

        if ($coupon->requires_assignment) {
            $assignment = $user->userCoupons()
                ->where('coupon_id', $coupon->id)
                ->first();

            if (! $assignment) {
                return back()
                    ->withInput($request->only('code'))
                    ->with('coupon_status', 'This perk is exclusive. Watch for it to unlock in your Glamer alerts.');
            }

            if (! $assignment->isAvailable()) {
                $unlockMessage = $assignment->available_at
                    ? 'This perk unlocks on ' . $assignment->available_at->format('M j, Y') . '. Check back soon!'
                    : 'This perk is not ready just yet. Check your alerts for the unlock date.';

                return back()
                    ->withInput($request->only('code'))
                    ->with('coupon_status', $unlockMessage);
            }
        }

        $summary = CartSummary::build($user, $items, $coupon);

        if (! $summary['applied_coupon']) {
            $message = $summary['coupon_message'] ?? 'This coupon does not apply to your cart.';

            return back()
                ->withInput($request->only('code'))
                ->with('coupon_status', $message);
        }

        $request->session()->put('cart.coupon', $coupon->code);

        if ($assignment && $coupon->requires_assignment && $assignment->status !== UserCoupon::STATUS_AVAILABLE) {
            $assignment->update(['status' => UserCoupon::STATUS_AVAILABLE]);
        }

        return back()->with('coupon_success', $coupon->title . ' is now applied to your bag.');
    }

    public function removeCoupon(Request $request): RedirectResponse
    {
        $request->session()->forget('cart.coupon');

        return back()->with('coupon_status', 'Coupon removed from your bag.');
    }

    protected function ensureOwnsCartItem(Request $request, CartItem $cartItem): void
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
