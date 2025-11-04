<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Support\Money;

class ShopController extends Controller
{
    protected array $wishlistProductIds = [];
    protected array $cartProductIds = [];
    protected bool $userProductStatePrimed = false;
    protected ?Brand $primaryBrandCache = null;
    protected bool $primaryBrandResolved = false;
    protected ?bool $limitPrimaryCache = null;

    protected const CATEGORY_LABELS = [
        'men' => 'Men',
        'women' => 'Women',
        'kids' => 'Kids',
    ];

    protected const SORT_OPTIONS = [
        'newest' => 'Newest arrivals',
        'price_low_high' => 'Price: Low to High',
        'price_high_low' => 'Price: High to Low',
        'rating_high_low' => 'Top Rated',
        'most_reviewed' => 'Most Reviewed',
    ];

    protected const RATING_BUCKETS = [
        5 => '5 only',
        4 => '4 & up',
        3 => '3 & up',
        2 => '2 & up',
        1 => '1 & up',
    ];

    public function index(Request $request): View
    {
        $this->primeUserProductState();
        $primaryBrand = $this->primaryBrand();
        $limitToPrimary = $this->shouldLimitToPrimaryBrand();
        $data = $this->resolveShopData(
            $request,
            null,
            12,
            $limitToPrimary ? $primaryBrand : null
        );

        return view('pages.shop', array_merge($data, [
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => null,
            'activeBrand' => $limitToPrimary ? $primaryBrand : null,
        ]));
    }

    public function category(Request $request, string $category): View
    {
        $category = $this->normalizeCategory($category);
        $this->primeUserProductState();
        $data = $this->resolveShopData($request, $category, 12);

        return view('pages.shop', array_merge($data, [
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => $category,
            'activeBrand' => $this->shouldLimitToPrimaryBrand() ? $this->primaryBrand() : null,
        ]));
    }

    public function brand(Request $request, string $slug): View
    {
        $brand = Brand::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if ($this->shouldLimitToPrimaryBrand()) {
            $primary = $this->primaryBrand();

            if ($primary && $brand->id !== $primary->id) {
                abort(404);
            }
        }

        $categoryParam = $request->query('category');
        $category = null;

        if ($categoryParam) {
            $category = $this->normalizeCategory($categoryParam);
        }

        $this->primeUserProductState();
        $data = $this->resolveShopData($request, $category, 12, $brand);

        return view('pages.shop', array_merge($data, [
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => $category,
            'activeBrand' => $brand,
        ]));
    }

    public function noSidebar(Request $request): View
    {
        $this->primeUserProductState();
        $primaryBrand = $this->primaryBrand();
        $limitToPrimary = $this->shouldLimitToPrimaryBrand();
        $data = $this->resolveShopData(
            $request,
            null,
            16,
            $limitToPrimary ? $primaryBrand : null
        );

        return view('pages.shop-no-sidebar', array_merge($data, [
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => null,
            'activeBrand' => $limitToPrimary ? $primaryBrand : null,
        ]));
    }

    public function rightSidebar(Request $request): View
    {
        $this->primeUserProductState();
        $primaryBrand = $this->primaryBrand();
        $limitToPrimary = $this->shouldLimitToPrimaryBrand();
        $data = $this->resolveShopData(
            $request,
            null,
            12,
            $limitToPrimary ? $primaryBrand : null
        );

        return view('pages.shop-right-sidebar', array_merge($data, [
            'categories' => self::CATEGORY_LABELS,
            'activeCategory' => null,
            'activeBrand' => $limitToPrimary ? $primaryBrand : null,
        ]));
    }

    public function show(?string $slug = null): View
    {
        if (! Schema::hasTable('products')) {
            abort(404);
        }

        $this->primeUserProductState();
        $primaryBrand = $this->primaryBrand();
        $limitToPrimary = $this->shouldLimitToPrimaryBrand();

        $productQuery = Product::where('status', 'published')
            ->whereHas('brand', fn (Builder $builder) => $builder->where('is_published', true))
            ->with('brand');

        if ($limitToPrimary && $primaryBrand) {
            $productQuery->where('brand_id', $primaryBrand->id);
        }

        $product = $productQuery
            ->when($slug, fn ($query) => $query->where('slug', $slug))
            ->firstOrFail();

        $relatedQuery = Product::where('status', 'published')
            ->whereHas('brand', fn (Builder $builder) => $builder->where('is_published', true))
            ->whereKeyNot($product->getKey());

        if ($limitToPrimary && $primaryBrand) {
            $relatedQuery->where('brand_id', $primaryBrand->id);
        }

        $relatedProducts = $relatedQuery->latest('updated_at')
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
        $product->loadMissing('brand');

        $basePrice = (float) ($product->price ?? 0);
        $baseSale = $product->sale_price ? (float) $product->sale_price : null;
        $hasSale = $baseSale && $baseSale > 0 && $baseSale < $basePrice;

        $displayBasePrice = $hasSale ? $baseSale : $basePrice;
        $displayPriceValue = Money::convertToDisplay($displayBasePrice);
        $displayPriceFormatted = Money::format($displayBasePrice);
        $originalPriceFormatted = $hasSale ? Money::format($basePrice) : null;
        $discountLabel = null;

        if ($hasSale) {
            $percent = max(1, round((1 - ($baseSale / $basePrice)) * 100));
            $discountLabel = "Save {$percent}%";
        }

        $rating = round((float) $product->average_rating, 1);
        $reviews = (int) $product->reviews_count;
        $colors = collect($product->options['colors'] ?? [])
            ->push($product->primary_color)
            ->filter()
            ->unique()
            ->values()
            ->all();
        $brand = $product->brand;
        $isWishlisted = in_array($product->id, $this->wishlistProductIds, true);
        $isInCart = in_array($product->id, $this->cartProductIds, true);
        $gallery = collect($product->gallery_images ?? [])
            ->prepend($product->featured_image)
            ->filter()
            ->map(fn (string $path) => $this->resolveImageUrl($path))
            ->filter()
            ->unique()
            ->values();
        $primaryImage = $gallery->first() ?? $this->resolveImageUrl('assets/img/product-img-1.jpg');

        return [
            'id' => $product->id,
            'title' => $product->name,
            'category' => ucfirst($product->category ?? 'collection'),
            'category_route' => 'shop.category',
            'category_url' => route('shop.category', ['category' => $product->category ?? 'men']),
            'brand' => $brand?->name,
            'brand_slug' => $brand?->slug,
            'brand_url' => $brand && $brand->is_published ? route('shop.brand', ['slug' => $brand->slug]) : null,
            'price' => $displayPriceFormatted,
            'price_value' => $displayPriceValue,
            'original_price' => $originalPriceFormatted,
            'discount' => $discountLabel,
            'image' => $primaryImage,
            'gallery' => $gallery->values()->all(),
            'details_url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'rating' => $rating,
            'rating_label' => $rating ? number_format($rating, 1) . ' / 5' : null,
            'reviews' => $reviews,
            'colors' => $colors,
            'primary_color' => $product->primary_color,
            'in_wishlist' => $isWishlisted,
            'in_cart' => $isInCart,
            'share_title' => $product->name,
            'share_url' => route('shop.details', ['slug' => $product->slug ?? $product->id]),
            'image_alt' => $product->meta_title ?? $product->name,
        ];
    }

    protected function resolveShopData(Request $request, ?string $category, int $perPage, ?Brand $brand = null): array
    {
        if (! Schema::hasTable('products')) {
            return [
                'products' => $this->emptyPaginator($request, $perPage),
                'filters' => $this->defaultFilters(),
                'priceRange' => ['min' => 0, 'max' => 0],
                'filterOptions' => [
                    'colors' => [],
                    'ratings' => $this->buildStaticRatingOptions(),
                    'sorts' => self::SORT_OPTIONS,
                ],
                'featuredProducts' => collect(),
                'brandOptions' => [],
                'activeFilters' => collect(),
            ];
        }

        $limitToPrimary = $this->shouldLimitToPrimaryBrand();
        $primaryBrand = $this->primaryBrand();

        $baseQuery = Product::query()
            ->with('brand')
            ->where('status', 'published')
            ->whereHas('brand', fn (Builder $builder) => $builder->where('is_published', true));

        if ($limitToPrimary && $primaryBrand) {
            $baseQuery->where('brand_id', $primaryBrand->id);
        }

        if ($category) {
            $baseQuery->where('category', $category);
        }

        if ($brand) {
            $baseQuery->where('brand_id', $brand->id);
        }

        $priceRange = $this->resolvePriceBounds(clone $baseQuery);
        $filters = $this->extractFilters($request, $priceRange, $brand);

        $productQuery = clone $baseQuery;
        $this->applyFilters($productQuery, $filters);
        $this->applySort($productQuery, $filters['sort']);

        $paginator = $productQuery
            ->paginate($perPage)
            ->withQueryString();

        $paginator->getCollection()->transform(fn (Product $product) => $this->transformProduct($product));

        $colorOptions = $this->buildColorOptions(clone $baseQuery, $filters);
        $ratingOptions = $this->buildRatingOptions(clone $baseQuery, $filters);
        $featuredProducts = $this->collectFeaturedProducts(clone $baseQuery, $filters);
        $brandOptions = $this->buildBrandOptions(clone $baseQuery, $filters);

        return [
            'products' => $paginator,
            'filters' => $filters,
            'priceRange' => $priceRange,
            'filterOptions' => [
                'colors' => $colorOptions,
                'ratings' => $ratingOptions,
                'sorts' => self::SORT_OPTIONS,
            ],
            'featuredProducts' => $featuredProducts,
            'brandOptions' => $brandOptions,
            'activeFilters' => $this->formatActiveFilters($filters, $category),
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

    protected function defaultFilters(): array
    {
        return [
            'search' => null,
            'price_min' => 0,
            'price_max' => 0,
            'price_floor' => 0,
            'price_ceiling' => 0,
            'price_requested' => false,
            'color' => null,
            'rating' => null,
            'brand' => null,
            'brand_slug' => null,
            'brand_id' => null,
            'sort' => 'newest',
        ];
    }

    protected function extractFilters(Request $request, array $priceRange, ?Brand $brandOverride = null): array
    {
        $defaults = $this->defaultFilters();
        $floor = (float) ($priceRange['min'] ?? 0);
        $ceiling = (float) ($priceRange['max'] ?? 0);

        $search = trim((string) $request->query('q', ''));
        $priceInput = $request->query('price', []);
        $priceRequested = is_array($priceInput) && ($request->has('price')); // ensure array & query param exists
        $requestedMin = is_array($priceInput) ? ($priceInput['min'] ?? null) : null;
        $requestedMax = is_array($priceInput) ? ($priceInput['max'] ?? null) : null;

        $min = is_numeric($requestedMin) ? max($floor, (float) $requestedMin) : $floor;
        $max = is_numeric($requestedMax) ? min($ceiling, (float) $requestedMax) : $ceiling;

        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        $color = $request->query('color');
        $rating = $request->integer('rating');
        $brandSlugParam = trim((string) $request->query('brand', ''));
        $sort = $request->query('sort', 'newest');

        if (! array_key_exists($sort, self::SORT_OPTIONS)) {
            $sort = 'newest';
        }

        if (! array_key_exists($rating, self::RATING_BUCKETS)) {
            $rating = null;
        }

        $brandModel = $brandOverride;

        if (! $brandModel && $brandSlugParam !== '') {
            $brandModel = Brand::query()
                ->where('slug', $brandSlugParam)
                ->where('is_published', true)
                ->first();
        }

        if ($this->shouldLimitToPrimaryBrand()) {
            $primaryBrand = $this->primaryBrand();

            if ($primaryBrand && (! $brandModel || $brandModel->id !== $primaryBrand->id)) {
                $brandModel = ($brandOverride && $brandOverride->id === $primaryBrand->id) ? $brandOverride : null;
            }
        }

        $priceChanged = ($min > $floor) || ($max < $ceiling);

        return array_merge($defaults, [
            'search' => $search ?: null,
            'price_min' => round($min, 2),
            'price_max' => round($max, 2),
            'price_floor' => round($floor, 2),
            'price_ceiling' => round($ceiling, 2),
            'price_requested' => $priceRequested && $priceChanged,
            'color' => $color ?: null,
            'rating' => $rating,
            'brand' => $brandModel?->name,
            'brand_slug' => $brandModel?->slug,
            'brand_id' => $brandModel?->id,
            'sort' => $sort,
        ]);
    }

    protected function applyFilters(Builder $query, array $filters, array $except = []): void
    {
        if (! in_array('search', $except, true) && $filters['search']) {
            $term = $filters['search'];
            $query->where(function (Builder $builder) use ($term) {
                $builder->where('name', 'like', "%{$term}%")
                    ->orWhere('summary', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if (! in_array('price', $except, true) && ($filters['price_requested'] ?? false)) {
            $min = $filters['price_min'];
            $max = $filters['price_max'];
            $query->whereRaw($this->effectivePriceExpression() . ' BETWEEN ? AND ?', [$min, $max]);
        }

        if (! in_array('color', $except, true) && $filters['color']) {
            $color = $filters['color'];
            $query->where(function (Builder $builder) use ($color) {
                $builder->where('primary_color', $color)
                    ->orWhereJsonContains('options->colors', $color);
            });
        }

        if (! in_array('rating', $except, true) && $filters['rating']) {
            $threshold = $this->ratingThreshold($filters['rating']);

            if ($filters['rating'] === 5) {
                $query->where('average_rating', '>=', $threshold);
            } else {
                $query->where('average_rating', '>=', $threshold);
            }
        }

        if (! in_array('brand', $except, true) && $filters['brand_id']) {
            $query->where('brand_id', $filters['brand_id']);
        }
    }

    protected function applySort(Builder $query, string $sort): void
    {
        switch ($sort) {
            case 'price_low_high':
                $query->orderByRaw($this->effectivePriceExpression() . ' ASC');
                break;
            case 'price_high_low':
                $query->orderByRaw($this->effectivePriceExpression() . ' DESC');
                break;
            case 'rating_high_low':
                $query->orderByDesc('average_rating')->orderByDesc('reviews_count');
                break;
            case 'most_reviewed':
                $query->orderByDesc('reviews_count')->orderByDesc('average_rating');
                break;
            case 'newest':
            default:
                $query->orderByDesc('updated_at');
                break;
        }
    }

    protected function resolvePriceBounds(Builder $query): array
    {
        $stats = (clone $query)
            ->selectRaw('MIN(' . $this->effectivePriceExpression() . ') as min_price, MAX(' . $this->effectivePriceExpression() . ') as max_price')
            ->first();

        if (! $stats || $stats->min_price === null || $stats->max_price === null) {
            return ['min' => 0, 'max' => 0];
        }

        return [
            'min' => floor((float) $stats->min_price),
            'max' => ceil((float) $stats->max_price),
        ];
    }

    protected function buildColorOptions(Builder $query, array $filters): array
    {
        $colorQuery = clone $query;
        $this->applyFilters($colorQuery, $filters, ['color']);

        $colors = $colorQuery->get()
            ->flatMap(function (Product $product) {
                $palette = collect($product->options['colors'] ?? []);

                if ($product->primary_color) {
                    $palette->push($product->primary_color);
                }

                return $palette;
            })
            ->filter()
            ->map(fn ($color) => trim((string) $color))
            ->filter()
            ->map(fn ($color) => Str::title($color));

        $counts = $colors->countBy();

        return $counts
            ->map(fn ($count, $label) => [
                'label' => $label,
                'value' => $label,
                'class' => Str::slug($label),
                'count' => $count,
            ])
            ->sortByDesc('count')
            ->values()
            ->all();
    }

    protected function buildBrandOptions(Builder $query, array $filters): array
    {
        $brandQuery = clone $query;
        $this->applyFilters($brandQuery, $filters, ['brand']);

        $brandProducts = $brandQuery
            ->whereNotNull('brand_id')
            ->select('brand_id', 'category')
            ->get()
            ->groupBy('brand_id');

        if ($this->shouldLimitToPrimaryBrand()) {
            $primaryBrand = $this->primaryBrand();

            if ($primaryBrand) {
                $brandProducts = $brandProducts->filter(fn ($items, $brandId) => $brandId === $primaryBrand->id);
            }
        }

        if ($brandProducts->isEmpty()) {
            if ($filters['brand_id']) {
                $fallbackBrand = Brand::query()
                    ->where('id', $filters['brand_id'])
                    ->where('is_published', true)
                    ->first();

                if ($fallbackBrand) {
                    return [[
                        'id' => $fallbackBrand->id,
                        'brand' => $fallbackBrand->name,
                        'slug' => $fallbackBrand->slug,
                        'count' => 0,
                        'categories' => [],
                        'active' => true,
                    ]];
                }
            }

            return [];
        }

        $brands = Brand::query()
            ->whereIn('id', $brandProducts->keys())
            ->where('is_published', true)
            ->get()
            ->keyBy('id');

        if ($this->shouldLimitToPrimaryBrand()) {
            $primaryBrand = $this->primaryBrand();

            if ($primaryBrand) {
                $brands = $brands->filter(fn (Brand $brand) => $brand->id === $primaryBrand->id);
            }
        }

        return $brandProducts
            ->map(function (Collection $items, int $brandId) use ($brands, $filters) {
                $brand = $brands->get($brandId);

                if (! $brand) {
                    return null;
                }

                $categories = collect(self::CATEGORY_LABELS)
                    ->map(function ($label, $key) use ($items) {
                        $count = $items->where('category', $key)->count();

                        return [
                            'key' => $key,
                            'label' => $label,
                            'count' => $count,
                        ];
                    })
                    ->filter(fn ($entry) => $entry['count'] > 0)
                    ->values();

                return [
                    'id' => $brandId,
                    'brand' => $brand->name,
                    'slug' => $brand->slug,
                    'count' => $items->count(),
                    'categories' => $categories->all(),
                    'active' => ($filters['brand_id'] ?? null) === $brandId,
                ];
            })
            ->filter()
            ->sortBy(fn ($item) => $item['brand'])
            ->values()
            ->all();
    }

    protected function buildRatingOptions(Builder $query, array $filters): array
    {
        $ratingQuery = clone $query;
        $this->applyFilters($ratingQuery, $filters, ['rating']);

        $options = [];

        foreach (self::RATING_BUCKETS as $value => $label) {
            $bucketQuery = clone $ratingQuery;
            $threshold = $this->ratingThreshold($value);
            $bucketQuery->where('average_rating', '>=', $threshold);

            $options[] = [
                'value' => $value,
                'label' => $label,
                'count' => $bucketQuery->count(),
            ];
        }

        return $options;
    }

    protected function collectFeaturedProducts(Builder $query, array $filters): Collection
    {
        $featuredQuery = clone $query;
        $featuredQuery->where('is_featured', true);
        $this->applyFilters($featuredQuery, $filters);
        $this->applySort($featuredQuery, 'rating_high_low');

        $featured = $featuredQuery->take(3)->get();

        if ($featured->count() < 3) {
            $fallbackQuery = clone $query;
            $this->applyFilters($fallbackQuery, $filters);
            $this->applySort($fallbackQuery, 'rating_high_low');
            $featured = $featured->concat(
                $fallbackQuery
                    ->take(3)
                    ->get()
            );
        }

        return $featured
            ->unique('id')
            ->values()
            ->take(3)
            ->map(fn (Product $product) => $this->transformProduct($product));
    }

    protected function effectivePriceExpression(): string
    {
        return 'CASE WHEN sale_price IS NOT NULL AND sale_price > 0 AND sale_price < price THEN sale_price ELSE price END';
    }

    protected function formatActiveFilters(array $filters, ?string $category): Collection
    {
        $active = collect();

        if ($category) {
            $active->push([
                'key' => 'category',
                'label' => self::CATEGORY_LABELS[$category] ?? Str::title($category),
            ]);
        }

        if ($filters['search']) {
            $active->push([
                'key' => 'q',
                'label' => 'Search: "' . $filters['search'] . '"',
            ]);
        }

        if ($filters['brand']) {
            $primaryBrand = $this->shouldLimitToPrimaryBrand() ? $this->primaryBrand() : null;
            $allowed = ! $primaryBrand || $filters['brand_id'] === $primaryBrand->id;

            if ($allowed) {
                $active->push([
                    'key' => 'brand',
                    'label' => 'Brand: ' . $filters['brand'],
                ]);
            }
        }

        if ($filters['color']) {
            $active->push([
                'key' => 'color',
                'label' => 'Color: ' . $filters['color'],
            ]);
        }

        if ($filters['rating']) {
            $active->push([
                'key' => 'rating',
                'label' => 'Rating: ' . (self::RATING_BUCKETS[$filters['rating']] ?? ($filters['rating'] . '+')),
            ]);
        }

        $defaultMin = $filters['price_floor'];
        $defaultMax = $filters['price_ceiling'];

        if (($filters['price_requested'] ?? false) && ($defaultMin !== $filters['price_min'] || $defaultMax !== $filters['price_max'])) {
            $active->push([
                'key' => 'price',
                'label' => 'Price: $' . number_format($filters['price_min'], 0) . ' - $' . number_format($filters['price_max'], 0),
            ]);
        }

        if ($filters['sort'] !== 'newest') {
            $active->push([
                'key' => 'sort',
                'label' => 'Sorted by: ' . (self::SORT_OPTIONS[$filters['sort']] ?? Str::title(str_replace('_', ' ', $filters['sort']))),
            ]);
        }

        return $active;
    }

    protected function buildStaticRatingOptions(): array
    {
        return collect(self::RATING_BUCKETS)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
                'count' => 0,
            ])->values()->all();
    }

    protected function ratingThreshold(int $bucket): float
    {
        return match ($bucket) {
            5 => 4.75,
            4 => 4.0,
            3 => 3.0,
            2 => 2.0,
            default => 1.0,
        };
    }

    protected function resolveImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset($path);
    }

    protected function primaryBrand(): ?Brand
    {
        if ($this->primaryBrandResolved) {
            return $this->primaryBrandCache;
        }

        $this->primaryBrandResolved = true;

        if (! Schema::hasTable('brands')) {
            return $this->primaryBrandCache = null;
        }

        $preferredSlug = config('catalog.primary_brand_slug');
        $query = Brand::query()->where('is_published', true);

        if ($preferredSlug) {
            $preferredBrand = (clone $query)->where('slug', $preferredSlug)->first();

            if ($preferredBrand) {
                return $this->primaryBrandCache = $preferredBrand;
            }
        }

        return $this->primaryBrandCache = $query->orderBy('name')->first();
    }

    protected function limitToPrimaryBrand(): bool
    {
        return (bool) config('catalog.limit_to_primary_brand', false);
    }

    protected function shouldLimitToPrimaryBrand(): bool
    {
        if ($this->limitPrimaryCache !== null) {
            return $this->limitPrimaryCache;
        }

        if (! $this->limitToPrimaryBrand()) {
            return $this->limitPrimaryCache = false;
        }

        $primaryBrand = $this->primaryBrand();

        if (! $primaryBrand) {
            return $this->limitPrimaryCache = false;
        }

        if (! Schema::hasTable('brands')) {
            return $this->limitPrimaryCache = false;
        }

        $secondaryExists = Brand::query()
            ->where('is_published', true)
            ->whereKeyNot($primaryBrand->id)
            ->exists();

        return $this->limitPrimaryCache = ! $secondaryExists;
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
