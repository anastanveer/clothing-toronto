<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Support\Loyalty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeaderMetricsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'wishlistCount' => 0,
                'cartCount' => 0,
                'loyaltyPoints' => 0,
                'pendingPoints' => 0,
            ]);
        }

        $wishlistCount = (int) $user->wishlistItems()->count();
        $cartItems = $user->cartItems()->with('product')->get();

        $cartCount = (int) $cartItems->sum('quantity');
        $cartValue = $cartItems->sum(function (CartItem $item) {
            $product = $item->product;
            $unitPrice = $product ? (float) ($product->sale_price ?? $product->price ?? 0) : 0.0;

            return $unitPrice * $item->quantity;
        });

        $loyaltySummary = Loyalty::summarize(
            (float) $user->orders()->sum('total'),
            (float) $cartValue
        );

        return response()->json([
            'wishlistCount' => $wishlistCount,
            'cartCount' => $cartCount,
            'loyaltyPoints' => $loyaltySummary['loyaltyPoints'] ?? 0,
            'pendingPoints' => $loyaltySummary['cartPoints'] ?? 0,
        ]);
    }
}
