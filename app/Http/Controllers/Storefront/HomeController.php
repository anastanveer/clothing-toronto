<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        if (! Schema::hasTable('products')) {
            return $this->emptyHome();
        }

        $featuredProducts = Product::where('status', 'published')
            ->orderByDesc('is_featured')
            ->orderByDesc('updated_at')
            ->take(12)
            ->get();

        $sliderProducts = $featuredProducts->concat(
            Product::where('status', 'published')->latest()->take(8)->get()
        )->unique('id')->values();

        $compactProducts = Product::where('status', 'published')->latest()->take(12)->get();

        $recentPosts = Schema::hasTable('blog_posts')
            ? BlogPost::where('status', 'published')->latest('published_at')->take(4)->get()
            : collect();

        return view('pages.index', [
            'productSliderItems' => $sliderProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'flashSaleProducts' => $featuredProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'horizontalProducts' => $compactProducts->map(fn (Product $product) => $this->transformProduct($product)),
            'recentPosts' => $recentPosts,
            'filterClasses' => $this->filterClasses(),
        ]);
    }

    protected function emptyHome(): View
    {
        $empty = collect();

        return view('pages.index', [
            'productSliderItems' => $empty,
            'flashSaleProducts' => $empty,
            'horizontalProducts' => $empty,
            'recentPosts' => collect(),
            'filterClasses' => $this->filterClasses(),
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

        return [
            'id' => $product->id,
            'title' => $product->name,
            'category' => $categoryLabel,
            'category_route' => 'shop.category',
            'category_url' => route('shop.category', ['category' => $category]),
            'price' => '$' . number_format($displayPrice, 2),
            'discount' => $discountLabel,
            'image' => $product->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-1.jpg'),
            'details_url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'rating' => 5,
        ];
    }
}
