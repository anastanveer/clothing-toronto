@extends('layouts.app')

@php
    $categoryLabel = $activeCategory ? ($categories[$activeCategory] ?? ucfirst($activeCategory)) : null;
    $brandName = $activeBrand->name ?? null;
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
        ['label' => 'Shop', 'url' => route('shop')],
    ];

    if ($brandName) {
        $breadcrumbs[] = [
            'label' => $brandName,
            'url' => route('shop.brand', $activeBrand->slug),
            'is_current' => ! $categoryLabel,
        ];
    }

    if ($categoryLabel) {
        $breadcrumbs[] = ['label' => $categoryLabel, 'is_current' => true];
    } elseif (! $brandName) {
        $breadcrumbs[count($breadcrumbs) - 1]['is_current'] = true;
    }

    $pageTitle = match (true) {
        $brandName && $categoryLabel => $brandName . ' · ' . $categoryLabel,
        $brandName => $brandName . ' Capsule',
        $categoryLabel => $categoryLabel . ' Collection',
        default => 'Shop Left Sidebar',
    };
@endphp

@section('title', $pageTitle)

@section('content')

<x-layout.page>
    <x-page.header
        :title="$pageTitle"
        :breadcrumbs="$breadcrumbs"
    />

    <x-shop.brand-hero :brand="$activeBrand ?? null" />

    <!-- MAIN CONTENT SECTION START -->
    <div class="ul-inner-page-container">
        <div class="ul-inner-products-wrapper">
            <x-shop.filters-bar
                :categories="$categories"
                :active-category="$activeCategory"
                :filters="$filters"
                :active-filters="$activeFilters"
                :sort-options="$filterOptions['sorts'] ?? []"
                form-id="shopFiltersForm"
            />

            <div class="row ul-bs-row flex-column-reverse flex-md-row">
                <!-- left side bar -->
                <div class="col-lg-3 col-md-4">
                    <x-shop.sidebar
                        :filters="$filters"
                        :price-range="$priceRange"
                        :filter-options="$filterOptions"
                        :featured-products="$featuredProducts"
                        :brand-options="$brandOptions"
                        :active-category="$activeCategory"
                        :active-brand="$activeBrand ?? null"
                        form-id="shopFiltersForm"
                    />
                </div>

                <!-- right products container -->
                <div class="col-lg-9 col-md-8">
                    <div class="row ul-bs-row row-cols-lg-3 row-cols-2 row-cols-xxs-1" id="shopProductsGrid">
                        @forelse($products as $product)
                            <div class="col">
                                <x-product.card :product="$product" />
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="py-5 text-center text-muted fw-semibold">No products match your filters right now. Try adjusting the filters to discover more styles.</div>
                            </div>
                        @endforelse
                    </div>

                    <!-- pagination -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT SECTION END -->
</x-layout.page>
@endsection
