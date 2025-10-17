<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\WishlistItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()
            ->wishlistItems()
            ->with('product.brand')
            ->latest('added_at')
            ->get();

        return view('pages.wishlist', [
            'items' => $items,
        ]);
    }

    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $wishlistItem = WishlistItem::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $data['product_id'])
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'removed',
                    'message' => 'Removed from your wishlist.',
                    'in_wishlist' => false,
                ]);
            }

            return back()->with('status', 'Removed from your wishlist.');
        }

        WishlistItem::create([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'],
            'added_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'added',
                'message' => 'Saved to your wishlist.',
                'in_wishlist' => true,
            ]);
        }

        return back()->with('status', 'Saved to your wishlist.');
    }

    public function destroy(Request $request, WishlistItem $wishlistItem): RedirectResponse|JsonResponse
    {
        if ($wishlistItem->user_id !== $request->user()->id) {
            abort(403);
        }

        $wishlistItem->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from your wishlist.',
                'in_wishlist' => false,
                'remaining' => $request->user()->wishlistItems()->count(),
            ]);
        }

        return back()->with('status', 'Removed from your wishlist.');
    }
}
