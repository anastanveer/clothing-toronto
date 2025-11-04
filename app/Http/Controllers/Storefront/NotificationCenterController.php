<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Support\Money;

class NotificationCenterController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        $coupons = collect();

        if ($user) {
            $userCoupons = $user->userCoupons()
                ->with('coupon')
                ->orderByRaw("CASE status WHEN 'available' THEN 0 WHEN 'pending' THEN 1 WHEN 'redeemed' THEN 2 WHEN 'expired' THEN 3 ELSE 4 END")
                ->orderByDesc('assigned_at')
                ->limit(10)
                ->get();

            $coupons = $userCoupons->map(function ($assignment) {
                $coupon = $assignment->coupon;
                $value = (float) $coupon->value;
                $discountLabel = $coupon->type === 'percent'
                    ? number_format($value, 0) . '% off'
                    : '$' . number_format($value, 2) . ' off';

                return [
                    'code' => $coupon->code,
                    'title' => $coupon->title,
                    'description' => $coupon->description,
                    'status' => $assignment->status,
                    'availableAt' => optional($assignment->available_at)->toIso8601String(),
                    'couponType' => $coupon->type,
                    'value' => $value,
                    'minSpend' => (float) $coupon->min_spend,
                    'exclusive' => (bool) $coupon->requires_assignment,
                    'discountLabel' => $discountLabel,
                ];
            })->all();
        }

        $sharedCoupons = Coupon::active()
            ->orderByDesc('priority')
            ->orderBy('code')
            ->limit(6)
            ->get()
            ->map(function (Coupon $coupon) {
                $value = (float) $coupon->value;
                $discountLabel = $coupon->type === 'percent'
                    ? number_format($value, 0) . '% off'
                    : '$' . number_format($value, 2) . ' off';

                $status = $coupon->requires_assignment ? 'pending' : 'available';
                $availableAt = $coupon->requires_assignment ? optional($coupon->starts_at)->toIso8601String() : null;

                return [
                    'code' => $coupon->code,
                    'title' => $coupon->title,
                    'description' => $coupon->description,
                    'status' => $status,
                    'availableAt' => $availableAt,
                    'couponType' => $coupon->type,
                    'value' => $value,
                    'minSpend' => (float) $coupon->min_spend,
                    'exclusive' => (bool) $coupon->requires_assignment,
                    'discountLabel' => $discountLabel,
                ];
            })
            ->all();

        if (! $coupons instanceof Collection) {
            $coupons = collect($coupons);
        }

        if ($user) {
            $coupons = $coupons->keyBy('code');
            foreach ($sharedCoupons as $shared) {
                $coupons->put($shared['code'], $shared);
            }
            $coupons = $coupons->values();
        } else {
            $coupons = collect($sharedCoupons);
        }

        $coupons = $coupons
            ->unique('code')
            ->values();

        $products = Product::query()
            ->where('status', 'published')
            ->whereIn('category', Product::CATEGORIES)
            ->with('brand')
            ->orderByRaw('(CASE WHEN sale_price IS NOT NULL AND sale_price < price THEN price - sale_price ELSE 0 END) DESC')
            ->orderByRaw('COALESCE(sale_price, price) ASC')
            ->limit(6)
            ->get()
            ->map(function (Product $product) {
                $currentPrice = (float) ($product->sale_price ?? $product->price ?? 0);
                $originalPrice = (float) ($product->price ?? $currentPrice);
                $discountAmount = max(0, $originalPrice - $currentPrice);
                $discountPercent = $originalPrice > 0 ? round(($discountAmount / $originalPrice) * 100) : 0;

                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $currentPrice,
                    'formattedPrice' => Money::format($currentPrice),
                    'brand' => optional($product->brand)->name,
                    'image' => $product->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-sm-6.jpg'),
                    'url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
                    'discountLabel' => $discountAmount > 0
                        ? 'Save $' . number_format($discountAmount, 2) . ' · ' . $discountPercent . '% off'
                        : null,
                ];
            })
            ->all();

        $articles = BlogPost::query()
            ->latest('published_at')
            ->limit(3)
            ->get()
            ->map(function (BlogPost $post) {
                return [
                    'title' => $post->title,
                    'excerpt' => (string) str($post->excerpt ?? $post->body)->limit(120),
                    'url' => route('blog.details', ['slug' => $post->slug ?? $post->id]),
                    'publishedAt' => optional($post->published_at ?? $post->created_at)->toIso8601String(),
                ];
            })
            ->all();

        return response()->json([
            'coupons' => $coupons->all(),
            'products' => $products,
            'articles' => $articles,
        ]);
    }
}
