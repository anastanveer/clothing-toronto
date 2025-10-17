@props([
    'filters' => [],
    'priceRange' => ['min' => 0, 'max' => 0],
    'filterOptions' => ['colors' => [], 'ratings' => [], 'sorts' => []],
    'featuredProducts' => null,
    'brandOptions' => [],
    'activeCategory' => null,
    'activeBrand' => null,
    'formId' => 'shopFiltersForm',
    'showFeatured' => true,
    'showClearAction' => true,
])

@php
    $colors = collect($filterOptions['colors'] ?? []);
    $ratings = collect($filterOptions['ratings'] ?? []);
    $searchValue = $filters['search'] ?? '';
    $priceMin = $filters['price_min'] ?? ($priceRange['min'] ?? 0);
    $priceMax = $filters['price_max'] ?? ($priceRange['max'] ?? 0);
    $priceMin = is_numeric($priceMin) ? $priceMin : 0;
    $priceMax = is_numeric($priceMax) ? $priceMax : 0;
    $featuredCollection = collect($featuredProducts);
    $priceActive = $filters['price_requested'] ?? false;
    $brandOptions = collect($brandOptions);
    $activeBrandModel = $activeBrand;
    $activeBrandName = $filters['brand'] ?? ($activeBrandModel->name ?? null);
    $activeBrandSlug = $filters['brand_slug'] ?? ($activeBrandModel->slug ?? null);
    $activeBrandId = $filters['brand_id'] ?? ($activeBrandModel->id ?? null);
    $queryBase = collect(request()->query())->except('page');
    $brandQueryBaseDefault = $queryBase->except(['brand', 'brand_slug', 'brand_id', 'page']);
    $activeColorValue = $filters['color'] ?? null;
    $activeColorLabel = $activeColorValue
        ? optional($colors->firstWhere('value', $activeColorValue))['label'] ?? \Illuminate\Support\Str::title($activeColorValue)
        : 'All colors';
@endphp

<div {{ $attributes->class('ul-products-sidebar') }}>
    <form method="GET" action="{{ request()->url() }}" id="{{ $formId }}" class="ul-products-sidebar-form" data-filter-form>
        @if(!empty($filters['brand_slug']))
            <input type="hidden" name="brand" value="{{ $filters['brand_slug'] }}">
        @endif
        @if(!empty($filters['brand_id']))
            <input type="hidden" name="brand_id" value="{{ $filters['brand_id'] }}">
        @endif
        @if(!empty($filters['brand_slug']))
            <input type="hidden" name="brand_slug" value="{{ $filters['brand_slug'] }}">
        @endif
        <div class="ul-products-sidebar-widget ul-products-search">
            <label for="{{ $formId }}-search" class="visually-hidden">Search products</label>
            <div class="ul-products-search-form">
                <input
                    type="search"
                    name="q"
                    id="{{ $formId }}-search"
                    placeholder="Search items"
                    value="{{ $searchValue }}"
                >
                <button type="submit"><i class="flaticon-search-interface-symbol"></i><span class="visually-hidden">Search</span></button>
            </div>
        </div>

        @if(($priceRange['max'] ?? 0) > 0)
            <div class="ul-products-sidebar-widget ul-products-price-filter">
                <h3 class="ul-products-sidebar-widget-title">Filter by price</h3>
                <div class="ul-products-price-filter-form" data-price-filter>
                    <div
                        id="ul-products-price-filter-slider"
                        data-min="{{ $priceRange['min'] ?? 0 }}"
                        data-max="{{ $priceRange['max'] ?? 0 }}"
                        data-start-min="{{ $priceMin }}"
                        data-start-max="{{ $priceMax }}"
                        data-min-target="#{{ $formId }}-price-min"
                        data-max-target="#{{ $formId }}-price-max"
                        data-display-target="#{{ $formId }}-price-display"
                        data-auto-submit="change"
                    ></div>
                    <span class="filtered-price" id="{{ $formId }}-price-display">
                        ${{ number_format($priceMin, 0) }} - ${{ number_format($priceMax, 0) }}
                    </span>
                    <input
                        type="hidden"
                        @if($priceActive) name="price[min]" @endif
                        data-field-name="price[min]"
                        id="{{ $formId }}-price-min"
                        value="{{ $priceMin }}"
                    >
                    <input
                        type="hidden"
                        @if($priceActive) name="price[max]" @endif
                        data-field-name="price[max]"
                        id="{{ $formId }}-price-max"
                        value="{{ $priceMax }}"
                    >
                </div>
            </div>
        @endif

        @if($colors->isNotEmpty())
            <div class="ul-products-sidebar-widget ul-products-color-filter" data-filter-dropdown>
                <h3 class="ul-products-sidebar-widget-title">Filter by color</h3>
                <button type="button" class="ul-filter-dropdown-toggle" data-filter-dropdown-toggle>
                    <span>{{ $activeColorLabel }}</span>
                    <i class="flaticon-arrow-point-to-right"></i>
                </button>
                <div class="ul-filter-dropdown-menu ul-products-color-filter-colors" hidden>
                    <label class="{{ empty($filters['color']) ? 'active' : '' }}">
                        <input
                            type="radio"
                            name="color"
                            value=""
                            @checked(empty($filters['color']))
                            data-filter-change-submit
                            hidden
                        >
                        <span class="left"><span class="color-prview neutral"></span> All colors</span>
                        <span>{{ $colors->sum('count') }}</span>
                    </label>
                    @foreach($colors as $color)
                        @php
                            $value = $color['value'];
                            $className = $color['class'] ?? \Illuminate\Support\Str::slug($value);
                            $isActive = ($filters['color'] ?? null) === $value;
                        @endphp
                        <label class="{{ $className }} {{ $isActive ? 'active' : '' }}">
                            <input
                                type="radio"
                                name="color"
                                value="{{ $value }}"
                                @checked($isActive)
                                data-filter-change-submit
                                hidden
                            >
                            <span class="left"><span class="color-prview"></span> {{ $value }}</span>
                            <span>{{ $color['count'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        @if($brandOptions->isNotEmpty())
            <div class="ul-products-sidebar-widget ul-products-categories" data-filter-accordion>
                <h3 class="ul-products-sidebar-widget-title">Brands &amp; categories</h3>
                <div class="ul-products-categories-accordion">
                    @foreach($brandOptions as $option)
                        @php
                            $brandName = $option['brand'];
                            $brandSlug = $option['slug'];
                            $brandId = $option['id'] ?? null;
                            $brandCount = $option['count'];
                            $brandActive = $option['active'] ?? false;
                            $brandUrl = route('shop.brand', ['slug' => $brandSlug]);
                            $brandQuery = $brandQueryBaseDefault
                                ->filter(fn ($value) => ! is_null($value) && $value !== '');
                            if ($brandQuery->isNotEmpty()) {
                                $brandUrl .= '?' . http_build_query($brandQuery->toArray());
                            }
                        @endphp
                        <div class="ul-brand-group {{ $brandActive ? 'is-open' : '' }}" data-filter-accordion-item>
                            <button type="button" class="ul-brand-toggle" data-filter-accordion-toggle>
                                <span class="label">{{ $brandName }}</span>
                                <span class="count">{{ $brandCount }}</span>
                                <i class="flaticon-arrow-point-to-right"></i>
                            </button>
                            <div class="ul-brand-menu" @if(! $brandActive) hidden @endif>
                                @php
                                    $allStylesQuery = $brandQueryBaseDefault
                                        ->except(['category'])
                                        ->filter(fn ($value) => ! is_null($value) && $value !== '');
                                    $allStylesUrl = route('shop.brand', ['slug' => $brandSlug]);
                                    if ($allStylesQuery->isNotEmpty()) {
                                        $allStylesUrl .= '?' . http_build_query($allStylesQuery->toArray());
                                    }
                                @endphp
                                <a href="{{ $allStylesUrl }}" class="brand-link {{ $brandActive && empty($activeCategory) ? 'active' : '' }}">All styles ({{ $brandCount }})</a>
                                @foreach($option['categories'] as $category)
                                    @php
                                        $categoryQuery = $brandQueryBaseDefault
                                            ->merge([
                                                'category' => $category['key'],
                                            ])
                                            ->filter(fn ($value) => ! is_null($value) && $value !== '');
                                        $categoryUrl = route('shop.brand', ['slug' => $brandSlug]);
                                        if ($categoryQuery->isNotEmpty()) {
                                            $categoryUrl .= '?' . http_build_query($categoryQuery->toArray());
                                        }
                                        $isCategoryActive = $brandActive && $activeCategory === $category['key'];
                                    @endphp
                                    <a href="{{ $categoryUrl }}" class="brand-link {{ $isCategoryActive ? 'active' : '' }}">
                                        {{ $category['label'] }} ({{ $category['count'] }})
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($ratings->isNotEmpty())
            <div class="ul-products-sidebar-widget ul-products-rating-filter">
                <h3 class="ul-products-sidebar-widget-title">Filter by rating</h3>
                <div class="ul-products-rating-filter-ratings">
                    <div class="single-rating-wrapper">
                        <label>
                            <input type="radio" name="rating" value="" @checked(empty($filters['rating'])) data-filter-change-submit hidden>
                            <span class="stars" aria-hidden="true">
                                <span><i class="flaticon-star"></i></span>
                                <span><i class="flaticon-star"></i></span>
                                <span><i class="flaticon-star"></i></span>
                                <span><i class="flaticon-star"></i></span>
                                <span><i class="flaticon-star"></i></span>
                            </span>
                            <span class="right">All ratings</span>
                        </label>
                    </div>
                    @foreach($ratings as $option)
                        @php
                            $value = $option['value'];
                            $isActive = (int) ($filters['rating'] ?? 0) === (int) $value;
                            $label = $option['label'];
                        @endphp
                        <div class="single-rating-wrapper">
                            <label class="{{ $isActive ? 'active' : '' }}">
                                <input type="radio" name="rating" value="{{ $value }}" @checked($isActive) data-filter-change-submit hidden>
                                <span class="stars" aria-hidden="true">
                                    <span><i class="flaticon-star"></i></span>
                                    <span><i class="flaticon-star"></i></span>
                                    <span><i class="flaticon-star"></i></span>
                                    <span><i class="flaticon-star"></i></span>
                                    <span><i class="flaticon-star"></i></span>
                                </span>
                                <span class="right">{{ ucfirst($label) }} <span class="count text-muted">({{ $option['count'] }})</span></span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </form>

    @if($showFeatured && $featuredCollection->isNotEmpty())
        <div class="ul-products-sidebar-widget ul-products-featured">
            <h3 class="ul-products-sidebar-widget-title">Featured</h3>
            <div class="ul-products-featured-products">
                @foreach($featuredCollection as $item)
                    <div class="ul-products-featured-product">
                        <div class="ul-products-featured-product-img">
                            <a href="{{ $item['details_url'] ?? '#' }}">
                                <img src="{{ $item['image'] ?? asset('assets/img/product-img-1.jpg') }}" alt="{{ $item['title'] ?? 'Featured product' }}">
                            </a>
                        </div>
                        <div class="ul-products-featured-product-txt">
                            <span class="price">{{ $item['price'] ?? '' }}</span>
                            @if(!empty($item['original_price']) && $item['original_price'] !== ($item['price'] ?? null))
                                <span class="compare">{{ $item['original_price'] }}</span>
                            @endif
                            <h4 class="title"><a href="{{ $item['details_url'] ?? '#' }}">{{ $item['title'] ?? 'Product' }}</a></h4>
                            <h5 class="category"><a href="{{ $item['category_url'] ?? '#' }}">{{ $item['category'] ?? '' }}</a></h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($showClearAction)
        <div class="ul-products-sidebar-widget text-center">
            <a href="{{ request()->url() }}" class="btn btn-outline-dark w-100" data-clear-filters>Clear filters</a>
        </div>
    @endif
</div>
