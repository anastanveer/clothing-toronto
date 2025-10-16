<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Support\Loyalty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $orders = $user->orders()
            ->with(['items.product.brand'])
            ->latest('placed_at')
            ->take(5)
            ->get();

        $wishlistItems = $user->wishlistItems()
            ->with('product.brand')
            ->latest('added_at')
            ->take(6)
            ->get();

        $cartItems = $user->cartItems()
            ->with('product.brand')
            ->latest('added_at')
            ->get();

        $likes = $user->likes()
            ->with('product.brand')
            ->latest('liked_at')
            ->take(6)
            ->get();

        $cartValue = $cartItems->sum(fn (CartItem $item) => $item->line_total);

        $metrics = [
            'ordersCount' => $orders->count(),
            'lifetimeOrders' => $user->orders()->count(),
            'wishlistCount' => $wishlistItems->count(),
            'likesCount' => $likes->count(),
            'cartCount' => $cartItems->count(),
            'totalSpent' => (float) $user->orders()->sum('total'),
            'cartValue' => (float) $cartValue,
            'deliveredCount' => $user->orders()->where('status', 'delivered')->count(),
            'processingCount' => $user->orders()->whereIn('status', ['processing', 'shipped'])->count(),
        ];

        $loyaltySummary = Loyalty::summarize($metrics['totalSpent'], $metrics['cartValue']);
        $loyaltyStages = collect($loyaltySummary['stages']);

        $flashReward = null;
        if ($metrics['cartValue'] > 0) {
            if ($metrics['cartValue'] >= 250) {
                $flashReward = 'Checkout this cart to unlock a surprise accessory tucked into your delivery.';
            } else {
                $flashReward = 'Add $' . number_format(max(0, 250 - $metrics['cartValue']), 0) . ' more to your bag and we will include a limited edition gift.';
            }
        }

        $nextDelivery = $user->orders()
            ->whereIn('status', ['shipped', 'processing'])
            ->orderByDesc('placed_at')
            ->first();

        return view('user.dashboard', [
            'user' => $user,
            'orders' => $orders,
            'wishlistItems' => $wishlistItems,
            'cartItems' => $cartItems,
            'likes' => $likes,
            'metrics' => $metrics,
            'nextDelivery' => $nextDelivery,
            'loyaltyStages' => $loyaltyStages,
            'loyaltySummary' => $loyaltySummary,
            'flashReward' => $flashReward,
        ]);
    }
}
