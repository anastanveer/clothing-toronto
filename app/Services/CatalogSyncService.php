<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CatalogSyncService
{
    public function sync(?string $brandKey = null): array
    {
        $brands = $this->resolveBrands($brandKey);

        $summary = [
            'collections' => 0,
            'products' => 0,
            'variants' => 0,
            'images' => 0,
            'collection_links' => 0,
            'brands' => [],
        ];

        foreach ($brands as $key => $brand) {
            $sourceUrl = trim((string) ($brand['source_url'] ?? ''));
            if ($sourceUrl === '') {
                continue;
            }

            $result = $this->syncBrand($key, $sourceUrl);
            $summary['collections'] += $result['collections'];
            $summary['products'] += $result['products'];
            $summary['variants'] += $result['variants'];
            $summary['images'] += $result['images'];
            $summary['collection_links'] += $result['collection_links'];
            $summary['brands'][$key] = $result;
        }

        return $summary;
    }

    private function resolveBrands(?string $brandKey): array
    {
        $brands = (array) config('catalog.brands', []);

        if ($brandKey) {
            return isset($brands[$brandKey]) ? [$brandKey => $brands[$brandKey]] : [];
        }

        return array_filter($brands, function (array $brand): bool {
            if (empty($brand['enabled'])) {
                return false;
            }
            return trim((string) ($brand['source_url'] ?? '')) !== '';
        });
    }

    private function syncBrand(string $brandKey, string $baseUrl): array
    {
        $baseUrl = rtrim($baseUrl, '/');

        $usingLocalData = false;

        try {
            $collections = $this->fetchPaginated("{$baseUrl}/collections.json?limit=250", 'collections');
        } catch (\Throwable $exception) {
            $collections = $this->loadLocalJson($this->resolveLocalJsonPath('collections.json'), 'collections');
            $usingLocalData = true;
        }

        try {
            $products = $this->fetchPaginated("{$baseUrl}/products.json?limit=250", 'products');
        } catch (\Throwable $exception) {
            $products = $this->loadLocalJson($this->resolveLocalJsonPath('products.json'), 'products');
            $usingLocalData = true;
        }

        if (!$collections) {
            $collections = $this->loadLocalJson($this->resolveLocalJsonPath('collections.json'), 'collections');
            $usingLocalData = $usingLocalData || !empty($collections);
        }

        if (!$products) {
            $products = $this->loadLocalJson($this->resolveLocalJsonPath('products.json'), 'products');
            $usingLocalData = $usingLocalData || !empty($products);
        }

        if (empty($collections) && empty($products)) {
            return [
                'collections' => 0,
                'products' => 0,
                'variants' => 0,
                'images' => 0,
                'collection_links' => 0,
            ];
        }

        $collectionModels = [];
        $productModels = [];
        $variantCount = 0;
        $imageCount = 0;

        $runner = DB::getDriverName() === 'sqlite'
            ? function (callable $callback) {
                return $callback();
            }
            : function (callable $callback) {
                return DB::transaction($callback);
            };

        return $runner(function () use (
            $collections,
            $products,
            $baseUrl,
            $brandKey,
            $usingLocalData,
            &$collectionModels,
            &$productModels,
            &$variantCount,
            &$imageCount
        ) {
            $this->purgeBrandData($brandKey);

            foreach ($collections as $collection) {
                $collectionModels[$collection['handle']] = Collection::updateOrCreate(
                    [
                        'shopify_id' => $collection['id'],
                        'brand_key' => $brandKey,
                    ],
                    [
                        'title' => $collection['title'] ?? $collection['handle'],
                        'handle' => $collection['handle'],
                        'source_updated_at' => $collection['updated_at'] ?? null,
                    ]
                );
            }

            foreach ($products as $product) {
                $firstVariant = $product['variants'][0] ?? [];

                $rawTags = $product['tags'] ?? null;
                $tags = is_array($rawTags) ? implode(', ', $rawTags) : $rawTags;

                $productModel = Product::updateOrCreate(
                    [
                        'shopify_id' => $product['id'],
                        'brand_key' => $brandKey,
                    ],
                    [
                        'title' => $product['title'] ?? $product['handle'],
                        'handle' => $product['handle'],
                        'body_html' => $product['body_html'] ?? null,
                        'product_type' => $product['product_type'] ?? null,
                        'vendor' => $product['vendor'] ?? null,
                        'tags' => $tags,
                        'price' => $firstVariant['price'] ?? null,
                        'compare_at_price' => $firstVariant['compare_at_price'] ?? null,
                        'available' => $firstVariant['available'] ?? true,
                        'source_created_at' => $product['created_at'] ?? null,
                        'source_updated_at' => $product['updated_at'] ?? null,
                    ]
                );

                $productModel->variants()->delete();
                foreach ($product['variants'] ?? [] as $variant) {
                    $productModel->variants()->create([
                        'shopify_id' => $variant['id'],
                        'title' => $variant['title'] ?? 'Default Title',
                        'sku' => $variant['sku'] ?? null,
                        'option1' => $variant['option1'] ?? null,
                        'option2' => $variant['option2'] ?? null,
                        'option3' => $variant['option3'] ?? null,
                        'price' => $variant['price'] ?? null,
                        'compare_at_price' => $variant['compare_at_price'] ?? null,
                        'inventory_quantity' => $variant['inventory_quantity'] ?? null,
                        'position' => $variant['position'] ?? 1,
                        'available' => $variant['available'] ?? true,
                    ]);
                }
                $variantCount += count($product['variants'] ?? []);

                $productModel->images()->delete();
                foreach ($product['images'] ?? [] as $image) {
                    $productModel->images()->create([
                        'shopify_id' => $image['id'],
                        'src' => $image['src'] ?? '',
                        'position' => $image['position'] ?? 1,
                        'width' => $image['width'] ?? null,
                        'height' => $image['height'] ?? null,
                    ]);
                }
                $imageCount += count($product['images'] ?? []);

                $productModels[$product['id']] = $productModel;
            }

            $collectionIds = Collection::query()
                ->where('brand_key', $brandKey)
                ->pluck('id');

            if ($collectionIds->isNotEmpty()) {
                DB::table('collection_product')
                    ->whereIn('collection_id', $collectionIds->all())
                    ->delete();
            }

            $pivotRows = [];

            foreach ($collectionModels as $handle => $collectionModel) {
                $collectionProducts = $this->fetchCollectionProducts($baseUrl, $handle);
                if ($usingLocalData && empty($collectionProducts)) {
                    $collectionProducts = $this->matchCollectionProductIds(
                        $handle,
                        $collectionModel->title ?? $handle,
                        $products
                    );
                }

                foreach ($collectionProducts as $collectionProduct) {
                    $productId = is_array($collectionProduct)
                        ? ($collectionProduct['id'] ?? null)
                        : $collectionProduct;
                    if (!$productId) {
                        continue;
                    }
                    $productModel = $productModels[$productId] ?? null;
                    if (!$productModel) {
                        continue;
                    }
                    $pivotRows[] = [
                        'collection_id' => $collectionModel->id,
                        'product_id' => $productModel->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if ($pivotRows) {
                DB::table('collection_product')->insert($pivotRows);
            }

            return [
                'collections' => count($collectionModels),
                'products' => count($productModels),
                'variants' => $variantCount,
                'images' => $imageCount,
                'collection_links' => count($pivotRows),
            ];
        });
    }

    private function purgeBrandData(string $brandKey): void
    {
        $collectionIds = Collection::query()
            ->where('brand_key', $brandKey)
            ->pluck('id');

        if ($collectionIds->isNotEmpty()) {
            DB::table('collection_product')
                ->whereIn('collection_id', $collectionIds->all())
                ->delete();
        }

        $productIds = Product::query()
            ->where('brand_key', $brandKey)
            ->pluck('id');

        if ($productIds->isNotEmpty()) {
            DB::table('product_images')
                ->whereIn('product_id', $productIds->all())
                ->delete();
            DB::table('product_variants')
                ->whereIn('product_id', $productIds->all())
                ->delete();
        }

        Product::query()
            ->where('brand_key', $brandKey)
            ->delete();

        Collection::query()
            ->where('brand_key', $brandKey)
            ->delete();
    }

    private function fetchJson(string $url): array
    {
        return Http::retry(3, 200)
            ->get($url)
            ->throw()
            ->json();
    }

    private function fetchCollectionProducts(string $baseUrl, string $handle): array
    {
        $url = "{$baseUrl}/collections/{$handle}/products.json?limit=250";

        try {
            return $this->fetchPaginated($url, 'products');
        } catch (\Throwable $exception) {
            return [];
        }
    }

    private function fetchPaginated(string $url, string $key): array
    {
        $items = [];
        $nextUrl = $url;

        while ($nextUrl) {
            $response = Http::retry(3, 200)
                ->get($nextUrl);

            if (!$response->successful()) {
                break;
            }

            $payload = $response->json();
            $items = array_merge($items, $payload[$key] ?? []);
            $nextUrl = $this->nextPageUrl($response->header('Link'));
        }

        return $items;
    }

    private function resolveLocalJsonPath(string $filename): ?string
    {
        $base = trim((string) config('catalog.local_json_path', ''));
        if ($base === '') {
            $base = base_path();
        }

        return rtrim($base, '/') . '/' . ltrim($filename, '/');
    }

    private function loadLocalJson(?string $path, string $key): array
    {
        if (!$path || !is_file($path)) {
            return [];
        }

        $payload = json_decode(file_get_contents($path), true);
        if (!is_array($payload)) {
            return [];
        }

        if (isset($payload[$key]) && is_array($payload[$key])) {
            return $payload[$key];
        }

        return array_values($payload);
    }

    private function matchCollectionProductIds(string $handle, string $title, array $products): array
    {
        $handleLower = strtolower($handle);
        $rawTokens = preg_split('/[^a-z0-9]+/', strtolower($handle . ' ' . $title));
        $tokens = [];

        foreach ($rawTokens as $token) {
            $token = trim($token);
            if ($token === '') {
                continue;
            }
            $tokens[] = $token;
            if (preg_match('/[a-z]+\\d+/', $token)) {
                $letters = preg_replace('/\\d+/', '', $token);
                if ($letters !== '') {
                    $tokens[] = $letters;
                }
            }
        }

        $stop = ['all', 'collection', 'collections', 'vol', 'season', 'frontpage'];
        $keywords = array_values(array_unique(array_filter($tokens, function (string $token) use ($stop): bool {
            return !in_array($token, $stop, true);
        })));
        $keywords = array_values(array_filter($keywords, function (string $token): bool {
            return $token !== '' && !ctype_digit($token);
        }));

        $isFrontpage = $handleLower === 'frontpage';
        $isSale = str_contains($handleLower, 'sale');
        if ($isSale) {
            $keywords = array_values(array_filter($keywords, fn (string $token) => $token !== 'sale'));
        }
        $requiresMen = str_contains($handleLower, 'men');
        $requiresWomen = str_contains($handleLower, 'women');
        $matched = [];

        foreach ($products as $product) {
            if (!is_array($product)) {
                continue;
            }

            $variants = $product['variants'] ?? [];
            $hasDiscount = false;
            foreach ($variants as $variant) {
                $price = (float) ($variant['price'] ?? 0);
                $compare = (float) ($variant['compare_at_price'] ?? 0);
                if ($compare > 0 && $compare > $price) {
                    $hasDiscount = true;
                    break;
                }
            }

            if ($isSale && !$hasDiscount) {
                continue;
            }

            if ($isFrontpage && empty($keywords)) {
                $matched[] = $product['id'] ?? null;
                continue;
            }

            $tags = $product['tags'] ?? '';
            $tagList = is_array($tags)
                ? $tags
                : array_filter(array_map('trim', explode(',', (string) $tags)));
            $tagText = strtolower(implode(' ', $tagList));
            $titleText = strtolower((string) ($product['title'] ?? ''));

            if ($requiresMen && !str_contains($tagText, 'men') && !str_contains($titleText, 'men')) {
                continue;
            }

            if ($requiresWomen && !str_contains($tagText, 'women') && !str_contains($titleText, 'women')) {
                continue;
            }

            $hasKeyword = false;
            if (empty($keywords)) {
                $hasKeyword = true;
            } else {
                foreach ($keywords as $keyword) {
                    if ($keyword === '') {
                        continue;
                    }
                    if (str_contains($tagText, $keyword) || str_contains($titleText, $keyword)) {
                        $hasKeyword = true;
                        break;
                    }
                }
            }

            if ($hasKeyword) {
                $matched[] = $product['id'] ?? null;
            }
        }

        return array_values(array_filter($matched));
    }

    private function nextPageUrl(?string $linkHeader): ?string
    {
        if (!$linkHeader) {
            return null;
        }

        $parts = explode(',', $linkHeader);
        foreach ($parts as $part) {
            if (str_contains($part, 'rel="next"')) {
                if (preg_match('/<([^>]+)>/', $part, $matches)) {
                    return $matches[1] ?? null;
                }
            }
        }

        return null;
    }
}
