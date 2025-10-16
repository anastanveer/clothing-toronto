@extends('layouts.app')

@php
    $brandName = $activeBrand->name ?? null;
    $categoryLabel = $activeCategory ? ($categories[$activeCategory] ?? ucfirst($activeCategory)) : null;
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
        default => 'Shop Right Sidebar',
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
                :categories="$categories ?? []"
                :active-category="$activeCategory ?? null"
                :filters="$filters ?? []"
                :active-filters="$activeFilters ?? null"
                :sort-options="$filterOptions['sorts'] ?? []"
                form-id="shopFiltersForm"
            />

            <div class="row ul-bs-row flex-column flex-md-row">
                <!-- right products container -->
                <div class="col-lg-9 col-md-8">
                    <div class="row ul-bs-row row-cols-lg-3 row-cols-2 row-cols-xxs-1">
                        @forelse($products as $product)
                            <div class="col">
                                <x-product.card :product="$product" />
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="py-5 text-center text-muted fw-semibold">No products match your filters right now. Try adjusting the filters to explore more looks.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>

                <!-- right side bar -->
                <div class="col-lg-3 col-md-4">
                    <x-shop.sidebar
                        :filters="$filters ?? []"
                        :price-range="$priceRange ?? ['min' => 0, 'max' => 0]"
                        :filter-options="$filterOptions ?? []"
                        :featured-products="$featuredProducts ?? collect()"
                        :brand-options="$brandOptions ?? []"
                        :active-category="$activeCategory ?? null"
                        :active-brand="$activeBrand ?? null"
                        form-id="shopFiltersForm"
                    />
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT SECTION END -->
</x-layout.page>
@endsection
