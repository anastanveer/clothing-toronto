@extends('layouts.app')

@section('title', 'Shop Left Sidebar')

@section('content')
@php
    $categoryLabel = $activeCategory ? ($categories[$activeCategory] ?? ucfirst($activeCategory)) : null;
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
        ['label' => 'Shop', 'url' => route('shop')],
    ];

    if ($categoryLabel) {
        $breadcrumbs[] = ['label' => $categoryLabel, 'is_current' => true];
    } else {
        $breadcrumbs[count($breadcrumbs) - 1]['is_current'] = true;
    }
@endphp

<x-layout.page>
    <x-page.header
        :title="$categoryLabel ? $categoryLabel . ' Collection' : 'Shop Left Sidebar'"
        :breadcrumbs="$breadcrumbs"
    />

    <!-- MAIN CONTENT SECTION START -->
    <div class="ul-inner-page-container">
        <div class="ul-inner-products-wrapper">
            <div class="row ul-bs-row flex-column-reverse flex-md-row">
                <!-- left side bar -->
                <div class="col-lg-3 col-md-4">
                    <div class="ul-products-sidebar">
                        <!-- single widget / search -->
                        <div class="ul-products-sidebar-widget ul-products-search">
                            <form action="#" class="ul-products-search-form">
                                <input type="text" name="product-search" id="ul-products-search-field" placeholder="Search Items">
                                <button><i class="flaticon-search-interface-symbol"></i></button>
                            </form>
                        </div>

                        <!-- single widget / price filter -->
                        <div class="ul-products-sidebar-widget ul-products-price-filter">
                            <h3 class="ul-products-sidebar-widget-title">Filter by price</h3>
                            <form action="#" class="ul-products-price-filter-form">
                                <div id="ul-products-price-filter-slider"></div>
                                <span class="filtered-price">$19 - $69</span>
                            </form>
                        </div>

                        <!-- single widget / categories -->
                        <div class="ul-products-sidebar-widget ul-products-categories">
                            <h3 class="ul-products-sidebar-widget-title">Categories</h3>

                            <div class="ul-products-categories-link">
                                <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> Lifestyle</span></a>
                                <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> Beauty &amp; Fashion</span></a>
                                <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> Fitness &amp; Health</span></a>
                                <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> Food &amp; Cooking</span></a>
                                <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> Tech &amp; Gadgets</span></a>
                                <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> Entertainment</span></a>
                            </div>
                        </div>

                        <!-- single widget / color filter -->
                        <div class="ul-products-sidebar-widget ul-products-color-filter">
                            <h3 class="ul-products-sidebar-widget-title">Filter By Color</h3>

                            <div class="ul-products-color-filter-colors">
                                <a href="{{ route('shop') }}" class="black">
                                    <span class="left"><span class="color-prview"></span> Black</span>
                                    <span>14</span>
                                </a>
                                <a href="{{ route('shop') }}" class="green">
                                    <span class="left"><span class="color-prview"></span> Green</span>
                                    <span>14</span>
                                </a>
                                <a href="{{ route('shop') }}" class="blue">
                                    <span class="left"><span class="color-prview"></span> Blue</span>
                                    <span>14</span>
                                </a>
                                <a href="{{ route('shop') }}" class="red">
                                    <span class="left"><span class="color-prview"></span> Red</span>
                                    <span>14</span>
                                </a>
                                <a href="{{ route('shop') }}" class="yellow">
                                    <span class="left"><span class="color-prview"></span> Yellow</span>
                                    <span>14</span>
                                </a>
                                <a href="{{ route('shop') }}" class="pink">
                                    <span class="left"><span class="color-prview"></span> Pink</span>
                                    <span>14</span>
                                </a>
                            </div>
                        </div>

                        <!-- single widget / featured products -->
                        <div class="ul-products-sidebar-widget ul-products-featured">
                            <h3 class="ul-products-sidebar-widget-title">Featured</h3>

                            <div class="ul-products-featured-products">
                                <div class="ul-products-featured-product">
                                    <div class="ul-products-featured-product-img">
                                        <img src="{{ asset('assets/img/product-img-sm-1.jpg') }}" alt="Featured Product">
                                    </div>
                                    <div class="ul-products-featured-product-txt">
                                        <span class="price">$99.00</span>
                                        <h4 class="title"><a href="{{ route('shop.details') }}">Orange Airsuit</a></h4>
                                        <h5 class="category"><a href="{{ route('shop') }}">Fashion Bag</a></h5>
                                    </div>
                                </div>

                                <div class="ul-products-featured-product">
                                    <div class="ul-products-featured-product-img">
                                        <img src="{{ asset('assets/img/product-img-sm-2.jpg') }}" alt="Featured Product">
                                    </div>
                                    <div class="ul-products-featured-product-txt">
                                        <span class="price">$99.00</span>
                                        <h4 class="title"><a href="{{ route('shop.details') }}">Orange Airsuit</a></h4>
                                        <h5 class="category"><a href="{{ route('shop') }}">Fashion Bag</a></h5>
                                    </div>
                                </div>

                                <div class="ul-products-featured-product">
                                    <div class="ul-products-featured-product-img">
                                        <img src="{{ asset('assets/img/product-img-sm-3.jpg') }}" alt="Featured Product">
                                    </div>
                                    <div class="ul-products-featured-product-txt">
                                        <span class="price">$99.00</span>
                                        <h4 class="title"><a href="{{ route('shop.details') }}">Orange Airsuit</a></h4>
                                        <h5 class="category"><a href="{{ route('shop') }}">Fashion Bag</a></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- single widget / rating filter -->
                        <div class="ul-products-sidebar-widget ul-products-rating-filter">
                            <h3 class="ul-products-sidebar-widget-title">Filter By Rating</h3>

                            <div class="ul-products-rating-filter-ratings">
                                <div class="single-rating-wrapper">
                                    <label for="ul-products-review-5-star">
                                        <span class="left">
                                            <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-5-star" hidden>
                                            <span class="stars">
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                            </span>
                                        </span>
                                        <span class="right">5 Only</span>
                                    </label>
                                </div>

                                <div class="single-rating-wrapper">
                                    <label for="ul-products-review-4-star">
                                        <span class="left">
                                            <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-4-star" hidden>
                                            <span class="stars">
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                            </span>
                                        </span>
                                        <span class="right">4 &amp; up</span>
                                    </label>
                                </div>

                                <div class="single-rating-wrapper">
                                    <label for="ul-products-review-3-star">
                                        <span class="left">
                                            <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-3-star" hidden>
                                            <span class="stars">
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                            </span>
                                        </span>
                                        <span class="right">3 &amp; up</span>
                                    </label>
                                </div>

                                <div class="single-rating-wrapper">
                                    <label for="ul-products-review-2-star">
                                        <span class="left">
                                            <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-2-star" hidden>
                                            <span class="stars">
                                                <span><i class="flaticon-star"></i></span>
                                                <span><i class="flaticon-star"></i></span>
                                            </span>
                                        </span>
                                        <span class="right">2 &amp; up</span>
                                    </label>
                                </div>

                                <div class="single-rating-wrapper">
                                    <label for="ul-products-review-1-star">
                                        <span class="left">
                                            <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-1-star" hidden>
                                            <span class="stars">
                                                <span><i class="flaticon-star"></i></span>
                                            </span>
                                        </span>
                                        <span class="right">1 &amp; up</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- right products container -->
                <div class="col-lg-9 col-md-8">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('shop') }}" class="btn btn-sm {{ $activeCategory ? 'btn-outline-dark' : 'btn-dark' }}">All</a>
                        @foreach($categories as $key => $label)
                            <a href="{{ route('shop.category', $key) }}" class="btn btn-sm {{ $activeCategory === $key ? 'btn-dark' : 'btn-outline-dark' }}">{{ $label }}</a>
                        @endforeach
                    </div>

                    <div class="row ul-bs-row row-cols-lg-3 row-cols-2 row-cols-xxs-1">
                        @forelse($products as $product)
                            <div class="col">
                                <x-product.card :product="$product" />
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="py-5 text-center text-muted fw-semibold">No products available right now. Please check again soon.</div>
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
