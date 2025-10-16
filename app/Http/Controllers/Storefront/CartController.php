<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
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

        $lines = $items->map(function (CartItem $item) {
            $product = $item->product;
            $unitPrice = $product ? (float) ($product->sale_price ?? $product->price ?? 0) : 0.0;
            $lineTotal = $unitPrice * $item->quantity;

            return [
                'model' => $item,
                'product' => $product,
                'unit_price' => $unitPrice,
                'unit_price_formatted' => '$' . number_format($unitPrice, 2),
                'line_total' => $lineTotal,
                'line_total_formatted' => '$' . number_format($lineTotal, 2),
                'in_stock' => ($product?->stock ?? 0) > 0,
            ];
        });

        $subtotal = $lines->sum('line_total');
        $shipping = $subtotal >= 250 ? 0 : ($subtotal > 0 ? 15 : 0);
        $lifetimeSpend = (float) $request->user()->orders()->sum('total');
        $loyaltyDiscount = $lifetimeSpend >= 500 ? min(50, $subtotal) : 0;
        $discount = $loyaltyDiscount;
        $total = max(0, $subtotal + $shipping - $discount);

        $loyaltyBanner = null;
        if ($discount > 0) {
            $loyaltyBanner = 'Radiant Insider perk applied — $50 off this order.';
        } elseif ($subtotal > 0 && $lifetimeSpend < 500) {
            $loyaltyBanner = 'Spend $' . number_format(max(0, 500 - $lifetimeSpend), 0) . ' more lifetime to unlock $50 off every order.';
        }

        return view('pages.cart', [
            'lines' => $lines,
            'summary' => [
                'subtotal' => $subtotal,
                'subtotal_formatted' => '$' . number_format($subtotal, 2),
                'shipping' => $shipping,
                'shipping_formatted' => $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free',
                'discount' => $discount,
                'discount_formatted' => $discount > 0 ? '-$' . number_format($discount, 2) : '$0.00',
                'total' => $total,
                'total_formatted' => '$' . number_format($total, 2),
            ],
            'loyaltyBanner' => $loyaltyBanner,
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
            return response()->json([
                'status' => 'removed',
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }

    protected function ensureOwnsCartItem(Request $request, CartItem $cartItem): void
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
