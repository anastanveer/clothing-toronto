<?php

namespace App\Services\RemoteCatalog;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class RemoteCatalogSyncer
{
    protected array $config;

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? Config::get('remote_store', []);
    }

    public function sync(?callable $reporter = null): array
    {
        $reporter ??= static fn (): null => null;

        $results = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $collections = $this->fetchCollections();

        $page = 1;

        do {
            $products = $this->fetchProducts($page);

            if ($products === null) {
                break;
            }

            if ($products->isEmpty()) {
                break;
            }

            $reporter("Processing page {$page} ({$products->count()} products)");

            foreach ($products as $payload) {
                try {
                    $status = $this->storeProduct($payload, $collections);
                    $results[$status] = ($results[$status] ?? 0) + 1;
                } catch (Throwable $exception) {
                    $results['errors'][] = [
                        'product' => Arr::get($payload, 'handle'),
                        'message' => $exception->getMessage(),
                    ];
                    $reporter(sprintf(
                        'Failed syncing %s: %s',
                        Arr::get($payload, 'handle', 'unknown'),
                        $exception->getMessage()
                    ));
                }
            }

            $page++;
        } while (true);

        return $results;
    }

    protected function fetchProducts(int $page = 1): ?Collection
    {
        $endpoint = $this->endpoint('products_endpoint');

        if (! $endpoint) {
            return null;
        }

        $query = array_merge(
            $this->config['products_query'] ?? [],
            ['page' => $page]
        );

        $response = $this->http()->get($endpoint, $query);

        if (! $response->successful()) {
            return collect();
        }

        $data = $response->json();

        return collect($data['products'] ?? []);
    }

    protected function fetchCollections(): Collection
    {
        $endpoint = $this->endpoint('collections_endpoint');

        if (! $endpoint) {
            return collect();
        }

        $response = $this->http()->get($endpoint);

        if (! $response->successful()) {
            return collect();
        }

        $data = $response->json();

        return collect($data['collections'] ?? [])
            ->keyBy('handle');
    }

    protected function storeProduct(array $payload, Collection $collections): string
    {
        $slug = Str::slug(Arr::get($payload, 'handle', Arr::get($payload, 'title')));

        if (! $slug) {
            return 'skipped';
        }

        $variant = collect(Arr::get($payload, 'variants', []))->first() ?: [];
        $basePrice = (float) Arr::get($variant, 'price', 0);
        $baseCompareAt = (float) Arr::get($variant, 'compare_at_price', 0);

        $price = $baseCompareAt > $basePrice ? $baseCompareAt : $basePrice;
        $salePrice = $baseCompareAt > $basePrice ? $basePrice : null;

        $category = $this->inferCategory($payload, $collections);
        $brandId = $this->resolveBrandId(Arr::get($payload, 'vendor'));
        $summary = Str::limit(strip_tags(Arr::get($payload, 'body_html', '')), 200);
        $description = Arr::get($payload, 'body_html');
        $images = collect(Arr::get($payload, 'images', []))
            ->pluck('src')
            ->filter()
            ->values()
            ->all();

        $options = [
            'remote' => [
                'source' => 'shopify',
                'id' => Arr::get($payload, 'id'),
                'handle' => Arr::get($payload, 'handle'),
                'tags' => Arr::get($payload, 'tags', []),
                'vendor' => Arr::get($payload, 'vendor'),
                'product_type' => Arr::get($payload, 'product_type'),
                'synced_at' => now()->toIso8601String(),
            ],
        ];

        $attributes = [
            'name' => Arr::get($payload, 'title'),
            'slug' => $slug,
            'sku' => Arr::get($variant, 'sku') ?: 'remote-' . Arr::get($payload, 'id'),
            'summary' => $summary,
            'description' => $description,
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => Arr::get($variant, 'available') ? 100 : 0,
            'is_featured' => $this->isFeatured($payload),
            'status' => 'published',
            'meta_title' => Arr::get($payload, 'title'),
            'meta_description' => Str::limit(strip_tags($description), 150),
            'featured_image' => $images[0] ?? null,
            'gallery_images' => $images,
            'options' => $options,
            'average_rating' => 0,
            'reviews_count' => 0,
            'primary_color' => null,
            'category' => $category,
        ];

        if ($brandId) {
            $attributes['brand_id'] = $brandId;
        }

        /** @var \App\Models\Product|null $existing */
        $existing = Product::where('slug', $slug)->first();

        if ($existing) {
            $existing->fill($attributes);
            $existing->options = array_merge($existing->options ?? [], $options);
            $existing->save();

            return 'updated';
        }

        Product::create($attributes);

        return 'created';
    }

    protected function inferCategory(array $payload, Collection $collections): string
    {
        $tags = collect(Arr::get($payload, 'tags', []))
            ->map(fn (string $tag) => Str::of($tag)->lower()->value());

        if ($tags->contains(fn (string $tag) => Str::contains($tag, 'women'))) {
            return 'women';
        }

        if ($tags->contains(fn (string $tag) => Str::contains($tag, 'kid'))) {
            return 'kids';
        }

        if ($tags->contains(fn (string $tag) => Str::contains($tag, 'men'))) {
            return 'men';
        }

        $handle = Str::of(Arr::get($payload, 'handle', ''))->lower();

        if ($handle->contains('women')) {
            return 'women';
        }

        if ($handle->contains('kid')) {
            return 'kids';
        }

        return 'men';
    }

    protected function isFeatured(array $payload): bool
    {
        $tags = collect(Arr::get($payload, 'tags', []))
            ->map(fn (string $tag) => Str::lower($tag));

        return $tags->contains(fn (string $tag) => Str::contains($tag, ['new', 'featured']));
    }

    protected function resolveBrandId(?string $vendor): ?int
    {
        $vendor = trim((string) $vendor);

        if ($vendor === '') {
            return null;
        }

        $slug = Str::slug($vendor);

        /** @var \App\Models\Brand $brand */
        $brand = Brand::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $vendor,
                'summary' => null,
                'description' => null,
                'is_published' => true,
            ]
        );

        return $brand->id;
    }

    protected function http()
    {
        $baseUrl = rtrim($this->config['base_url'] ?? '', '/');

        $timeout = Arr::get($this->config, 'http.timeout', 20);
        $times = Arr::get($this->config, 'http.retry.times', 2);
        $sleep = Arr::get($this->config, 'http.retry.sleep', 250);

        return Http::baseUrl($baseUrl)
            ->timeout($timeout)
            ->retry($times, $sleep);
    }

    protected function endpoint(string $key): ?string
    {
        $endpoint = $this->config[$key] ?? null;

        if (! $endpoint) {
            return null;
        }

        return Str::start($endpoint, '/');
    }
}
