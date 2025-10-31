<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected array $wishlistProductIds = [];
    protected array $cartProductIds = [];
    protected bool $userProductStatePrimed = false;

    public function __invoke(): View
    {
        $primaryBrand = $this->resolvePrimaryBrand();

        if (! Schema::hasTable('products')) {
            return $this->emptyHome();
        }

        $this->primeUserProductState();
        $featuredProducts = Product::where('status', 'published')
            ->orderByDesc('is_featured')
            ->orderByDesc('updated_at')
            ->take(12)
            ->get();

        $sliderProducts = $featuredProducts->concat(
            Product::where('status', 'published')->latest()->take(8)->get()
        )->unique('id')->values();

        $menSliderProducts = $this->categorySliderProducts('men', $sliderProducts);
        $womenSliderProducts = $this->categorySliderProducts('women', $sliderProducts);

        $compactProducts = Product::where('status', 'published')->latest()->take(12)->get();

        $recentPosts = Schema::hasTable('blog_posts')
            ? BlogPost::where('status', 'published')->latest('published_at')->take(4)->get()
            : collect();

        return view('pages.index', [
            'productSliderItems' => $sliderProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'menProductSliderItems' => $menSliderProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'womenProductSliderItems' => $womenSliderProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'flashSaleProducts' => $featuredProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'horizontalProducts' => $compactProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'recentPosts' => $recentPosts,
            'filterClasses' => $this->filterClasses(),
            'primaryBrand' => $primaryBrand,
        ]);
    }

    protected function emptyHome(): View
    {
        $empty = collect();

        return view('pages.index', [
            'productSliderItems' => $empty,
            'menProductSliderItems' => $empty,
            'womenProductSliderItems' => $empty,
            'flashSaleProducts' => $empty,
            'horizontalProducts' => $empty,
            'recentPosts' => collect(),
            'filterClasses' => $this->filterClasses(),
            'primaryBrand' => $this->resolvePrimaryBrand(),
        ]);
    }

    protected function filterClasses(): array
    {
        return [
            'best-selling',
            'on-selling',
            'top-rating',
            'top-rating',
            'on-selling',
            'best-selling',
            'on-selling',
            'top-rating',
            'on-selling',
            'best-selling',
            'best-selling',
            'on-selling',
        ];
    }

    protected function transformProduct(Product $product): array
    {
        $this->primeUserProductState();

        $price = $product->price;
        $sale = $product->sale_price;
        $displayPrice = $sale ?? $price;
        $discountLabel = null;

        if ($sale && $sale < $price) {
            $percent = round((1 - ($sale / $price)) * 100);
            $discountLabel = "Save {$percent}%";
        }

        $category = $product->category ?? 'men';
        $categoryLabel = ucfirst($category);
        $isWishlisted = in_array($product->id, $this->wishlistProductIds, true);
        $isInCart = in_array($product->id, $this->cartProductIds, true);

        return [
            'id' => $product->id,
            'title' => $product->name,
            'category' => $categoryLabel,
            'category_route' => 'shop.category',
            'category_url' => route('shop.category', ['category' => $category]),
            'price' => '$' . number_format($displayPrice, 2),
            'price_value' => (float) $displayPrice,
            'discount' => $discountLabel,
            'image' => $product->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-1.jpg'),
            'details_url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'rating' => 5,
            'in_wishlist' => $isWishlisted,
            'in_cart' => $isInCart,
            'share_title' => $product->name,
            'share_url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
        ];
    }

    protected function primeUserProductState(): void
    {
        if ($this->userProductStatePrimed) {
            return;
        }

        $this->userProductStatePrimed = true;

        if (! auth()->check()) {
            $this->wishlistProductIds = [];
            $this->cartProductIds = [];
            return;
        }

        $user = auth()->user();
        $this->wishlistProductIds = $user->wishlistItems()->pluck('product_id')->all();
        $this->cartProductIds = $user->cartItems()->pluck('product_id')->all();
    }

    protected function categorySliderProducts(string $category, Collection $fallback): Collection
    {
        $categoryProducts = Product::where('status', 'published')
            ->whereRaw('LOWER(category) = ?', [strtolower($category)])
            ->orderByDesc('is_featured')
            ->orderByDesc('updated_at')
            ->take(12)
            ->get()
            ->unique('id')
            ->values();

        return $categoryProducts->isNotEmpty() ? $categoryProducts : $fallback;
    }

    protected function resolvePrimaryBrand(): ?Brand
    {
        if (! Schema::hasTable('brands')) {
            return null;
        }

        $preferredSlug = config('catalog.primary_brand_slug');

        $query = Brand::query()->where('is_published', true);

        if ($preferredSlug) {
            $preferred = (clone $query)->where('slug', $preferredSlug)->first();

            if ($preferred) {
                return $preferred;
            }
        }

        return $query->orderBy('name')->first();
    }
}
