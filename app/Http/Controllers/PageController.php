<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Support\CurrencyFormatter;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public function home()
    {
        $brandKey = $this->resolveBrandKey();
        $brandProfiles = $this->brandProfiles();
        $brandCounts = Product::query()
            ->selectRaw('brand_key, COUNT(*) as total')
            ->groupBy('brand_key')
            ->pluck('total', 'brand_key');

        $heroProducts = Product::query()
            ->where('brand_key', $brandKey)
            ->with('images')
            ->orderByDesc('source_created_at')
            ->take(5)
            ->get();

        $newMenProducts = $this->productsFromCollection('new-arrivals', 12, ['Men'], $brandKey);
        if ($newMenProducts->isEmpty()) {
            $newMenProducts = $this->productsByTags(['Men', 'New'], 12, $brandKey);
        }
        if ($newMenProducts->isEmpty()) {
            $newMenProducts = $this->productsByTags(['Men'], 12, $brandKey);
        }

        $menProducts = $this->productsFromCollection('men-all', 12, null, $brandKey);
        if ($menProducts->isEmpty()) {
            $menProducts = $this->productsByTags(['Men'], 12, $brandKey);
        }

        $womenProducts = $this->productsFromCollection('women-all', 12, null, $brandKey);
        if ($womenProducts->isEmpty()) {
            $womenProducts = $this->productsByTags(['Women'], 12, $brandKey);
        }

        $accessoryProducts = $this->productsFromCollection('accessories', 12, ['Accessories'], $brandKey);
        if ($accessoryProducts->isEmpty()) {
            $accessoryProducts = $this->productsByTags(['Accessories'], 12, $brandKey);
        }

        $brands = collect((array) config('catalog.brands', []))
            ->filter(fn ($brand) => !empty($brand['enabled']))
            ->map(function ($brand, $key) use ($brandProfiles, $brandCounts) {
                $profile = $brandProfiles[$key] ?? [];
                $fallbackCount = count($this->fallbackProductsForBrand($key));
                $brand['key'] = $key;
                $brand['tagline'] = $profile['tagline'] ?? 'Curated seasonal essentials for every day.';
                $brand['tone'] = $profile['tone'] ?? 'classic';
                $brand['count'] = (int) ($brandCounts[$key] ?? 0);
                if ($brand['count'] === 0 && $fallbackCount > 0) {
                    $brand['count'] = $fallbackCount;
                }
                return $brand;
            })
            ->values();

        $khanabadoshProducts = collect();
        if ($brands->pluck('key')->contains('khanabadosh')) {
            $khanabadoshProducts = Product::query()
                ->where('brand_key', 'khanabadosh')
                ->with('images')
                ->orderByDesc('source_created_at')
                ->take(6)
                ->get();
        }

        return view('home', [
            'pageTitle' => 'Home',
            'heroProducts' => $heroProducts,
            'newMenProducts' => $newMenProducts,
            'menProducts' => $menProducts,
            'womenProducts' => $womenProducts,
            'accessoryProducts' => $accessoryProducts,
            'brands' => $brands,
            'khanabadoshProducts' => $khanabadoshProducts,
            'brandKey' => $brandKey,
            'brandProfile' => $this->resolveBrandProfile($brandKey),
        ]);
    }

    public function shop(Request $request)
    {
        $brandKey = $this->resolveBrandKey();
        $pageTitle = $request->query('title', 'Men Winter');
        $results = (int) $request->query('results', 18);
        $collectionSlug = Str::slug($pageTitle);
        $popularProducts = Product::query()
            ->where('brand_key', $brandKey)
            ->where('is_popular', true)
            ->with('images')
            ->take(3)
            ->get();

        $collection = Collection::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $collectionSlug)
            ->with(['products.images'])
            ->first();

        if ($collection && $collection->products->count() > 0) {
            $products = $collection->products;
            $results = $products->count();
        } else {
            $products = $this->fallbackProductsForSlug($collectionSlug, $brandKey);
            $results = $products->count();
        }

        if ($popularProducts->isEmpty()) {
            $popularProducts = Product::query()
                ->where('brand_key', $brandKey)
                ->with('images')
                ->orderByDesc('source_created_at')
                ->take(3)
                ->get();
        }

        $inStockCount = $products->filter(fn ($product) => $product->available ?? true)->count();
        $sidebarGroups = $this->resolveSidebarGroups($brandKey);
        $colorFilters = $this->resolveColorFilters($products);
        $colorMap = $this->mapProductColors($products);

        return view('shop', [
            'pageTitle' => $pageTitle,
            'results' => $results,
            'products' => $products,
            'collectionSlug' => $collectionSlug,
            'sidebarMen' => $sidebarGroups['men'],
            'sidebarWomen' => $sidebarGroups['women'],
            'inStockCount' => $inStockCount,
            'popularProducts' => $popularProducts,
            'colorFilters' => $colorFilters,
            'colorMap' => $colorMap,
            'brandKey' => $brandKey,
            'brandProfile' => $this->resolveBrandProfile($brandKey),
        ]);
    }

    public function collection(string $slug)
    {
        $brandKey = $this->resolveBrandKey();

        $collection = Collection::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $slug)
            ->with(['products.images'])
            ->first();
        if (!$collection) {
            $alias = $this->resolveSaleAlias($slug);
            if ($alias) {
                $collection = Collection::query()
                    ->where('brand_key', $brandKey)
                    ->where('handle', $alias)
                    ->with(['products.images'])
                    ->first();
            }
        }

        $pageTitle = $collection?->title ?? $this->resolveCollectionTitle($slug);
        if ($collection && $collection->products->count() > 0) {
            $products = $collection->products;
            $filtered = $this->filterProductsBySlug($products, $slug);
            if ($filtered->isNotEmpty()) {
                $products = $filtered;
            }
            $results = $products->count();
        } else {
            $products = $this->fallbackProductsForSlug($slug, $brandKey);
            $results = $products->count();
        }

        $popularProducts = Product::query()
            ->where('brand_key', $brandKey)
            ->where('is_popular', true)
            ->with('images')
            ->take(3)
            ->get();
        if ($popularProducts->isEmpty()) {
            $popularProducts = Product::query()
                ->where('brand_key', $brandKey)
                ->with('images')
                ->orderByDesc('source_created_at')
                ->take(3)
                ->get();
        }
        $colorFilters = $this->resolveColorFilters($products);
        $colorMap = $this->mapProductColors($products);

        return view('shop', [
            'pageTitle' => $pageTitle,
            'results' => $results,
            'products' => $products,
            'collectionSlug' => $slug,
            'sidebarMen' => $this->resolveSidebarGroups($brandKey)['men'],
            'sidebarWomen' => $this->resolveSidebarGroups($brandKey)['women'],
            'inStockCount' => $products->filter(fn ($product) => $product->available ?? true)->count(),
            'popularProducts' => $popularProducts,
            'colorFilters' => $colorFilters,
            'colorMap' => $colorMap,
            'brandKey' => $brandKey,
            'brandProfile' => $this->resolveBrandProfile($brandKey),
        ]);
    }

    public function brand(string $brand)
    {
        $brandKey = $this->resolveBrandKey($brand);
        $brandLabel = $this->resolveBrandLabel($brandKey);

        $products = Product::query()
            ->where('brand_key', $brandKey)
            ->with('images')
            ->orderByDesc('source_created_at')
            ->get();

        if ($products->isEmpty()) {
            $products = $this->fallbackProductsForSlug('all', $brandKey);
        }

        $popularProducts = Product::query()
            ->where('brand_key', $brandKey)
            ->where('is_popular', true)
            ->with('images')
            ->take(3)
            ->get();

        if ($popularProducts->isEmpty()) {
            $popularProducts = Product::query()
                ->where('brand_key', $brandKey)
                ->with('images')
                ->orderByDesc('source_created_at')
                ->take(3)
                ->get();
        }

        $sidebarGroups = $this->resolveSidebarGroups($brandKey);
        $colorFilters = $this->resolveColorFilters($products);
        $colorMap = $this->mapProductColors($products);

        return view('shop', [
            'pageTitle' => $brandLabel,
            'results' => $products->count(),
            'products' => $products,
            'collectionSlug' => 'all',
            'sidebarMen' => $sidebarGroups['men'],
            'sidebarWomen' => $sidebarGroups['women'],
            'inStockCount' => $products->filter(fn ($product) => $product->available ?? true)->count(),
            'popularProducts' => $popularProducts,
            'colorFilters' => $colorFilters,
            'colorMap' => $colorMap,
            'brandKey' => $brandKey,
            'brandProfile' => $this->resolveBrandProfile($brandKey),
        ]);
    }

    public function brandCollection(string $brand, string $slug)
    {
        $brandKey = $this->resolveBrandKey($brand);

        $collection = Collection::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $slug)
            ->with(['products.images'])
            ->first();

        if (!$collection) {
            $alias = $this->resolveSaleAlias($slug);
            if ($alias) {
                $collection = Collection::query()
                    ->where('brand_key', $brandKey)
                    ->where('handle', $alias)
                    ->with(['products.images'])
                    ->first();
            }
        }

        $pageTitle = $collection?->title ?? $this->resolveCollectionTitle($slug);
        if ($collection && $collection->products->count() > 0) {
            $products = $collection->products;
            $filtered = $this->filterProductsBySlug($products, $slug);
            if ($filtered->isNotEmpty()) {
                $products = $filtered;
            }
            $results = $products->count();
        } else {
            $products = $this->fallbackProductsForSlug($slug, $brandKey);
            $results = $products->count();
        }

        $popularProducts = Product::query()
            ->where('brand_key', $brandKey)
            ->where('is_popular', true)
            ->with('images')
            ->take(3)
            ->get();
        if ($popularProducts->isEmpty()) {
            $popularProducts = Product::query()
                ->where('brand_key', $brandKey)
                ->with('images')
                ->orderByDesc('source_created_at')
                ->take(3)
                ->get();
        }

        $colorFilters = $this->resolveColorFilters($products);
        $colorMap = $this->mapProductColors($products);
        $sidebarGroups = $this->resolveSidebarGroups($brandKey);

        return view('shop', [
            'pageTitle' => $pageTitle,
            'results' => $results,
            'products' => $products,
            'collectionSlug' => $slug,
            'sidebarMen' => $sidebarGroups['men'],
            'sidebarWomen' => $sidebarGroups['women'],
            'inStockCount' => $products->filter(fn ($product) => $product->available ?? true)->count(),
            'popularProducts' => $popularProducts,
            'colorFilters' => $colorFilters,
            'colorMap' => $colorMap,
            'brandKey' => $brandKey,
            'brandProfile' => $this->resolveBrandProfile($brandKey),
        ]);
    }

    public function brandProduct(string $brand, string $collection, string $slug)
    {
        $brandKey = $this->resolveBrandKey($brand);

        $collectionModel = Collection::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $collection)
            ->with(['products.images'])
            ->first();
        $productModel = Product::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $slug)
            ->with(['images', 'variants'])
            ->first();

        $collectionTitle = $collectionModel?->title ?? $this->resolveCollectionTitle($collection);
        $product = $productModel
            ?? $this->fallbackProductBySlug($brandKey, $slug)
            ?? $this->resolveProduct($slug);
        $colorSwatches = [];
        if ($collectionModel && $collectionModel->products->isNotEmpty()) {
            $palette = $this->colorPalette();
            $patterns = $this->colorPatterns();
            $swatchMap = [];

            foreach ($collectionModel->products as $item) {
                $keys = $this->extractColorKeysFromText($this->productColorText($item), $patterns);
                $key = $keys[0] ?? null;
                if (!$key || !isset($palette[$key])) {
                    continue;
                }
                if (!isset($swatchMap[$key])) {
                    $swatchMap[$key] = [
                        'key' => $key,
                        'label' => Str::of($key)->replace('-', ' ')->title()->value(),
                        'value' => $palette[$key],
                        'url' => route('brands.products.show', [
                            'brand' => $brandKey,
                            'collection' => $collection,
                            'slug' => $item->handle,
                        ]),
                        'isActive' => $item->handle === $slug,
                    ];
                } elseif ($item->handle === $slug) {
                    $swatchMap[$key]['isActive'] = true;
                }
            }

            $colorSwatches = array_values($swatchMap);
        }

        return view('product', [
            'pageTitle' => $productModel?->title ?? $product['name'],
            'collectionSlug' => $collection,
            'collectionTitle' => $collectionTitle,
            'product' => $product,
            'colorSwatches' => $colorSwatches,
            'productSlug' => $slug,
            'brandKey' => $brandKey,
        ]);
    }

    public function product(string $collection, string $slug)
    {
        $brandKey = $this->resolveBrandKey();

        $collectionModel = Collection::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $collection)
            ->with(['products.images'])
            ->first();
        $productModel = Product::query()
            ->where('brand_key', $brandKey)
            ->where('handle', $slug)
            ->with(['images', 'variants'])
            ->first();

        $collectionTitle = $collectionModel?->title ?? $this->resolveCollectionTitle($collection);
        $product = $productModel
            ?? $this->fallbackProductBySlug($brandKey, $slug)
            ?? $this->resolveProduct($slug);
        $colorSwatches = [];
        if ($collectionModel && $collectionModel->products->isNotEmpty()) {
            $palette = $this->colorPalette();
            $patterns = $this->colorPatterns();
            $swatchMap = [];

            foreach ($collectionModel->products as $item) {
                $keys = $this->extractColorKeysFromText($this->productColorText($item), $patterns);
                $key = $keys[0] ?? null;
                if (!$key || !isset($palette[$key])) {
                    continue;
                }
                if (!isset($swatchMap[$key])) {
                    $swatchMap[$key] = [
                        'key' => $key,
                        'label' => Str::of($key)->replace('-', ' ')->title()->value(),
                        'value' => $palette[$key],
                        'url' => route('products.show', ['collection' => $collection, 'slug' => $item->handle]),
                        'isActive' => $item->handle === $slug,
                    ];
                } elseif ($item->handle === $slug) {
                    $swatchMap[$key]['isActive'] = true;
                }
            }

            $colorSwatches = array_values($swatchMap);
        }

        return view('product', [
            'pageTitle' => $productModel?->title ?? $product['name'],
            'collectionSlug' => $collection,
            'collectionTitle' => $collectionTitle,
            'product' => $product,
            'colorSwatches' => $colorSwatches,
            'productSlug' => $slug,
            'brandKey' => $brandKey,
        ]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $results = collect();

        if ($query !== '') {
            $results = Product::query()
                ->with('images')
                ->where(function ($builder) use ($query) {
                    $builder->where('title', 'like', '%' . $query . '%')
                        ->orWhere('handle', 'like', '%' . $query . '%')
                        ->orWhere('tags', 'like', '%' . $query . '%')
                        ->orWhere('product_type', 'like', '%' . $query . '%');
                })
                ->orderBy('title')
                ->get();
        }

        return view('search', [
            'pageTitle' => 'Search',
            'query' => $query,
            'results' => $results,
        ]);
    }

    public function wishlist()
    {
        return view('wishlist', [
            'pageTitle' => 'Wishlist',
        ]);
    }

    public function cart()
    {
        return view('cart', [
            'pageTitle' => 'Cart',
        ]);
    }

    public function checkout()
    {
        $storeName = (string) (config('catalog.store.name') ?? 'Toronto Textile');

        return view('checkout', [
            'pageTitle' => 'Checkout',
            'bankName' => \App\Models\Setting::getValue('bank_name', $storeName . ' Bank'),
            'bankTitle' => \App\Models\Setting::getValue('bank_account_title', $storeName),
            'bankAccount' => \App\Models\Setting::getValue('bank_account_number', '0001-2233-4455'),
            'bankIban' => \App\Models\Setting::getValue('bank_iban', 'PK00KB0000000000000001'),
            'bankNote' => \App\Models\Setting::getValue('bank_note', 'Send payment to the bank account and upload the transfer screenshot below.'),
        ]);
    }

    public function trackOrder(Request $request)
    {
        $orderNumber = strtoupper(trim((string) $request->query('order_number', '')));
        $email = strtolower(trim((string) $request->query('email', '')));

        if ($orderNumber === '' || $email === '') {
            return view('track-order-gate', [
                'pageTitle' => 'Track Order',
                'messageTitle' => 'Order link required',
                'messageBody' => 'This page is only available from your confirmation email.',
                'helpBody' => 'If you placed an order and did not receive the email, contact support and we will resend it.',
            ]);
        }

        $order = Order::query()
            ->with('items')
            ->whereRaw('upper(order_number) = ?', [$orderNumber])
            ->whereRaw('lower(email) = ?', [$email])
            ->first();

        if (!$order) {
            return view('track-order-gate', [
                'pageTitle' => 'Track Order',
                'messageTitle' => 'We could not find that order',
                'messageBody' => 'Please make sure you opened the tracking link from your order email.',
                'helpBody' => 'If you think this is a mistake, contact support with your order number and email.',
            ]);
        }

        return view('track-order', [
            'pageTitle' => 'Track Order',
            'order' => $order,
        ]);
    }

    public function productsApi(Request $request): JsonResponse
    {
        $ids = collect(explode(',', (string) $request->query('ids', '')))
            ->map(fn ($id) => trim($id))
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            $handles = collect(explode(',', (string) $request->query('handles', '')))
                ->map(fn ($handle) => trim($handle))
                ->filter()
                ->values();
            $defaultBrand = $this->resolveBrandKey();
            $ids = $handles->map(fn ($handle) => $defaultBrand . '::' . $handle);
        }

        if ($ids->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $defaultBrand = $this->resolveBrandKey();
        $requested = $ids->map(function ($id) use ($defaultBrand) {
            $parts = explode('::', $id, 2);
            $brand = trim($parts[0] ?? '');
            $handle = trim($parts[1] ?? '');
            if ($handle === '') {
                $handle = $brand;
                $brand = $defaultBrand;
            }

            $brand = $brand !== '' ? $brand : $defaultBrand;

            return [
                'id' => $brand . '::' . $handle,
                'brand' => $brand,
                'handle' => $handle,
            ];
        })->filter(fn ($item) => $item['handle'] !== '');

        if ($requested->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $products = Product::query()
            ->with('images')
            ->where(function ($query) use ($requested) {
                foreach ($requested as $item) {
                    $query->orWhere(function ($subQuery) use ($item) {
                        $subQuery->where('brand_key', $item['brand'])
                            ->where('handle', $item['handle']);
                    });
                }
            })
            ->get();

        $found = $products->mapWithKeys(function ($product) {
            return [$product->brand_key . '::' . $product->handle => true];
        });

        $items = $products->map(function ($product) use ($defaultBrand) {
            $image = optional($product->images->sortBy('position')->first())->src;
            $priceValue = $product->effectivePrice();
            $converted = CurrencyFormatter::convert($priceValue);
            $brandKey = $product->brand_key ?: $defaultBrand;
            $collectionSlug = $brandKey === $defaultBrand ? 'men-all' : 'all';
            $url = $brandKey === $defaultBrand
                ? route('products.show', ['collection' => $collectionSlug, 'slug' => $product->handle])
                : route('brands.products.show', [
                    'brand' => $brandKey,
                    'collection' => $collectionSlug,
                    'slug' => $product->handle,
                ]);

            return [
                'id' => $brandKey . '::' . $product->handle,
                'handle' => $product->handle,
                'brand' => $brandKey,
                'title' => $product->title,
                'price_value' => $converted ?? 0,
                'price_label' => CurrencyFormatter::format($priceValue),
                'image' => $image,
                'url' => $url,
                'available' => (bool) $product->available,
            ];
        });

        $missing = $requested->filter(function ($item) use ($found) {
            $key = $item['brand'] . '::' . $item['handle'];
            return empty($found[$key]);
        });

        if ($missing->isNotEmpty()) {
            $fallbackItems = [];
            foreach ($missing as $item) {
                $fallbackProduct = $this->fallbackProductBySlug($item['brand'], $item['handle']);
                if (!$fallbackProduct) {
                    continue;
                }
                $fallbackItems[] = $this->formatFallbackApiProduct($fallbackProduct, $item['brand'], $defaultBrand);
            }
            if (!empty($fallbackItems)) {
                $items = $items->concat(collect($fallbackItems));
            }
        }

        return response()->json([
            'items' => $items->values(),
            'currency' => CurrencyFormatter::currency(),
            'symbol' => CurrencyFormatter::symbol(),
        ]);
    }

    public function policy(Request $request)
    {
        $pageTitle = $request->query('title', 'Policies');
        $storeName = (string) (config('catalog.store.name') ?? 'Toronto Textile');
        $storeLabel = $storeName . ' Canada';

        $policyMap = [
            'Policies' => [
                'intro' => $storeLabel . ' shares details about shipping, returns, privacy, and terms. Please choose a policy from the menu to read full information.',
                'sections' => [
                    [
                        'title' => 'Quick Links',
                        'items' => [
                            'Shipping Policy',
                            'Exchange & Return Policy',
                            'FAQs',
                            'Terms & Conditions',
                            'Privacy Policy',
                        ],
                    ],
                ],
            ],
            'Shipping Policy' => [
                'intro' => $storeLabel . ' processes and ships orders with care. Shipping rates and delivery timelines are shown at checkout.',
                'sections' => [
                    [
                        'title' => 'Order Processing',
                        'items' => [
                            'Processing time: 1-2 business days after order confirmation.',
                            'Orders placed on weekends or holidays process the next business day.',
                        ],
                    ],
                    [
                        'title' => 'Delivery',
                        'items' => [
                            'Estimated delivery in Canada: 3-7 business days after dispatch.',
                            'Tracking details are emailed when your order ships.',
                        ],
                    ],
                    [
                        'title' => 'Important Notes',
                        'items' => [
                            'Please ensure your shipping address is accurate.',
                            'Undelivered orders may be returned to sender and re-shipping fees may apply.',
                        ],
                    ],
                ],
            ],
            'Exchange & Return Policy' => [
                'intro' => $storeLabel . ' accepts returns and exchanges with a simple process.',
                'sections' => [
                    [
                        'title' => 'Return Window',
                        'items' => [
                            'Returns accepted within 10 days of delivery.',
                            'Items must be unused, unwashed, and in original packaging.',
                        ],
                    ],
                    [
                        'title' => 'Exchanges',
                        'items' => [
                            'Exchanges depend on stock availability.',
                            'Sale items may be final sale unless otherwise stated.',
                        ],
                    ],
                    [
                        'title' => 'Refunds',
                        'items' => [
                            'Refunds are issued to the original payment method after inspection.',
                            'Please allow 5-7 business days for processing.',
                        ],
                    ],
                ],
            ],
            'FAQs' => [
                'intro' => 'Find quick answers from ' . $storeLabel . ' below.',
                'sections' => [
                    [
                        'title' => 'Orders',
                        'items' => [
                            'How do I place an order? Choose your product and complete checkout.',
                            'Can I change my order? Contact support as soon as possible after checkout.',
                        ],
                    ],
                    [
                        'title' => 'Shipping',
                        'items' => [
                            'How do I track my order? Use the tracking link sent to your email.',
                            'Do you ship across Canada? Yes, shipping is available nationwide.',
                        ],
                    ],
                    [
                        'title' => 'Products',
                        'items' => [
                            'Are colors exact? Colors may vary slightly due to screen settings.',
                            'Need help with size? Contact support for assistance.',
                        ],
                    ],
                ],
            ],
            'Terms & Conditions' => [
                'intro' => 'By using the ' . $storeLabel . ' website, you agree to the following terms.',
                'sections' => [
                    [
                        'title' => 'Use of Site',
                        'items' => [
                            'All content is provided for personal, non-commercial use.',
                            'Prices and availability may change without notice.',
                        ],
                    ],
                    [
                        'title' => 'Product Information',
                        'items' => [
                            'We aim for accurate product details and imagery.',
                            'Color variation may occur due to device settings.',
                        ],
                    ],
                    [
                        'title' => 'Liability',
                        'items' => [
                            $storeLabel . ' is not liable for indirect damages.',
                            'Our maximum liability is limited to the purchase value.',
                        ],
                    ],
                ],
            ],
            'Privacy Policy' => [
                'intro' => $storeLabel . ' respects your privacy and keeps your data secure.',
                'sections' => [
                    [
                        'title' => 'Information We Collect',
                        'items' => [
                            'Name, contact details, and shipping address.',
                            'Order details and payment confirmation.',
                        ],
                    ],
                    [
                        'title' => 'How We Use It',
                        'items' => [
                            'To process orders and provide customer support.',
                            'To share updates and offers if you opt in.',
                        ],
                    ],
                    [
                        'title' => 'Data Sharing',
                        'items' => [
                            'We do not sell your data.',
                            'We share only with trusted service providers to fulfill orders.',
                        ],
                    ],
                ],
            ],
        ];

        $policy = $policyMap[$pageTitle] ?? $policyMap['Policies'];

        return view('policy', [
            'pageTitle' => $pageTitle,
            'intro' => $policy['intro'],
            'sections' => $policy['sections'],
        ]);
    }

    public function lookbook()
    {
        return view('lookbook', [
            'pageTitle' => 'Lookbook',
        ]);
    }

    private function buildProducts(int $results): array
    {
        $baseProducts = [
            ['name' => 'Arctic Parka', 'slug' => 'arctic-parka', 'price' => 249],
            ['name' => 'Maple Knit Beanie', 'slug' => 'maple-knit-beanie', 'price' => 29],
            ['name' => 'Northern Trail Hoodie', 'slug' => 'northern-trail-hoodie', 'price' => 69],
            ['name' => 'Lakeside Denim Jacket', 'slug' => 'lakeside-denim-jacket', 'price' => 89],
            ['name' => 'Evergreen Flannel', 'slug' => 'evergreen-flannel', 'price' => 59],
            ['name' => 'Riverstone Boots', 'slug' => 'riverstone-boots', 'price' => 129],
            ['name' => 'Aurora Puffer Vest', 'slug' => 'aurora-puffer-vest', 'price' => 119],
            ['name' => 'Alpine Wool Scarf', 'slug' => 'alpine-wool-scarf', 'price' => 35],
            ['name' => 'City Runner Sneakers', 'slug' => 'city-runner-sneakers', 'price' => 109],
            ['name' => 'Heritage Leather Belt', 'slug' => 'heritage-leather-belt', 'price' => 39],
            ['name' => 'Polar Sunglasses', 'slug' => 'polar-sunglasses', 'price' => 49],
            ['name' => 'Compass Watch', 'slug' => 'compass-watch', 'price' => 149],
            ['name' => 'Daily Tote Bag', 'slug' => 'daily-tote-bag', 'price' => 54],
            ['name' => 'Baby Fleece Onesie', 'slug' => 'baby-fleece-onesie', 'price' => 34],
            ['name' => 'Kids Adventure Jacket', 'slug' => 'kids-adventure-jacket', 'price' => 79],
            ['name' => 'Trail Cap', 'slug' => 'trail-cap', 'price' => 24],
            ['name' => 'Harbor Rain Shell', 'slug' => 'harbor-rain-shell', 'price' => 139],
            ['name' => 'Studio Leggings', 'slug' => 'studio-leggings', 'price' => 59],
        ];

        $products = [];
        if ($results > 0) {
            $count = count($baseProducts);
            for ($i = 0; $i < $results; $i++) {
                $base = $baseProducts[$i % $count];
                $slug = $base['slug'] ?? Str::slug($base['name'] ?? 'product');
                $seed = 'fallback-' . $slug . '-' . ($i + 1);
                $gallery = $this->placeholderGallery($seed, 2);

                $products[] = array_merge($base, [
                    'slug' => $slug,
                    'sku' => 'FB-' . strtoupper(Str::of($slug)->replace('-', '')->limit(6, '')->value()),
                    'description' => $base['description'] ?? 'Classic winter fabric designed for comfort and durability.',
                    'details' => $base['details'] ?? 'Unstitched • 4.0m fabric • Season: Winter • Care: Dry clean recommended.',
                    'gallery' => $gallery,
                    'image' => $gallery[0]['src'] ?? null,
                    'alt_image' => $gallery[1]['src'] ?? null,
                    'created_at' => $base['created_at'] ?? '2026-01-01',
                ]);
            }
        }

        return $products;
    }

    private function placeholderImage(string $seed, int $width = 900, int $height = 1200): string
    {
        return "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
    }

    private function placeholderGallery(string $seed, int $count = 2): array
    {
        $gallery = [];
        for ($i = 1; $i <= $count; $i++) {
            $gallery[] = ['src' => $this->placeholderImage($seed . '-' . $i)];
        }

        return $gallery;
    }

    private function fallbackCatalogs(): array
    {
        static $catalogs = null;
        if ($catalogs !== null) {
            return $catalogs;
        }

        $catalogs = [
            'khanabadosh' => $this->hydrateFallbackProducts([
                [
                    'name' => 'Desert Active Set',
                    'slug' => 'desert-active-set',
                    'price' => 3890,
                    'sku' => 'KB-DA-01',
                    'product_type' => 'Activewear',
                    'tags' => 'Men, Activewear, New',
                    'description' => 'Breathable performance set with soft stretch and clean seams.',
                    'details' => 'Performance blend • Two-piece • Easy care.',
                    'created_at' => '2026-01-16',
                ],
                [
                    'name' => 'Heritage Track Jacket',
                    'slug' => 'heritage-track-jacket',
                    'price' => 4590,
                    'compare_at_price' => 5290,
                    'sku' => 'KB-HTJ-02',
                    'product_type' => 'Outerwear',
                    'tags' => 'Men, Outerwear, Sale',
                    'description' => 'Structured track jacket with warm lining and tonal zipper.',
                    'details' => 'Brushed lining • Regular fit.',
                    'created_at' => '2026-01-14',
                ],
                [
                    'name' => 'Sahara Cap',
                    'slug' => 'sahara-cap',
                    'price' => 990,
                    'sku' => 'KB-SC-03',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Caps',
                    'description' => 'Soft crown cap with embroidered logo.',
                    'details' => 'Cotton twill • Adjustable strap.',
                    'created_at' => '2026-01-12',
                ],
                [
                    'name' => 'Khanabadosh Scarf',
                    'slug' => 'khanabadosh-scarf',
                    'price' => 1390,
                    'sku' => 'KB-KS-04',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Scarves',
                    'description' => 'Warm woven scarf with soft hand feel.',
                    'details' => 'Wool blend • 180 x 70 cm.',
                    'created_at' => '2026-01-10',
                ],
                [
                    'name' => 'Nomad Tote',
                    'slug' => 'nomad-tote',
                    'price' => 2190,
                    'sku' => 'KB-NT-05',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags',
                    'description' => 'Structured tote with stitched handles and inner pocket.',
                    'details' => 'Canvas • Magnetic closure.',
                    'created_at' => '2026-01-08',
                ],
                [
                    'name' => 'Dawn Belt',
                    'slug' => 'dawn-belt',
                    'price' => 1190,
                    'sku' => 'KB-DB-06',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Belts',
                    'description' => 'Clean leather belt with matte buckle.',
                    'details' => 'Leather • Adjustable.',
                    'created_at' => '2026-01-06',
                ],
            ], 'khanabadosh'),
            'demo-brand' => $this->hydrateFallbackProducts([
                [
                    'name' => 'Cloudline Hoodie',
                    'slug' => 'cloudline-hoodie',
                    'price' => 3290,
                    'compare_at_price' => 3890,
                    'sku' => 'SV-CLH-01',
                    'product_type' => 'Activewear',
                    'tags' => 'Men, Activewear, New',
                    'description' => 'Brushed fleece hoodie with a relaxed drape and soft hand feel.',
                    'details' => 'Fleece • Relaxed fit • Rib cuffs • Care: Machine wash cold.',
                    'created_at' => '2026-01-18',
                ],
                [
                    'name' => 'Metro Knit Set',
                    'slug' => 'metro-knit-set',
                    'price' => 4490,
                    'sku' => 'SV-MKS-02',
                    'product_type' => 'Women',
                    'tags' => 'Women, Knitwear, New',
                    'description' => 'Fine-gauge knit co-ord with a smooth, studio-ready finish.',
                    'details' => 'Viscose blend • Slim fit • Two-piece set.',
                    'created_at' => '2026-01-16',
                ],
                [
                    'name' => 'Signal Field Jacket',
                    'slug' => 'signal-field-jacket',
                    'price' => 5890,
                    'compare_at_price' => 6990,
                    'sku' => 'SV-SFJ-03',
                    'product_type' => 'Outerwear',
                    'tags' => 'Men, Outerwear, Sale',
                    'description' => 'Structured field jacket with wind-block lining and matte hardware.',
                    'details' => 'Water resistant • Utility pockets • Care: Spot clean.',
                    'created_at' => '2026-01-12',
                ],
                [
                    'name' => 'Drift Cargo',
                    'slug' => 'drift-cargo',
                    'price' => 3790,
                    'sku' => 'SV-DC-04',
                    'product_type' => 'Men',
                    'tags' => 'Men, Casual',
                    'description' => 'Lightweight cargo pant with clean seams and tailored taper.',
                    'details' => 'Cotton twill • Tapered leg • Mid-rise.',
                    'created_at' => '2026-01-10',
                ],
                [
                    'name' => 'Canvas Weekender',
                    'slug' => 'canvas-weekender',
                    'price' => 2690,
                    'sku' => 'SV-CW-05',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags, New',
                    'description' => 'Structured canvas duffle built for studio-to-street travel.',
                    'details' => 'Canvas • Zip top • Interior pocket.',
                    'created_at' => '2026-01-14',
                ],
                [
                    'name' => 'Pulse Sneakers',
                    'slug' => 'pulse-sneakers',
                    'price' => 4990,
                    'sku' => 'SV-PS-06',
                    'product_type' => 'Footwear',
                    'tags' => 'Men, Women, Footwear',
                    'description' => 'Chunky knit sneaker with cushioned midsole and matte laces.',
                    'details' => 'Knit upper • EVA sole • True to size.',
                    'created_at' => '2026-01-08',
                ],
                [
                    'name' => 'Studio Cap',
                    'slug' => 'studio-cap',
                    'price' => 1190,
                    'sku' => 'SV-SC-07',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Caps, New',
                    'description' => 'Minimal cap with tonal stitching and clean crown.',
                    'details' => 'Cotton twill • Adjustable strap.',
                    'created_at' => '2026-01-05',
                ],
                [
                    'name' => 'Monochrome Watch',
                    'slug' => 'monochrome-watch',
                    'price' => 2890,
                    'sku' => 'SV-MW-08',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Watches',
                    'description' => 'Matte dial watch with a brushed metal strap.',
                    'details' => 'Quartz • 40mm case.',
                    'created_at' => '2026-01-04',
                ],
                [
                    'name' => 'City Crossbody',
                    'slug' => 'city-crossbody',
                    'price' => 2190,
                    'sku' => 'SV-CC-09',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags',
                    'description' => 'Compact crossbody for everyday carry with zip top.',
                    'details' => 'Nylon • Adjustable strap.',
                    'created_at' => '2026-01-03',
                ],
            ], 'demo-brand'),
            'northline' => $this->hydrateFallbackProducts([
                [
                    'name' => 'Summit Parka',
                    'slug' => 'summit-parka',
                    'price' => 7990,
                    'compare_at_price' => 8990,
                    'sku' => 'NL-SP-01',
                    'product_type' => 'Outerwear',
                    'tags' => 'Men, Outerwear, Winter, Sale',
                    'description' => 'Down-filled parka built for deep cold and icy winds.',
                    'details' => 'Insulated • Storm hood • Care: Dry clean.',
                    'created_at' => '2026-01-20',
                ],
                [
                    'name' => 'Glacier Fleece',
                    'slug' => 'glacier-fleece',
                    'price' => 2890,
                    'sku' => 'NL-GF-02',
                    'product_type' => 'Men',
                    'tags' => 'Men, Winter',
                    'description' => 'Midweight fleece with clean piping and thermal lining.',
                    'details' => 'Fleece • Regular fit • Zip neck.',
                    'created_at' => '2026-01-17',
                ],
                [
                    'name' => 'Ridge Shell Jacket',
                    'slug' => 'ridge-shell-jacket',
                    'price' => 5590,
                    'sku' => 'NL-RSJ-03',
                    'product_type' => 'Outerwear',
                    'tags' => 'Women, Outerwear',
                    'description' => 'Seam-sealed shell for wet days with breathable lining.',
                    'details' => 'Waterproof • Vent panels • Packable hood.',
                    'created_at' => '2026-01-15',
                ],
                [
                    'name' => 'Tundra Boots',
                    'slug' => 'tundra-boots',
                    'price' => 6390,
                    'sku' => 'NL-TB-04',
                    'product_type' => 'Footwear',
                    'tags' => 'Men, Women, Footwear',
                    'description' => 'Insulated boots with grippy sole for snow and slush.',
                    'details' => 'Thermal lining • Rubber sole • True to size.',
                    'created_at' => '2026-01-11',
                ],
                [
                    'name' => 'Everpine Beanie',
                    'slug' => 'everpine-beanie',
                    'price' => 1390,
                    'sku' => 'NL-EB-05',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Winter',
                    'description' => 'Ribbed knit beanie with a soft, warm finish.',
                    'details' => 'Wool blend • One size.',
                    'created_at' => '2026-01-09',
                ],
                [
                    'name' => 'Trail Harness Bag',
                    'slug' => 'trail-harness-bag',
                    'price' => 2490,
                    'sku' => 'NL-THB-06',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags',
                    'description' => 'Compact crossbody bag with utility straps and matte buckles.',
                    'details' => 'Nylon • Adjustable strap • Interior zip.',
                    'created_at' => '2026-01-07',
                ],
                [
                    'name' => 'Polar Sunglasses',
                    'slug' => 'polar-sunglasses',
                    'price' => 1590,
                    'sku' => 'NL-PS-07',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Glasses',
                    'description' => 'UV-protective frames with cold-weather lenses.',
                    'details' => 'UV400 • Matte frame.',
                    'created_at' => '2026-01-06',
                ],
                [
                    'name' => 'Summit Gloves',
                    'slug' => 'summit-gloves',
                    'price' => 1290,
                    'sku' => 'NL-SG-08',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Winter',
                    'description' => 'Insulated gloves with grip-textured palms.',
                    'details' => 'Thermal lining • Touchscreen tips.',
                    'created_at' => '2026-01-05',
                ],
                [
                    'name' => 'Base Camp Cap',
                    'slug' => 'base-camp-cap',
                    'price' => 990,
                    'sku' => 'NL-BCC-09',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Caps',
                    'description' => 'Lightweight cap with a weather-ready finish.',
                    'details' => 'Ripstop • Adjustable strap.',
                    'created_at' => '2026-01-02',
                ],
                [
                    'name' => 'Alpine Training Set',
                    'slug' => 'alpine-training-set',
                    'price' => 3490,
                    'sku' => 'NL-ATS-10',
                    'product_type' => 'Activewear',
                    'tags' => 'Men, Activewear',
                    'description' => 'Lightweight training set built for cold-weather runs.',
                    'details' => 'Thermal knit • Two-piece.',
                    'created_at' => '2026-01-01',
                ],
            ], 'northline'),
            'harbour-loom' => $this->hydrateFallbackProducts([
                [
                    'name' => 'Coast Linen Shirt',
                    'slug' => 'coast-linen-shirt',
                    'price' => 3190,
                    'sku' => 'HL-CLS-01',
                    'product_type' => 'Men',
                    'tags' => 'Men, Linen, New',
                    'description' => 'Breathable linen shirt with a relaxed coastal silhouette.',
                    'details' => '100% linen • Relaxed fit • Button front.',
                    'created_at' => '2026-01-19',
                ],
                [
                    'name' => 'Mariner Chino',
                    'slug' => 'mariner-chino',
                    'price' => 3590,
                    'sku' => 'HL-MC-02',
                    'product_type' => 'Men',
                    'tags' => 'Men, Casual',
                    'description' => 'Clean chino with soft stretch and tapered ankle.',
                    'details' => 'Cotton stretch • Tapered leg.',
                    'created_at' => '2026-01-13',
                ],
                [
                    'name' => 'Harbor Knit Cardigan',
                    'slug' => 'harbor-knit-cardigan',
                    'price' => 4390,
                    'sku' => 'HL-HKC-03',
                    'product_type' => 'Women',
                    'tags' => 'Women, Knitwear',
                    'description' => 'Soft knit cardigan with matte buttons and deep pockets.',
                    'details' => 'Acrylic blend • Midweight.',
                    'created_at' => '2026-01-12',
                ],
                [
                    'name' => 'Bay Shawl',
                    'slug' => 'bay-shawl',
                    'price' => 1890,
                    'sku' => 'HL-BS-04',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Scarves',
                    'description' => 'Lightweight shawl in muted coastal tones.',
                    'details' => 'Woven blend • 180 x 70 cm.',
                    'created_at' => '2026-01-10',
                ],
                [
                    'name' => 'Saltair Tote',
                    'slug' => 'saltair-tote',
                    'price' => 2290,
                    'sku' => 'HL-ST-05',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags, New',
                    'description' => 'Structured tote with woven texture and contrast handles.',
                    'details' => 'Canvas • Interior pocket • Magnetic snap.',
                    'created_at' => '2026-01-08',
                ],
                [
                    'name' => 'Drift Sundress',
                    'slug' => 'drift-sundress',
                    'price' => 3990,
                    'sku' => 'HL-DS-06',
                    'product_type' => 'Women',
                    'tags' => 'Women, Linen',
                    'description' => 'Flowing sundress with airy linen and a soft waist tie.',
                    'details' => 'Linen blend • Midi length.',
                    'created_at' => '2026-01-06',
                ],
                [
                    'name' => 'Reef Straw Hat',
                    'slug' => 'reef-straw-hat',
                    'price' => 1490,
                    'sku' => 'HL-RSH-07',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Caps',
                    'description' => 'Breezy straw hat with a soft woven band.',
                    'details' => 'Straw weave • One size.',
                    'created_at' => '2026-01-05',
                ],
                [
                    'name' => 'Lagoon Sunglasses',
                    'slug' => 'lagoon-sunglasses',
                    'price' => 1690,
                    'sku' => 'HL-LS-08',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Glasses',
                    'description' => 'Tortoise-shell frames with ocean tint lenses.',
                    'details' => 'UV400 • Lightweight.',
                    'created_at' => '2026-01-04',
                ],
                [
                    'name' => 'Cove Belt',
                    'slug' => 'cove-belt',
                    'price' => 1190,
                    'sku' => 'HL-CB-09',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Belts',
                    'description' => 'Soft leather belt with brushed buckle.',
                    'details' => 'Leather • Adjustable.',
                    'created_at' => '2026-01-03',
                ],
                [
                    'name' => 'Harbor Flow Set',
                    'slug' => 'harbor-flow-set',
                    'price' => 3190,
                    'sku' => 'HL-HFS-10',
                    'product_type' => 'Activewear',
                    'tags' => 'Women, Activewear',
                    'description' => 'Flowy lounge set with breathable knit texture.',
                    'details' => 'Modal blend • Two-piece.',
                    'created_at' => '2026-01-02',
                ],
            ], 'harbour-loom'),
            'sable-atelier' => $this->hydrateFallbackProducts([
                [
                    'name' => 'Dune Wrap Blazer',
                    'slug' => 'dune-wrap-blazer',
                    'price' => 6690,
                    'sku' => 'SA-DWB-01',
                    'product_type' => 'Women',
                    'tags' => 'Women, Outerwear, New',
                    'description' => 'Soft-structure blazer with a wrap waist and matte belt.',
                    'details' => 'Wool blend • Adjustable tie • Lined.',
                    'created_at' => '2026-01-20',
                ],
                [
                    'name' => 'Mirage Linen Suit',
                    'slug' => 'mirage-linen-suit',
                    'price' => 7990,
                    'sku' => 'SA-MLS-02',
                    'product_type' => 'Men',
                    'tags' => 'Men, Linen',
                    'description' => 'Lightweight suit in airy linen with a clean drape.',
                    'details' => 'Linen • Two-piece • Tailored fit.',
                    'created_at' => '2026-01-18',
                ],
                [
                    'name' => 'Saffron Slip Dress',
                    'slug' => 'saffron-slip-dress',
                    'price' => 4590,
                    'sku' => 'SA-SSD-03',
                    'product_type' => 'Women',
                    'tags' => 'Women, New',
                    'description' => 'Silky slip dress with a soft sheen and minimalist cut.',
                    'details' => 'Satin blend • Adjustable straps.',
                    'created_at' => '2026-01-14',
                ],
                [
                    'name' => 'Oasis Lounge Set',
                    'slug' => 'oasis-lounge-set',
                    'price' => 3490,
                    'sku' => 'SA-OLS-04',
                    'product_type' => 'Women',
                    'tags' => 'Women, Activewear',
                    'description' => 'Breathable lounge set with a fluid, draped silhouette.',
                    'details' => 'Modal blend • Two-piece.',
                    'created_at' => '2026-01-12',
                ],
                [
                    'name' => 'Amber Crossbody',
                    'slug' => 'amber-crossbody',
                    'price' => 2790,
                    'sku' => 'SA-AC-05',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags',
                    'description' => 'Minimal crossbody with a structured silhouette and warm tone.',
                    'details' => 'Vegan leather • Magnetic flap.',
                    'created_at' => '2026-01-09',
                ],
                [
                    'name' => 'Dune Silk Scarf',
                    'slug' => 'dune-silk-scarf',
                    'price' => 1890,
                    'sku' => 'SA-DSS-06',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Scarves, New',
                    'description' => 'Silk scarf with soft gradient tones and hand-rolled edges.',
                    'details' => '100% silk • 90 x 90 cm.',
                    'created_at' => '2026-01-07',
                ],
                [
                    'name' => 'Atelier Clutch',
                    'slug' => 'atelier-clutch',
                    'price' => 2590,
                    'sku' => 'SA-AC-07',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Bags',
                    'description' => 'Structured clutch with matte hardware and clean edges.',
                    'details' => 'Vegan leather • Magnetic closure.',
                    'created_at' => '2026-01-06',
                ],
                [
                    'name' => 'Solstice Belt',
                    'slug' => 'solstice-belt',
                    'price' => 1390,
                    'sku' => 'SA-SB-08',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Belts',
                    'description' => 'Minimal belt with soft-grain finish.',
                    'details' => 'Leatherette • Adjustable.',
                    'created_at' => '2026-01-05',
                ],
                [
                    'name' => 'Marble Watch',
                    'slug' => 'marble-watch',
                    'price' => 3190,
                    'sku' => 'SA-MW-09',
                    'product_type' => 'Accessories',
                    'tags' => 'Accessories, Watches',
                    'description' => 'Clean dial watch with marble-inspired face.',
                    'details' => 'Quartz • 38mm case.',
                    'created_at' => '2026-01-04',
                ],
            ], 'sable-atelier'),
        ];

        return $catalogs;
    }

    private function hydrateFallbackProducts(array $products, string $brandKey): array
    {
        foreach ($products as $index => $product) {
            $slug = $product['slug'] ?? Str::slug($product['name'] ?? 'product');
            $seed = $brandKey . '-' . $slug;
            $gallery = $product['gallery'] ?? $this->placeholderGallery($seed, 2);

            $products[$index] = array_merge($product, [
                'slug' => $slug,
                'price' => isset($product['price']) ? (float) $product['price'] : 0,
                'compare_at_price' => isset($product['compare_at_price']) ? (float) $product['compare_at_price'] : null,
                'gallery' => $gallery,
                'image' => $product['image'] ?? ($gallery[0]['src'] ?? null),
                'alt_image' => $product['alt_image'] ?? ($gallery[1]['src'] ?? null),
                'created_at' => $product['created_at'] ?? '2026-01-01',
                'tags' => $product['tags'] ?? '',
                'brand' => $brandKey,
            ]);
        }

        return $products;
    }

    private function fallbackProductsForBrand(?string $brandKey): array
    {
        if (!$brandKey) {
            return [];
        }

        $catalogs = $this->fallbackCatalogs();

        return $catalogs[$brandKey] ?? [];
    }

    private function fallbackProductBySlug(string $brandKey, string $slug): ?array
    {
        $products = $this->fallbackProductsForBrand($brandKey);
        if (empty($products)) {
            return null;
        }

        foreach ($products as $product) {
            $productSlug = $product['slug'] ?? Str::slug($product['name'] ?? '');
            if ($productSlug === $slug) {
                return $product;
            }
        }

        return null;
    }

    private function filterFallbackProductsBySlug(array $products, string $slug): array
    {
        if (empty($products)) {
            return [];
        }

        $slugLower = strtolower($slug);
        if (Str::contains($slugLower, 'sale')) {
            $saleProducts = array_filter($products, function ($product) {
                $price = (float) ($product['price'] ?? 0);
                $compare = (float) ($product['compare_at_price'] ?? 0);
                if ($compare > 0 && $compare > $price) {
                    return true;
                }
                $tags = strtolower((string) ($product['tags'] ?? ''));
                return str_contains($tags, 'sale');
            });

            if (!empty($saleProducts)) {
                return array_values($saleProducts);
            }
        }

        $tagMap = [
            'men-all' => ['Men'],
            'men-winter' => ['Men', 'Winter'],
            'women-all' => ['Women'],
            'women-sale' => ['Women'],
            'men-sale' => ['Men'],
            'winter25' => ['Winter'],
            '11-11-sale' => ['New'],
            '11-11-sale-men' => ['Men'],
            '11-11-sale-women' => ['Women'],
            '12-12-sale' => ['New'],
            '12-12-sale-men' => ['Men'],
            '12-12-sale-women' => ['Women'],
            'outerwear' => ['Outerwear'],
            'activewear' => ['Activewear'],
            'accessories' => ['Accessories'],
            'caps' => ['Caps', 'Cap', 'Accessories'],
            'glasses' => ['Glasses', 'Sunglasses', 'Eyewear'],
            'watches' => ['Watches', 'Watch'],
            'bags' => ['Bags', 'Bag'],
            'scarves' => ['Scarves', 'Scarf'],
            'belts' => ['Belts', 'Belt'],
            'kids-baby' => ['Kids', 'Baby'],
            'baby-clothing' => ['Baby'],
            'home-textiles' => ['Home', 'Textiles', 'Bedding'],
        ];

        $tags = $tagMap[$slug] ?? [];
        if (empty($tags)) {
            if (str_contains($slugLower, 'women')) {
                $tags = ['Women'];
            } elseif (str_contains($slugLower, 'men')) {
                $tags = ['Men'];
            }
        }

        if (empty($tags)) {
            return [];
        }

        $filtered = array_filter($products, function ($product) use ($tags) {
            $productTags = collect(explode(',', (string) ($product['tags'] ?? '')))
                ->map(fn ($tag) => strtolower(trim($tag)))
                ->filter()
                ->values();

            foreach ($tags as $tag) {
                if (!$productTags->contains(strtolower($tag))) {
                    return false;
                }
            }

            return true;
        });

        return array_values($filtered);
    }

    private function formatFallbackApiProduct(array $product, string $brandKey, string $defaultBrand): array
    {
        $slug = $product['slug'] ?? Str::slug($product['name'] ?? 'product');
        $image = $product['image'] ?? data_get($product, 'gallery.0.src');
        $priceValue = (float) ($product['price'] ?? 0);
        $converted = CurrencyFormatter::convert($priceValue);
        $collectionSlug = $brandKey === $defaultBrand ? 'men-all' : 'all';
        $url = $brandKey === $defaultBrand
            ? route('products.show', ['collection' => $collectionSlug, 'slug' => $slug])
            : route('brands.products.show', [
                'brand' => $brandKey,
                'collection' => $collectionSlug,
                'slug' => $slug,
            ]);

        return [
            'id' => $brandKey . '::' . $slug,
            'handle' => $slug,
            'brand' => $brandKey,
            'title' => $product['name'] ?? Str::of($slug)->replace('-', ' ')->title()->value(),
            'price_value' => $converted ?? 0,
            'price_label' => CurrencyFormatter::format($priceValue),
            'image' => $image,
            'url' => $url,
            'available' => (bool) ($product['available'] ?? true),
        ];
    }

    private function fallbackProductsForSlug(string $slug, ?string $brandKey = null)
    {
        $products = Product::query()
            ->with('images')
            ->when($brandKey, function ($query) use ($brandKey) {
                $query->where('brand_key', $brandKey);
            })
            ->orderBy('id')
            ->get();

        if ($products->isNotEmpty()) {
            $filtered = $this->filterProductsBySlug($products, $slug);

            if ($filtered->isNotEmpty()) {
                return $filtered;
            }

            if ($this->isCategorySlug($slug)) {
                $fallbackProducts = $this->fallbackProductsForBrand($brandKey);
                if (!empty($fallbackProducts)) {
                    $fallbackFiltered = $this->filterFallbackProductsBySlug($fallbackProducts, $slug);
                    return collect(!empty($fallbackFiltered) ? $fallbackFiltered : $fallbackProducts);
                }
            }

            return $products;
        }

        $fallbackProducts = $this->fallbackProductsForBrand($brandKey);
        if (!empty($fallbackProducts)) {
            $filtered = $this->filterFallbackProductsBySlug($fallbackProducts, $slug);

            return collect(!empty($filtered) ? $filtered : $fallbackProducts);
        }

        return collect($this->buildProducts(18));
    }

    private function isCategorySlug(string $slug): bool
    {
        $primary = collect(config('catalog.categories.primary', []))
            ->pluck('slug')
            ->filter()
            ->all();
        $accessories = collect(config('catalog.categories.accessories', []))
            ->pluck('slug')
            ->filter()
            ->all();

        if ($slug === 'accessories') {
            return true;
        }

        return in_array($slug, $primary, true) || in_array($slug, $accessories, true);
    }

    private function filterProductsBySlug($products, string $slug)
    {
        $saleProducts = $this->filterSaleProducts($products, $slug);
        if ($saleProducts->isNotEmpty()) {
            return $saleProducts;
        }

        $tagMap = [
            'men-all' => ['Men'],
            'men-winter' => ['Men', 'Winter'],
            'women-all' => ['Women'],
            'women-sale' => ['Women'],
            'men-sale' => ['Men'],
            'winter25' => ['Winter'],
            '11-11-sale' => ['New'],
            '11-11-sale-men' => ['Men'],
            '11-11-sale-women' => ['Women'],
            '12-12-sale' => ['New'],
            '12-12-sale-men' => ['Men'],
            '12-12-sale-women' => ['Women'],
            'peridot' => ['Peridot'],
            'coral' => ['Coral'],
            'outerwear' => ['Outerwear', 'Jackets', 'Coats'],
            'activewear' => ['Activewear', 'Athleisure'],
            'accessories' => ['Accessories'],
            'caps' => ['Caps', 'Cap', 'Accessories'],
            'glasses' => ['Glasses', 'Sunglasses', 'Eyewear'],
            'watches' => ['Watches', 'Watch'],
            'bags' => ['Bags', 'Bag'],
            'scarves' => ['Scarves', 'Scarf'],
            'belts' => ['Belts', 'Belt'],
            'kids-baby' => ['Kids', 'Baby'],
            'baby-clothing' => ['Baby'],
            'home-textiles' => ['Home', 'Textiles', 'Bedding'],
        ];

        $tags = $tagMap[$slug] ?? [];
        if (empty($tags)) {
            $slugLower = strtolower($slug);
            if (str_contains($slugLower, 'women')) {
                $tags = ['Women'];
            } elseif (str_contains($slugLower, 'men')) {
                $tags = ['Men'];
            }
        }

        if (empty($tags)) {
            return collect();
        }

        return $products->filter(function ($product) use ($tags) {
            $productTags = collect(explode(',', $product->tags ?? ''))
                ->map(fn ($tag) => trim($tag))
                ->map(fn ($tag) => strtolower($tag))
                ->filter()
                ->values();

            foreach ($tags as $tag) {
                if (!$productTags->contains(strtolower($tag))) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    private function filterSaleProducts($products, string $slug)
    {
        $slugLower = strtolower($slug);
        if (!Str::contains($slugLower, 'sale')) {
            return collect();
        }

        $needsMen = Str::contains($slugLower, 'men');
        $needsWomen = Str::contains($slugLower, 'women');

        return $products->filter(function ($product) use ($needsMen, $needsWomen) {
            $price = (float) ($product->price ?? 0);
            $compare = (float) ($product->compare_at_price ?? 0);
            $hasDiscount = $compare > 0 && $compare > $price;
            if (!$hasDiscount) {
                return false;
            }

            $tagList = collect(explode(',', (string) ($product->tags ?? '')))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->values();
            $tagText = strtolower($tagList->implode(' '));
            $titleText = strtolower((string) ($product->title ?? ''));

            if ($needsMen && !str_contains($tagText, 'men') && !str_contains($titleText, 'men')) {
                return false;
            }

            if ($needsWomen && !str_contains($tagText, 'women') && !str_contains($titleText, 'women')) {
                return false;
            }

            return true;
        })->values();
    }

    private function resolveSidebarCollections(string $slug, ?string $brandKey = null)
    {
        $collections = Collection::query()
            ->withCount('products')
            ->has('products')
            ->when($brandKey, function ($query) use ($brandKey) {
                $query->where('brand_key', $brandKey);
            })
            ->orderBy('title')
            ->get();

        if ($collections->isEmpty()) {
            return collect();
        }

        if (Str::contains($slug, 'men')) {
            return $this->collectionsByTag('Men')->whenEmpty(fn () => $collections);
        }

        if (Str::contains($slug, 'women')) {
            return $this->collectionsByTag('Women')->whenEmpty(fn () => $collections);
        }

        if (Str::contains($slug, 'winter')) {
            return $this->collectionsByTag('Winter')->whenEmpty(fn () => $collections);
        }

        if (Str::contains($slug, 'sale')) {
            $saleHandles = [
                '11-11-sale',
                '11-11-sale-men',
                '11-11-sale-women',
                '12-12-sale',
                '12-12-sale-men',
                '12-12-sale-women',
                'sale',
                'men-sale',
                'women-sale',
            ];
            $saleCollections = $collections->whereIn('handle', $saleHandles)->values();

            return $saleCollections->isNotEmpty() ? $saleCollections : $collections;
        }

        return $collections;
    }

    private function resolveSidebarGroups(?string $brandKey = null): array
    {
        $collections = Collection::query()
            ->withCount('products')
            ->has('products')
            ->when($brandKey, function ($query) use ($brandKey) {
                $query->where('brand_key', $brandKey);
            })
            ->orderBy('title')
            ->get();

        $menCollections = $this->collectionsByTag('Men', $brandKey);
        if ($menCollections->isEmpty()) {
            $menCollections = $collections->filter(function ($collection) {
                return Str::contains(strtolower($collection->handle), 'men');
            });
        }

        $womenCollections = $this->collectionsByTag('Women', $brandKey);
        if ($womenCollections->isEmpty()) {
            $womenCollections = $collections->filter(function ($collection) {
                return Str::contains(strtolower($collection->handle), 'women');
            });
        }

        return [
            'men' => $menCollections->values(),
            'women' => $womenCollections->values(),
        ];
    }

    private function collectionsByTag(string $tag, ?string $brandKey = null)
    {
        return Collection::query()
            ->withCount('products')
            ->when($brandKey, function ($query) use ($brandKey) {
                $query->where('brand_key', $brandKey);
            })
            ->whereHas('products', function ($query) use ($tag) {
                $query->whereRaw("(',' || lower(tags) || ',') LIKE ?", ['%,' . strtolower($tag) . ',%']);
            })
            ->orderBy('title')
            ->get();
    }

    private function resolveColorFilters($products): array
    {
        $palette = $this->colorPalette();
        $patterns = $this->colorPatterns();
        $found = [];

        foreach ($products as $product) {
            $text = $this->productColorText($product);
            $keys = $this->extractColorKeysFromText($text, $patterns);
            foreach ($keys as $key) {
                $found[$key] = true;
            }
        }

        $filters = [];
        foreach ($palette as $key => $hex) {
            if (!isset($found[$key])) {
                continue;
            }
            $filters[] = [
                'key' => $key,
                'label' => Str::of($key)->replace('-', ' ')->title()->value(),
                'value' => $hex,
            ];
        }

        return $filters;
    }

    private function mapProductColors($products): array
    {
        $patterns = $this->colorPatterns();
        $map = [];

        foreach ($products as $product) {
            $handle = $product instanceof Product
                ? $product->handle
                : (string) ($product['slug'] ?? Str::slug($product['name'] ?? 'product'));
            $text = $this->productColorText($product);
            $map[$handle] = $this->extractColorKeysFromText($text, $patterns);
        }

        return $map;
    }

    private function productColorText($product): string
    {
        if ($product instanceof Product) {
            $parts = [
                (string) $product->title,
                (string) $product->product_type,
                (string) $product->tags,
                strip_tags((string) $product->body_html),
            ];
        } else {
            $parts = [
                (string) ($product['name'] ?? ''),
                (string) ($product['product_type'] ?? ''),
                (string) ($product['tags'] ?? ''),
                (string) ($product['description'] ?? ''),
                strip_tags((string) ($product['body_html'] ?? '')),
            ];
        }

        return strtolower(implode(' ', $parts));
    }

    private function extractColorKeysFromText(string $text, array $patterns): array
    {
        $keys = [];

        foreach ($patterns as $key => $variants) {
            foreach ($variants as $variant) {
                $pattern = '/\b' . preg_quote($variant, '/') . '\b/i';
                if (preg_match($pattern, $text)) {
                    $keys[$key] = true;
                    break;
                }
            }
        }

        return array_keys($keys);
    }

    private function colorPatterns(): array
    {
        $palette = $this->colorPalette();
        $patterns = [];

        foreach ($palette as $key => $_) {
            $variants = [$key];
            if (Str::contains($key, '-')) {
                $variants[] = str_replace('-', ' ', $key);
                $variants[] = str_replace('-', '', $key);
            }
            $patterns[$key] = array_values(array_unique($variants));
        }

        $patterns['black'] = array_merge($patterns['black'] ?? [], [
            'charcoal',
            'graphite',
            'jet',
            'onyx',
        ]);
        $patterns['white'] = array_merge($patterns['white'] ?? [], [
            'off white',
            'offwhite',
            'ivory',
            'cream',
            'snow',
        ]);
        $patterns['red'] = array_merge($patterns['red'] ?? [], [
            'maroon',
            'burgundy',
            'crimson',
            'scarlet',
        ]);

        return $patterns;
    }

    private function colorPalette(): array
    {
        return [
            'black' => '#111',
            'white' => '#fff',
            'red' => '#EA2B20',
        ];
    }

    private function productsByTags(array $tags, int $limit, ?string $brandKey = null)
    {
        $query = Product::query()
            ->with('images')
            ->when($brandKey, function ($query) use ($brandKey) {
                $query->where('brand_key', $brandKey);
            });
        foreach ($tags as $tag) {
            $query->whereRaw("(',' || lower(tags) || ',') LIKE ?", ['%,' . strtolower($tag) . ',%']);
        }

        return $query->orderByDesc('source_created_at')->take($limit)->get();
    }

    private function productsFromCollection(string $handle, int $limit, ?array $tags = null, ?string $brandKey = null)
    {
        $collection = Collection::query()
            ->when($brandKey, function ($query) use ($brandKey) {
                $query->where('brand_key', $brandKey);
            })
            ->where('handle', $handle)
            ->with(['products.images'])
            ->first();

        if (!$collection) {
            return collect();
        }

        $products = $collection->products;
        if ($tags) {
            $products = $products->filter(function ($product) use ($tags) {
                return $this->productHasTags($product, $tags);
            });
        }

        return $products->sortByDesc('source_created_at')->take($limit)->values();
    }

    private function productHasTags($product, array $tags): bool
    {
        $productTags = collect(explode(',', $product->tags ?? ''))
            ->map(fn ($tag) => strtolower(trim($tag)))
            ->filter()
            ->values();

        foreach ($tags as $tag) {
            if (!$productTags->contains(strtolower($tag))) {
                return false;
            }
        }

        return true;
    }

    private function resolveProduct(string $slug): array
    {
        $map = [
            'dark-grey' => [
                'name' => 'Dark Grey',
                'sku' => 'KBMUS-DG-01',
                'price' => 4290,
                'description' => 'Premium winter fabric with a soft finish and structured drape, ideal for daily wear.',
                'details' => 'Unstitched • 4.0m fabric • Season: Winter • Care: Dry clean recommended.',
                'colors' => ['#111', '#EA2B20', '#fff'],
            ],
        ];

        if (isset($map[$slug])) {
            $product = $map[$slug];
        } else {
            $title = Str::of($slug)->replace('-', ' ')->title()->value();

            $product = [
                'name' => $title,
                'sku' => 'KB-' . strtoupper(Str::of($slug)->replace('-', '')->limit(6, '')->value()),
                'price' => 4290,
                'description' => 'Classic winter fabric designed for comfort and durability.',
                'details' => 'Unstitched • 4.0m fabric • Season: Winter • Care: Dry clean recommended.',
                'colors' => ['#111', '#EA2B20', '#fff'],
            ];
        }

        $gallery = $product['gallery'] ?? $this->placeholderGallery('product-' . $slug, 4);
        $product['gallery'] = $gallery;
        $product['image'] = $product['image'] ?? ($gallery[0]['src'] ?? null);
        $product['alt_image'] = $product['alt_image'] ?? ($gallery[1]['src'] ?? null);

        return $product;
    }

    private function resolveCollectionTitle(string $slug): string
    {
        $map = [
            'men-all' => 'Men All',
            'women-all' => 'Women All',
            'winter25' => "Winter '25",
            'men-winter' => 'Men Winter',
            '11-11-sale' => '12.12 Sale',
            '11-11-sale-men' => '12.12 Sale Men',
            '11-11-sale-women' => '12.12 Sale Women',
            '12-12-sale' => '12.12 Sale',
            '12-12-sale-men' => '12.12 Sale Men',
            '12-12-sale-women' => '12.12 Sale Women',
            'all-season-men' => 'Men All Seasons',
            'dewan-e-khaas' => 'Dewan-e-Khaas Collection',
            'oxford' => 'Oxford Collection',
            'jasper' => 'Jasper Collection',
            'venus' => 'Venus Collection',
            'jupiter' => 'Jupiter Collection',
            'coral' => 'Coral Collection',
            'peridot' => 'Peridot Collection',
            'sang-e-marmar' => 'Sang e marmar Collection',
            'naltar' => 'Naltar Collection',
            'deosai' => 'Deosai Collection',
            'sale' => 'Sale Collection',
            'men-sale' => 'Men Sale',
            'women-sale' => 'Women Sale',
            'new-arrivals' => 'New Arrivals',
            'linen25' => 'Linen 25',
            'outerwear' => 'Outerwear',
            'activewear' => 'Activewear',
            'accessories' => 'Accessories',
            'caps' => 'Caps',
            'glasses' => 'Glasses',
            'watches' => 'Watches',
            'bags' => 'Bags',
            'scarves' => 'Scarves',
            'belts' => 'Belts',
            'kids-baby' => 'Kids & Baby',
            'baby-clothing' => 'Baby Clothing',
            'home-textiles' => 'Home Textiles',
            'all' => 'All Products',
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        return Str::of($slug)->replace('-', ' ')->title()->value();
    }

    private function resolveSaleAlias(string $slug): ?string
    {
        $aliases = [
            '12-12-sale' => '11-11-sale',
            '12-12-sale-men' => '11-11-sale-men',
            '12-12-sale-women' => '11-11-sale-women',
        ];

        return $aliases[$slug] ?? null;
    }

    private function resolveBrandKey(?string $brandKey = null): string
    {
        $brands = (array) config('catalog.brands', []);
        $default = (string) (config('catalog.default_brand')
            ?? array_key_first($brands)
            ?? 'default');

        if (!$brandKey) {
            return $default;
        }

        return isset($brands[$brandKey]) ? $brandKey : $default;
    }

    private function brandProfiles(): array
    {
        return [
            'khanabadosh' => [
                'tagline' => 'Heritage winter fabrics with a premium hand feel.',
                'tone' => 'heritage',
            ],
            'demo-brand' => [
                'tagline' => 'Studio-driven essentials with clean, modern lines.',
                'tone' => 'studio',
            ],
            'northline' => [
                'tagline' => 'Performance outerwear engineered for cold climates.',
                'tone' => 'outdoor',
            ],
            'harbour-loom' => [
                'tagline' => 'Coastal textures, linen blends, and relaxed comfort.',
                'tone' => 'coastal',
            ],
            'sable-atelier' => [
                'tagline' => 'Minimal tailoring with soft luxe finishes.',
                'tone' => 'atelier',
            ],
        ];
    }

    private function resolveBrandProfile(string $brandKey): array
    {
        $profile = $this->brandProfiles()[$brandKey] ?? [];

        return array_merge([
            'tagline' => 'Curated seasonal essentials for modern wardrobes.',
            'tone' => 'classic',
        ], $profile);
    }

    private function resolveBrandLabel(string $brandKey): string
    {
        $brands = (array) config('catalog.brands', []);
        $label = $brands[$brandKey]['label'] ?? null;

        if ($label) {
            return $label;
        }

        return Str::of($brandKey)->replace('-', ' ')->title()->value();
    }
}
