@extends('layouts.app')

@section('title', 'Shop No Sidebar')

@section('content')
@php
    $baseProducts = collect(config('catalog.products.standard'))
        ->map(function ($product) {
            $categoryRoute = $product['category_route'] ?? null;

            return array_merge($product, [
                'details_url' => route('shop.details'),
                'category_url' => $categoryRoute ? route($categoryRoute) : '#',
            ]);
        });

    $products = $baseProducts->concat($baseProducts);
@endphp

<x-layout.page>
    <x-page.header
        title="Shop Without Sidebar"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Shop', 'is_current' => true],
        ]"
    />

    <!-- MAIN CONTENT SECTION START -->
    <div class="ul-inner-page-container">
        <div class="ul-inner-products-wrapper">
            <div class="row ul-bs-row flex-column-reverse flex-md-row">
                <!-- right products container -->
                <div class="col-12">
                    <div class="row ul-bs-row row-cols-lg-4 row-cols-sm-3 row-cols-2 row-cols-xxs-1">
                        @foreach($products as $product)
                            <div class="col">
                                <x-product.card :product="$product" />
                            </div>
                        @endforeach
                    </div>

                    <!-- pagination -->
                    <div class="ul-pagination">
                        <ul>
                            <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                            <li class="pages">
                                <a href="#" class="active">01</a>
                                <a href="#">02</a>
                                <a href="#">03</a>
                                <a href="#">04</a>
                                <a href="#">05</a>
                            </li>
                            <li><a href="#"><i class="flaticon-arrow-point-to-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT SECTION END -->
</x-layout.page>
@endsection
