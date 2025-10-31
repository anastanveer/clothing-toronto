@extends('layouts.app')

@section('title', 'Home')

@section('content')

<x-layout.page>
    @php
        $sliderCollection = collect($productSliderItems ?? []);
        $brandName = $primaryBrand->name ?? 'Toronto Textile';
        $brandTagline = $primaryBrand->tagline ?? 'Curated wardrobe essentials';
        $brandSummary = $primaryBrand->summary ?? 'Hand-finished outfits designed for life between Karachi and Toronto.';
        $brandShopUrl = $primaryBrand ? route('shop.brand', $primaryBrand->slug) : route('shop');
        $startingPrice = data_get($sliderCollection->sortBy('price_value')->first(), 'price', '$129');
        $heroSlides = [
            [
                'image' => asset('assets/img/banner-slide-1.jpg'),
                'modifier' => '',
                'subtitle' => $brandTagline,
                'title' => "{$brandName} Signature Wardrobe",
                'description' => $brandSummary,
                'price' => $startingPrice,
                'cta_label' => "Shop {$brandName}",
                'cta_url' => $brandShopUrl,
            ],
            [
                'image' => asset('assets/img/banner-slide-2.jpg'),
                'modifier' => 'ul-banner-slide--2',
                'subtitle' => "{$brandName} seasonal layers",
                'title' => 'From desert evenings to downtown strolls',
                'description' => 'Breathable fabrics and relaxed tailoring inspired by journeys between Karachi and Toronto.',
                'price' => $startingPrice,
                'cta_label' => "Browse {$brandName} outfits",
                'cta_url' => $brandShopUrl,
            ],
            [
                'image' => asset('assets/img/banner-slide-3.jpg'),
                'modifier' => 'ul-banner-slide--3',
                'subtitle' => "{$brandName} limited release",
                'title' => 'Five outfits, countless journeys',
                'description' => 'Mix, layer, and reimagine handcrafted staples shaped for nomads and celebrants alike.',
                'price' => $startingPrice,
                'cta_label' => "Reserve your {$brandName} look",
                'cta_url' => $brandShopUrl,
            ],
        ];
    @endphp
        <!-- BANNER SECTION START -->
        <div class="overflow-hidden">
            <div class="ul-container">
                <section class="ul-banner">
                    <div class="ul-banner-slider-wrapper">
                        <div class="ul-banner-slider swiper">
                            <div class="swiper-wrapper">
                                @foreach($heroSlides as $index => $slide)
                                    <div class="swiper-slide ul-banner-slide {{ $slide['modifier'] }}">
                                        <div class="ul-banner-slide-img">
                                            <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                                        </div>
                                        <div
                                            class="ul-banner-slide-txt"
                                            data-animate="fade-up"
                                            style="--animate-delay: {{ number_format($index * 0.06, 2, '.', '') }}s;"
                                        >
                                            @if(!empty($slide['subtitle']))
                                                <span class="ul-banner-slide-sub-title">{{ $slide['subtitle'] }}</span>
                                            @endif
                                            <h1 class="ul-banner-slide-title">{{ $slide['title'] }}</h1>
                                            @if(!empty($slide['description']))
                                                <p class="ul-banner-slide-descr">{{ $slide['description'] }}</p>
                                            @endif
                                            @if(!empty($slide['price']))
                                                <p class="ul-banner-slide-price">Starting From <span class="price">{{ $slide['price'] }}</span></p>
                                            @endif
                                            <a href="{{ $slide['cta_url'] }}" class="ul-btn">
                                                {{ $slide['cta_label'] }} <i class="flaticon-up-right-arrow"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
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
                                @foreach($heroSlides as $slide)
                                    <div class="swiper-slide">
                                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }} inspiration">
                                    </div>
                                @endforeach
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

                        <div class="right"><a href="{{ route('shop') }}" class="ul-btn">More Collection <i class="flaticon-up-right-arrow"></i></a></div>
                    </div>

                    @php
                        $menCategoryUrl = route('shop.category', ['category' => 'men']);
                        $womenCategoryUrl = route('shop.category', ['category' => 'women']);
                    @endphp

                    <div class="row ul-bs-row align-items-stretch g-4 g-lg-5 mb-5">
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="ul-products-sub-banner text-start">
                                <div class="ul-products-sub-banner-img">
                                    <img src="{{ asset('assets/img/products-sub-banner-1.jpg') }}" alt="Sub Banner Image">
                                </div>
                                <div class="ul-products-sub-banner-txt text-start">
                                    <h3 class="ul-products-sub-banner-title">Trending Now Only This Weekend!</h3>
                                    <a href="{{ $menCategoryUrl }}" class="ul-btn">Shop Now <i class="flaticon-up-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8 col-12">
                            <div class="swiper ul-products-slider-1">
                                <div class="swiper-wrapper">
                                    @foreach($menProductSliderItems->take(8) as $product)
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
                    </div>

                    <div class="row ul-bs-row align-items-stretch g-4 g-lg-5 flex-lg-row-reverse ul-bs-row--women mt-4">
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="ul-products-sub-banner text-start">
                                <div class="ul-products-sub-banner-img">
                                    <img src="{{ asset('assets/img/products-sub-banner-2.jpg') }}" alt="Sub Banner Image">
                                </div>
                                <div class="ul-products-sub-banner-txt text-start">
                                    <h3 class="ul-products-sub-banner-title">Trending Now Only This Weekend!</h3>
                                    <a href="{{ $womenCategoryUrl }}" class="ul-btn">Shop Now <i class="flaticon-up-right-arrow"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8 col-12">
                            <div class="swiper ul-products-slider-2" dir="rtl">
                                <div class="swiper-wrapper">
                                    @foreach($womenProductSliderItems->take(8) as $product)
                                        <div class="swiper-slide">
                                            <x-product.card :product="$product" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="ul-products-slider-nav ul-products-slider-2-nav">
                                <button class="prev"><i class="flaticon-arrow-point-to-right"></i></button>
                                <button class="next"><i class="flaticon-left-arrow"></i></button>
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
                            <span class="ul-ad-sub-title">{{ $brandName }} spotlight</span>
                            <h2 class="ul-section-title">Nomad essentials, handcrafted in limited batches</h2>
                            <div class="ul-ad-categories">
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Handloom cotton</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Desert dyes</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Travel ready</span>
                                <span class="category"><span><i class="flaticon-check-mark"></i></span>Women's</span>
                            </div>
                            <p class="ul-sub-banner-descr">{{ $brandSummary }}</p>
                        </div>

                        <div class="ul-ad-img">
                            <img src="{{ asset('assets/img/ad-img.png') }}" alt="{{ $brandName }} showcase">
                        </div>

                        <a href="{{ $brandShopUrl }}" class="ul-btn">Explore {{ $brandName }} <i class="flaticon-up-right-arrow"></i></a>
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
                                        <span class="ul-ad-sub-title">{{ $brandName }} edit</span>
                                        <h3 class="ul-sub-banner-title">Nomad co-ords</h3>
                                        <p class="ul-sub-banner-descr">Handwoven sets that move with you from souk to skyline.</p>
                                    </div>

                                    <div class="bottom">
                                        <a href="{{ $brandShopUrl }}" class="ul-sub-banner-btn">Shop the look <i class="flaticon-up-right-arrow"></i></a>
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
                                        <span class="ul-ad-sub-title">{{ $brandName }} nights</span>
                                        <h3 class="ul-sub-banner-title">Desert evening layers</h3>
                                        <p class="ul-sub-banner-descr">Indigo overcoats, charcoal kurtas, and statement shawls.</p>
                                    </div>

                                    <div class="bottom">
                                        <a href="{{ $brandShopUrl }}" class="ul-sub-banner-btn">Explore pieces <i class="flaticon-up-right-arrow"></i></a>
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
                                        <span class="ul-ad-sub-title">{{ $brandName }} accessories</span>
                                        <h3 class="ul-sub-banner-title">Carry Karachi to Toronto</h3>
                                        <p class="ul-sub-banner-descr">Hand-finished totes, wraps, and keepsakes ready for takeoff.</p>
                                    </div>

                                    <div class="bottom">
                                        <a href="{{ $brandShopUrl }}" class="ul-sub-banner-btn">Discover accents <i class="flaticon-up-right-arrow"></i></a>
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
        <section class="ul-blogs">
            <div class="ul-container">
                <div class="ul-inner-container">
                    <div class="ul-section-heading">
                        <div class="left">
                            <span class="ul-section-sub-title">latest stories</span>
                            <h2 class="ul-section-title">Fresh From The Atelier</h2>
                        </div>

                        <div class="right">
                            <a href="{{ route('blog') }}" class="ul-blogs-heading-btn">View All Blog <i class="flaticon-up-right-arrow"></i></a>
                        </div>
                    </div>

                    <div class="row ul-bs-row row-cols-md-3 row-cols-1">
                        @forelse($recentPosts as $post)
                            <div class="col">
                                <div class="ul-blog">
                                    <div class="ul-blog-img">
                                        <img src="{{ asset($post->featured_image ?? 'assets/img/blog-1.jpg') }}" alt="{{ $post->title }}">

                                        <div class="date">
                                            <span class="number">{{ optional($post->published_at)->format('d') ?? now()->format('d') }}</span>
                                            <span class="txt">{{ optional($post->published_at)->format('M') ?? now()->format('M') }}</span>
                                        </div>
                                    </div>

                                    <div class="ul-blog-txt">
                                        <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
                                            <div class="ul-blog-info">
                                                <span class="icon"><i class="flaticon-user-2"></i></span>
                                                <span class="text font-normal text-[14px] text-etGray">{{ $post->author->name ?? 'Editorial Team' }}</span>
                                            </div>
                                        </div>

                                        <h3 class="ul-blog-title"><a href="{{ route('blog.details', $post->slug ?? $post->id) }}">{{ $post->title }}</a></h3>
                                        <p class="ul-blog-descr">{{ $post->excerpt ?? Illuminate\Support\Str::limit(strip_tags($post->content), 110) }}</p>

                                        <a href="{{ route('blog.details', $post->slug ?? $post->id) }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="py-5 text-center text-muted fw-semibold">Stories are being crafted. Check back soon for editorials.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
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
