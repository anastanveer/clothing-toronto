<?php

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('import:khanabadosh {--wipe}', function () {
    $productFiles = [
        base_path('products.json'),
        base_path('products (1).json'),
    ];

    $missing = collect($productFiles)->filter(fn (string $path) => ! is_file($path));

    if ($missing->isNotEmpty()) {
        $this->error('Missing product files:');
        $missing->each(fn ($path) => $this->line(' - ' . $path));
        return 1;
    }

    $brand = Brand::firstOrCreate(
        ['slug' => 'khanabadosh'],
        [
            'name' => 'Khanabadosh',
            'tagline' => 'Nomadic wardrobe essentials',
            'summary' => 'Signature Pakistani unstitched, seasonal layers, and capsule looks.',
            'description' => 'Khanabadosh brings nomadic elegance to every season with premium unstitched suits, layered separates, and limited seasonal edits handcrafted for wanderers at heart.',
            'is_published' => true,
            'hero_image' => 'assets/img/12.png',
        ]
    );

    if ($this->option('wipe')) {
        $deleted = Product::where('brand_id', $brand->id)->delete();
        $this->info("Deleted {$deleted} existing products for Khanabadosh.");
    }

    $rawProducts = collect($productFiles)
        ->flatMap(function (string $path) {
            $data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
            return collect($data['products'] ?? []);
        })
        ->keyBy(fn (array $product) => $product['id'] ?? Str::uuid()->toString());

    if ($rawProducts->isEmpty()) {
        $this->warn('No products detected in the provided JSON files.');
        return 0;
    }

    $this->info('Importing ' . $rawProducts->count() . ' products...');
    $bar = $this->output->createProgressBar($rawProducts->count());

    $rawProducts->each(function (array $raw) use ($brand, $bar) {
        $bar->advance();

        $variant = Arr::first($raw['variants'] ?? []) ?? [];
        $images = collect($raw['images'] ?? [])->pluck('src')->filter()->unique()->values();
        $featuredImage = Arr::get($raw, 'image.src') ?? $images->first();
        $galleryImages = $images->reject(fn ($src) => $src === $featuredImage)->values();

        $tags = collect($raw['tags'] ?? [])
            ->filter()
            ->map(fn ($tag) => trim((string) $tag))
            ->unique()
            ->values();

        $category = $tags->contains(fn ($tag) => Str::lower($tag) === 'women') ? 'women' : 'men';

        $compareAt = (float) ($variant['compare_at_price'] ?? 0);
        $currentPrice = (float) ($variant['price'] ?? 0);

        $price = $compareAt > 0 ? $compareAt : $currentPrice;
        $salePrice = $compareAt > 0 ? ($currentPrice ?: null) : null;

        $slugSource = $raw['handle'] ?? $raw['title'] ?? Str::uuid()->toString();
        $slug = Str::slug($slugSource);
        if ($slug === '') {
            $slug = Str::slug($raw['title'] ?? ('product-' . Str::random(6)));
        }

        $summary = Str::limit(trim(strip_tags(Arr::get($raw, 'body_html', ''))), 240);

        Product::updateOrCreate(
            [
                'brand_id' => $brand->id,
                'slug' => $slug,
            ],
            [
                'name' => $raw['title'] ?? 'Untitled Product',
                'sku' => $variant['sku'] ?: null,
                'category' => $category,
                'summary' => $summary ?: null,
                'description' => $raw['body_html'] ?? null,
                'price' => $price ?: 0,
                'sale_price' => $salePrice,
                'stock' => 100,
                'is_featured' => $tags->contains(fn ($tag) => Str::lower($tag) === 'new'),
                'status' => 'published',
                'meta_title' => ($raw['title'] ?? 'Khanabadosh Product') . ' | Khanabadosh',
                'meta_description' => $summary ?: null,
                'featured_image' => $featuredImage,
                'gallery_images' => $galleryImages->values()->all() ?: null,
                'options' => [
                    'shopify_id' => $raw['id'] ?? null,
                    'vendor' => $raw['vendor'] ?? null,
                    'product_type' => $raw['product_type'] ?? null,
                    'tags' => $tags,
                ],
            ]
        );
    });

    $bar->finish();
    $this->newLine(2);
    $this->info('Khanabadosh catalog import complete.');

    return 0;
})->purpose('Import Khanabadosh catalog from local Shopify JSON exports');
