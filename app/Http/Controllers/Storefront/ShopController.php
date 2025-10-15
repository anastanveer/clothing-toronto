<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ShopController extends Controller
{
    protected const CATEGORY_LABELS = [
        'men' => 'Men',
        'women' => 'Women',
        'kids' => 'Kids',
    ];

    public function index(Request $request): View
    {
        $products = $this->paginateProducts($request, null, 12);

        return view('pages.shop', [
            'products' => $products,
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => null,
        ]);
    }

    public function category(Request $request, string $category): View
    {
        $category = $this->normalizeCategory($category);

        $products = $this->paginateProducts($request, $category, 12);

        return view('pages.shop', [
            'products' => $products,
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => $category,
        ]);
    }

    public function noSidebar(Request $request): View
    {
        $products = $this->paginateProducts($request, null, 16);

        return view('pages.shop-no-sidebar', [
            'products' => $products,
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => null,
        ]);
    }

    public function rightSidebar(Request $request): View
    {
        $products = $this->paginateProducts($request, null, 12);

        return view('pages.shop-right-sidebar', [
            'products' => $products,
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => null,
        ]);
    }

    public function show(?string $slug = null): View
    {
        if (! Schema::hasTable('products')) {
            abort(404);
        }

        $product = Product::where('status', 'published')
            ->when($slug, fn ($query) => $query->where('slug', $slug))
            ->firstOrFail();

        $relatedProducts = Product::where('status', 'published')
            ->whereKeyNot($product->getKey())
            ->latest('updated_at')
            ->take(6)
            ->get()
            ->map(fn (Product $related) => $this->transformProduct($related));

        return view('pages.shop-details', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
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

        return [
            'id' => $product->id,
            'title' => $product->name,
            'category' => ucfirst($product->category ?? 'collection'),
            'category_route' => 'shop.category',
            'category_url' => route('shop.category', ['category' => $product->category ?? 'men']),
            'price' => '$' . number_format($displayPrice, 2),
            'discount' => $discountLabel,
            'image' => $product->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-1.jpg'),
            'details_url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'rating' => 5,
        ];
    }

    protected function paginateProducts(Request $request, ?string $category, int $perPage): LengthAwarePaginator
    {
        if (! Schema::hasTable('products')) {
            return $this->emptyPaginator($request, $perPage);
        }

        $query = Product::where('status', 'published');

        if ($category) {
            $query->where('category', $category);
        }

        $paginator = $query
            ->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        $paginator->getCollection()->transform(fn (Product $product) => $this->transformProduct($product));

        return $paginator;
    }

    protected function normalizeCategory(string $category): string
    {
        $category = strtolower($category);

        if (! array_key_exists($category, self::CATEGORY_LABELS)) {
            abort(404);
        }

        return $category;
    }

    protected function emptyPaginator(Request $request, int $perPage): LengthAwarePaginator
    {
        $page = max(1, $request->integer('page', 1));
        $perPage = max(1, $perPage);

        return new LengthAwarePaginator(
            items: collect(),
            total: 0,
            perPage: $perPage,
            currentPage: $page,
            options: [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }
}
