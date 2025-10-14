@extends('layouts.app')

@section('title', 'Home')

@section('content')
@php
    $productList = collect(config('catalog.products.standard'))
        ->map(function ($product) {
            $categoryRoute = $product['category_route'] ?? null;

            return array_merge($product, [
                'details_url' => route('shop.details'),
                'category_url' => $categoryRoute ? route($categoryRoute) : '#',
            ]);
        });

    $productSliderItems = $productList->concat($productList);
    $flashSaleProducts = $productList;

    $horizontalProducts = collect(config('catalog.products.compact'))
        ->map(function ($product) {
            return array_merge($product, [
                'details_url' => route('shop.details'),
                'category_url' => route('shop'),
                'rating' => 5,
            ]);
        });

    $filterClasses = [
        'best-selling',
        'on-selling',
        'top-rating',
        'top-rating',
        'on-selling',
        'best-selling',
        'on-selling',
        'top-rating',
        'on-selling',
        'best-selling',
        'best-selling',
        'on-selling',
    ];
@endphp

<x-layout.page>
        <!-- BANNER SECTION START -->
        <div class="overflow-hidden">
            <div class="ul-container">
                <section class="ul-banner">
                    <div class="ul-banner-slider-wrapper">
                        <div class="ul-banner-slider swiper">
                            <div class="swiper-wrapper">
                                <!-- single slide -->
                                <div class="swiper-slide ul-banner-slide">
                                    <div class="ul-banner-slide-img">
                                        <img src="{{ asset('assets/img/banner-slide-1.jpg') }}" alt="Banner Image">
                                    </div>
                                    <div class="ul-banner-slide-txt">
                                        <span class="ul-banner-slide-sub-title">Perfect for Summer Evenings</span>
                                        <h1 class="ul-banner-slide-title">Casual and Stylish for All Seasons</h1>
                                        <p class="ul-banner-slide-price">Starting From <span class="price">$129</span></p>
                                        <a href="{{ route('shop') }}" class="ul-btn">SHOP NOW <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div>

                                <!-- single slide -->
                                <div class="swiper-slide ul-banner-slide ul-banner-slide--2">
                                    <div class="ul-banner-slide-img">
                                        <img src="{{ asset('assets/img/banner-slide-2.jpg') }}" alt="Banner Image">
                                    </div>
                                    <div class="ul-banner-slide-txt">
                                        <span class="ul-banner-slide-sub-title">Perfect for Summer Evenings</span>
                                        <h1 class="ul-banner-slide-title">Casual and Stylish for All Seasons</h1>
                                        <p class="ul-banner-slide-price">Starting From <span class="price">$129</span></p>
                                        <a href="{{ route('shop') }}" class="ul-btn">SHOP NOW <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div>

                                <!-- single slide -->
                                <div class="swiper-slide ul-banner-slide ul-banner-slide--3">
                                    <div class="ul-banner-slide-img">
                                        <img src="{{ asset('assets/img/banner-slide-3.jpg') }}" alt="Banner Image">
                                    </div>
                                    <div class="ul-banner-slide-txt">
                                        <span class="ul-banner-slide-sub-title">Perfect for Summer Evenings</span>
                                        <h1 class="ul-banner-slide-title">Casual and Stylish for All Seasons</h1>
                                        <p class="ul-banner-slide-price">Starting From <span class="price">$129</span></p>
                                        <a href="{{ route('shop') }}" class="ul-btn">SHOP NOW <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div>

                                <!-- single slide -->
                                <!-- <div class="swiper-slide ul-banner-slide">
                                    <div class="ul-banner-slide-img">
                                        <img src="{{ asset('assets/img/banner-slide-1.jpg') }}" alt="Banner Image">
                                    </div>
                                    <div class="ul-banner-slide-txt">
                                        <span class="ul-banner-slide-sub-title">Perfect for Summer Evenings</span>
                                        <h1 class="ul-banner-slide-title">Casual and Stylish for All Seasons</h1>
                                        <p class="ul-banner-slide-price">Starting From <span class="price">$129</span></p>
                                        <a href="{{ route('shop') }}" class="ul-btn">SHOP NOW <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div> -->
                            </div>

                            <!-- slider navigation -->
                            <div class="ul-banner-slider-nav-wrapper">
                                <div class="ul-banner-slider-nav">
                                    <button class="prev"><span class="icon"><i class="flaticon-down"></i></span></button>
                                    <button class="next"><span class="icon"><i class="flaticon-down"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ul-banner-img-slider-wrapper">
                        <div class="ul-banner-img-slider swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="{{ asset('assets/img/banner-img-slide-1.jpg') }}" alt="Banner Image">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('assets/img/banner-img-slide-2.jpg') }}" alt="Banner Image">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('assets/img/banner-img-slide-3.jpg') }}" alt="Banner Image">
                                </div>
                                <!-- <div class="swiper-slide">
                                    <img src="{{ asset('assets/img/banner-img-slide-1.jpg') }}" alt="Banner Image">
                                </div> -->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- BANNER SECTION END -->


        <!-- CATEGORY SECTION START -->
        <div class="ul-container">
            <section class="ul-categories">
                <div class="ul-inner-container">
                    <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-1.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Men</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-2.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Kids</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-3.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Pants</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-1.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Men</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-4.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Women</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-5.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Jeans</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-6.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Sweater</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>

                        <!-- single category -->
                        <div class="col">
                            <a class="ul-category" href="{{ route('shop') }}">
                                <div class="ul-category-img">
                                    <img src="{{ asset('assets/img/category-7.jpg') }}" alt="Category Image">
                                </div>
                                <div class="ul-category-txt">
                                    <span>Shoe</span>
                                </div>
                                <div class="ul-category-btn">
                                    <span><i class="flaticon-arrow-point-to-right"></i></span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- CATEGORY SECTION END -->


        <!-- PRODUCTS SECTION START -->
        <div class="ul-container">
            <section class="ul-products">
                <div class="ul-inner-container">
                    <div class="ul-section-heading">
                        <div class="left">
                            <span class="ul-section-sub-title">Summer collection</span>
                            <h2 class="ul-section-title">Shopping Every Day</h2>
                        </div>

                        <div class="right"><a href="#" class="ul-btn">More Collection <i class="flaticon-up-right-arrow"></i></a></div>
                    </div>

                    <div class="row ul-bs-row">
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="ul-products-sub-banner">
                                <div class="ul-products-sub-banner-img">
                                    <img src="{{ asset('assets/img/products-sub-banner-1.jpg') }}" alt="Sub Banner Image">
                                </div>
                                <div class="ul-products-sub-banner-txt">
                                    <h3 class="ul-products-sub-banner-title">Trending Now Only This Weekend!</h3>
                                    <a href="{{ route('shop') }}" class="ul-btn">Shop Now <i class="flaticon-up-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8 col-12">
                            <div class="swiper ul-products-slider-1">
                                <div class="swiper-wrapper">
                                    @foreach($productSliderItems->take(8) as $product)
                                        <div class="swiper-slide">
                                            <x-product.card :product="$product" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="ul-products-slider-nav ul-products-slider-1-nav">
                                <button class="prev"><i class="flaticon-left-arrow"></i></button>
                                <button class="next"><i class="flaticon-arrow-point-to-right"></i></button>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="ul-products-sub-banner">
                                <div class="ul-products-sub-banner-img">
                                    <img src="{{ asset('assets/img/products-sub-banner-2.jpg') }}" alt="Sub Banner Image">
                                </div>
                                <div class="ul-products-sub-banner-txt">
                                    <h3 class="ul-products-sub-banner-title">Trending Now Only This Weekend!</h3>
                                    <a href="{{ route('shop') }}" class="ul-btn">Shop Now <i class="flaticon-up-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8 col-12">
                            <div class="swiper ul-products-slider-2">
                                <div class="swiper-wrapper">
                                    @foreach($productSliderItems->slice(3, 8) as $product)
                                        <div class="swiper-slide">
                                            <x-product.card :product="$product" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="ul-products-slider-nav ul-products-slider-2-nav">
                                <button class="prev"><i class="flaticon-left-arrow"></i></button>
                                <button class="next"><i class="flaticon-arrow-point-to-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- PRODUCTS SECTION END -->


        <!-- AD SECTION START -->
        <div class="ul-container">
            <section class="ul-ad">
                <div class="ul-inner-container">
                    <div class="ul-ad-content">
                        <div class="ul-ad-txt">
                            <span class="ul-ad-sub-title">Trending Products</span>
                            <h2 class="ul-section-title">Get 30% Discount On All Hudis!</h2>
                            <div class="ul-ad-categories">
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Zara</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Gucie</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Publo</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Men's</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Women's</span>
                            </div>
                        </div>

                        <div class="ul-ad-img">
                            <img src="{{ asset('assets/img/ad-img.png') }}" alt="Ad Image">
                        </div>

                        <a href="{{ route('shop') }}" class="ul-btn">Check Discount <i class="flaticon-up-right-arrow"></i></a>
                    </div>
                </div>
            </section>
        </div>
        <!-- AD SECTION END -->


        <!-- MOST SELLING START -->
        <div class="ul-container">
            <section class="ul-products ul-most-selling-products">
                <div class="ul-inner-container">
                    <div class="ul-section-heading flex-lg-row flex-column text-md-start text-center">
                        <div class="left">
                            <span class="ul-section-sub-title">most selling items</span>
                            <h2 class="ul-section-title">Top selling Categories This Week</h2>
                        </div>

                        <div class="right">
                            <div class="ul-most-sell-filter-navs">
                                <button type="button" data-filter="all">All Products</button>
                                <button type="button" data-filter=".best-selling">Best Selling</button>
                                <button type="button" data-filter=".on-selling">On Selling</button>
                                <button type="button" data-filter=".top-rating">Top Rating</button>
                            </div>
                        </div>
                    </div>

                    <!-- products grid -->
                    <div class="ul-bs-row row row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1 ul-filter-products-wrapper">
                        @foreach($horizontalProducts->take(count($filterClasses)) as $index => $product)
                            <div class="mix col {{ $filterClasses[$index] }}">
                                <x-product.horizontal-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
        <!-- MOST SELLING END -->


        <!-- VIDEO SECTION START -->
        <div class="ul-container">
            <div class="ul-video">
                <div>
                    <img src="{{ asset('assets/img/video-banner.jpg') }}" alt="Video Banner" class="ul-video-cover">
                </div>
                <a href="https://youtu.be/cNOKQIw81SE?si=iwUyBvpTD3h8DpFK" data-fslightbox="video" class="ul-video-btn"><i class="flaticon-play-button-arrowhead"></i></a>
            </div>
        </div>
        <!-- VIDEO SECTION END -->


        <!-- SUB BANNER SECTION START -->
        <div class="ul-container">
            <section class="ul-sub-banners">
                <div class="ul-inner-container">
                    <div class="row ul-bs-row row-cols-md-3 row-cols-sm-2 row-cols-1">
                        <!-- single sub banner -->
                        <div class="col">
                            <div class="ul-sub-banner ">
                                <div class="ul-sub-banner-txt">
                                    <div class="top">
                                        <span class="ul-ad-sub-title">Trending Products</span>
                                        <h3 class="ul-sub-banner-title">Women's collections</h3>
                                        <p class="ul-sub-banner-descr">Up to 22% off Gimbals</p>
                                    </div>

                                    <div class="bottom">
                                        <a href="{{ route('shop') }}" class="ul-sub-banner-btn">Collection <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div>

                                <div class="ul-sub-banner-img">
                                    <img src="{{ asset('assets/img/sub-banner-1.png') }}" alt="Sub Banner Image">
                                </div>
                            </div>
                        </div>

                        <!-- single sub banner -->
                        <div class="col">
                            <div class="ul-sub-banner ul-sub-banner--2">
                                <div class="ul-sub-banner-txt">
                                    <div class="top">
                                        <span class="ul-ad-sub-title">Trending Products</span>
                                        <h3 class="ul-sub-banner-title">Men's collections</h3>
                                        <p class="ul-sub-banner-descr">Up to 22% off Gimbals</p>
                                    </div>

                                    <div class="bottom">
                                        <a href="{{ route('shop') }}" class="ul-sub-banner-btn">Collection <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div>

                                <div class="ul-sub-banner-img">
                                    <img src="{{ asset('assets/img/sub-banner-2.png') }}" alt="Sub Banner Image">
                                </div>
                            </div>
                        </div>

                        <!-- single sub banner -->
                        <div class="col">
                            <div class="ul-sub-banner ul-sub-banner--3">
                                <div class="ul-sub-banner-txt">
                                    <div class="top">
                                        <span class="ul-ad-sub-title">Trending Products</span>
                                        <h3 class="ul-sub-banner-title">Kid's collections</h3>
                                        <p class="ul-sub-banner-descr">Up to 22% off Gimbals</p>
                                    </div>

                                    <div class="bottom">
                                        <a href="{{ route('shop') }}" class="ul-sub-banner-btn">Collection <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </div>

                                <div class="ul-sub-banner-img">
                                    <img src="{{ asset('assets/img/sub-banner-3.png') }}" alt="Sub Banner Image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- SUB BANNER SECTION END -->


        <!-- FLASH SALE SECTION START -->
        <div class="overflow-hidden">
            <div class="ul-container">
                <div class="ul-flash-sale">
                    <div class="ul-inner-container">
                        <div class="ul-section-heading ul-flash-sale-heading">
                            <div class="left">
                                <span class="ul-section-sub-title">New Collection</span>
                                <h2 class="ul-section-title">Trending Flash Sell</h2>
                            </div>

                            <div class="ul-flash-sale-countdown-wrapper">
                                <div class="ul-flash-sale-countdown">
                                    <div class="days-wrapper">
                                        <div class="days number">00</div>
                                        <span class="txt">Days</span>
                                    </div>
                                    <div class="hours-wrapper">
                                        <div class="hours number">00</div>
                                        <span class="txt">Hours</span>
                                    </div>
                                    <div class="minutes-wrapper">
                                        <div class="minutes number">00</div>
                                        <span class="txt">Min</span>
                                    </div>
                                    <div class="seconds-wrapper">
                                        <div class="seconds number">00</div>
                                        <span class="txt">Sec</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('shop') }}" class="ul-btn">View All Collection <i class="flaticon-up-right-arrow"></i></a>
                        </div>

                        <div class="ul-flash-sale-slider swiper">
                            <div class="swiper-wrapper">
                                @foreach($productSliderItems->take(10) as $product)
                                    <div class="swiper-slide">
                                        <x-product.card :product="$product" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FLASH SALE SECTION END -->


        <!-- REVIEWS SECTION START -->
        <section class="ul-reviews overflow-hidden">
            <div class="ul-section-heading text-center justify-content-center">
                <div>
                    <span class="ul-section-sub-title">Customer Reviews</span>
                    <h2 class="ul-section-title">Product Reviews</h2>
                    <p class="ul-reviews-heading-descr">Our references are very valuable, the result of a great effort...</p>
                </div>
            </div>

            <!-- slider -->
            <div class="ul-reviews-slider swiper">
                <div class="swiper-wrapper">
                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-1.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Esther Howard</h3>
                                        <span class="reviewer-role">Web Designer</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-2.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Wade Warren</h3>
                                        <span class="reviewer-role">Marketing Coordinator</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-3.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Esther Howard</h3>
                                        <span class="reviewer-role">Nursing Assistant</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-4.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">John Doe</h3>
                                        <span class="reviewer-role">Medical Assistant</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-2.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Leslie Alexander</h3>
                                        <span class="reviewer-role">Medical Assistant</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- REVIEWS SECTION END -->


        <!-- NEWSLETTER SUBSCRIPTION SECTION START -->
        <div class="ul-container">
            <section class="ul-nwsltr-subs">
                <div class="ul-inner-container">
                    <!-- heading -->
                    <div class="ul-section-heading justify-content-center text-center">
                        <div>
                            <span class="ul-section-sub-title text-white">GET NEWSLETTER</span>
                            <h2 class="ul-section-title text-white text-white">Sign Up to Newsletter</h2>
                        </div>
                    </div>

                    <!-- form -->
                    <div class="ul-nwsltr-subs-form-wrapper">
                        <div class="icon"><i class="flaticon-airplane"></i></div>
                        <form action="#" class="ul-nwsltr-subs-form">
                            <input type="email" name="nwsltr-subs-email" id="nwsltr-subs-email" placeholder="Enter Your Email">
                            <button type="submit">Subscribe Now <i class="flaticon-up-right-arrow"></i></button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <!-- NEWSLETTER SUBSCRIPTION SECTION END -->


        <!-- BLOG SECTION START -->
        <div class="ul-container">
            <section class="ul-blogs">
                <div class="ul-inner-container">
                    <!-- heading -->
                    <div class="ul-section-heading">
                        <div class="left">
                            <span class="ul-section-sub-title">News & Blog</span>
                            <h2 class="ul-section-title">Latest News & Blog</h2>
                        </div>

                        <div>
                            <a href="{{ route('blog') }}" class="ul-blogs-heading-btn">View All BLog <i class="flaticon-up-right-arrow"></i></a>
                        </div>
                    </div>

                    <!-- blog grid -->
                    <div class="row ul-bs-row row-cols-md-3 row-cols-2 row-cols-xxs-1">
                        <!-- single blog -->
                        <div class="col">
                            <div class="ul-blog">
                                <div class="ul-blog-img">
                                    <img src="{{ asset('assets/img/blog-1.jpg') }}" alt="Article Image">

                                    <div class="date">
                                        <span class="number">15</span>
                                        <span class="txt">Dec</span>
                                    </div>
                                </div>

                                <div class="ul-blog-txt">
                                    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
                                        <!-- single info -->
                                        <div class="ul-blog-info">
                                            <span class="icon"><i class="flaticon-user-2"></i></span>
                                            <span class="text font-normal text-[14px] text-etGray">By Admin</span>
                                        </div>
                                    </div>

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                    <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                    <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>
                        </div>

                        <!-- single blog -->
                        <div class="col">
                            <div class="ul-blog">
                                <div class="ul-blog-img">
                                    <img src="{{ asset('assets/img/blog-2.jpg') }}" alt="Article Image">

                                    <div class="date">
                                        <span class="number">15</span>
                                        <span class="txt">Dec</span>
                                    </div>
                                </div>

                                <div class="ul-blog-txt">
                                    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
                                        <!-- single info -->
                                        <div class="ul-blog-info">
                                            <span class="icon"><i class="flaticon-user-2"></i></span>
                                            <span class="text font-normal text-[14px] text-etGray">By Admin</span>
                                        </div>
                                    </div>

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                    <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                    <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>
                        </div>

                        <!-- single blog -->
                        <div class="col">
                            <div class="ul-blog">
                                <div class="ul-blog-img">
                                    <img src="{{ asset('assets/img/blog-3.jpg') }}" alt="Article Image">

                                    <div class="date">
                                        <span class="number">15</span>
                                        <span class="txt">Dec</span>
                                    </div>
                                </div>

                                <div class="ul-blog-txt">
                                    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
                                        <!-- single info -->
                                        <div class="ul-blog-info">
                                            <span class="icon"><i class="flaticon-user-2"></i></span>
                                            <span class="text font-normal text-[14px] text-etGray">By Admin</span>
                                        </div>
                                    </div>

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                    <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                    <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- BLOG SECTION END -->


        <!-- GALLERY SECTION START -->
        <div class="ul-gallery overflow-hidden mx-auto">
            <div class="ul-gallery-slider swiper">
                <div class="swiper-wrapper">
                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-1.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-1.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-2.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-2.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-3.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-3.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-4.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-4.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-5.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-5.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-6.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-6.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-1.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-1.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-2.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-2.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- GALLERY SECTION END -->
</x-layout.page>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/countdown.js') }}"></script>
@endpush
