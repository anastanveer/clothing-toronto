@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)

@section('content')
@php
    $galleryImages = $product->gallery_images ?? [];
    if (empty($galleryImages) && $product->featured_image) {
        $galleryImages = [$product->featured_image];
    }
    $displayPrice = $product->sale_price ?? $product->price;
    $discountPercent = $product->sale_price && $product->sale_price < $product->price
        ? round((1 - ($product->sale_price / $product->price)) * 100)
        : null;
@endphp

<x-layout.page>
    <x-page.header
        :title="$product->name"
        :subtitle="$product->meta_description"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Shop', 'url' => route('shop')],
            ['label' => ucfirst($product->category ?? 'collection'), 'url' => route('shop.category', $product->category ?? 'men')],
            ['label' => $product->name, 'is_current' => true],
        ]"
    />

    <div class="ul-inner-page-container">
        <div class="ul-product-details">
            <div class="ul-product-details-top">
                <div class="row ul-bs-row row-cols-lg-2 row-cols-1 align-items-center">
                    <div class="col">
                        <div class="ul-product-details-img">
                            <div class="ul-product-details-img-slider swiper">
                                <div class="swiper-wrapper">
                                    @forelse($galleryImages as $image)
                                        <div class="swiper-slide"><img src="{{ asset($image) }}" alt="{{ $product->name }}"></div>
                                    @empty
                                        <div class="swiper-slide"><img src="{{ asset('assets/img/product-details-1.jpg') }}" alt="{{ $product->name }}"></div>
                                    @endforelse
                                </div>

                                <div class="ul-product-details-img-slider-nav" id="ul-product-details-img-slider-nav">
                                    <button class="prev"><i class="flaticon-left-arrow"></i></button>
                                    <button class="next"><i class="flaticon-arrow-point-to-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="ul-product-details-txt">
                            <div class="ul-product-details-rating d-flex align-items-center gap-3">
                                <span class="rating">
                                    <i class="flaticon-star"></i>
                                    <i class="flaticon-star"></i>
                                    <i class="flaticon-star"></i>
                                    <i class="flaticon-star"></i>
                                    <i class="flaticon-star"></i>
                                </span>
                                <span class="text-secondary">Hand-finished quality</span>
                            </div>

                            <div class="d-flex align-items-baseline gap-3 mt-2">
                                <span class="ul-product-details-price">${{ number_format($displayPrice, 2) }}</span>
                                @if($discountPercent)
                                    <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                    <span class="badge bg-danger-subtle text-danger fw-semibold">Save {{ $discountPercent }}%</span>
                                @endif
                            </div>

                            <h3 class="ul-product-details-title mt-3">{{ $product->name }}</h3>

                            <p class="ul-product-details-descr">{{ $product->summary ?? 'Tailored for confident silhouettes and effortless layering.' }}</p>

                            <div class="ul-product-details-options">
                                <div class="ul-product-details-option ul-product-details-sizes">
                                    <span class="title">Size</span>
                                    <form action="#" class="variants">
                                        @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                            @php $inputId = 'ul-product-details-size-' . $loop->index; @endphp
                                            <label for="{{ $inputId }}">
                                                <input type="radio" name="product-size" id="{{ $inputId }}" @checked($loop->first) hidden>
                                                <span class="size-btn">{{ $size }}</span>
                                            </label>
                                        @endforeach
                                    </form>
                                </div>

                                <div class="ul-product-details-option ul-product-details-colors">
                                    <span class="title">Color</span>
                                    <form action="#" class="variants">
                                        @foreach(['#18181b', '#d1d5db', '#a855f7', '#ea580c'] as $index => $color)
                                            @php $colorId = 'ul-product-details-color-' . $index; @endphp
                                            <label for="{{ $colorId }}">
                                                <input type="radio" name="product-color" id="{{ $colorId }}" @checked($loop->first) hidden>
                                                <span class="color" style="background: {{ $color }}"></span>
                                            </label>
                                        @endforeach
                                    </form>
                                </div>

                                <div class="ul-product-details-option ul-product-details-quantity">
                                    <span class="title">Quantity</span>
                                    <form action="#" class="ul-product-quantity-wrapper">
                                        <input type="number" name="product-quantity" id="ul-product-details-quantity" class="ul-product-quantity" value="1" min="1" readonly>
                                        <div class="btns">
                                            <button type="button" class="quantityIncreaseButton"><i class="flaticon-plus"></i></button>
                                            <button type="button" class="quantityDecreaseButton"><i class="flaticon-minus-sign"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="ul-product-details-actions">
                                <button class="add-to-cart"><span class="icon"><i class="flaticon-shopping-bag"></i></span> Add to cart</button>
                                <button class="add-to-wishlist"><span class="icon"><i class="flaticon-heart"></i></span> Add to wishlist</button>
                                <div class="share-options">
                                    <button><i class="flaticon-facebook-app-symbol"></i></button>
                                    <button><i class="flaticon-twitter"></i></button>
                                    <button><i class="flaticon-linkedin-big-logo"></i></button>
                                    <a href="#"><i class="flaticon-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ul-product-details-bottom">
                <div class="ul-product-details-long-descr-wrapper">
                    <h3 class="ul-product-details-inner-title">Item Description</h3>
                    <p>{!! nl2br(e($product->description ?? 'A signature wardrobe essential crafted to elevate any ensemble. Pair with relaxed tailoring or bold accessories for a complete look.')) !!}</p>
                </div>

                <div class="ul-product-details-reviews">
                    <h3 class="ul-product-details-inner-title">Styling Notes</h3>
                    <div class="ul-product-details-review">
                        <div class="ul-product-details-review-txt w-100">
                            <p class="mb-0">Layer with soft tailoring, finish with sculpted accessories, and keep the palette tonal for the full Glamer effect. Update the look each season by swapping in statement footwear or textural outerwear.</p>
                        </div>
                    </div>
                </div>

                <div class="ul-product-details-review-form-wrapper">
                    <h3 class="ul-product-details-inner-title">Care Instructions</h3>
                    <ul class="mb-0 text-secondary">
                        <li>Dry clean for best longevity.</li>
                        <li>Steam to refresh and maintain structure.</li>
                        <li>Store on padded hangers away from direct light.</li>
                    </ul>
                </div>
            </div>
        </div>

        <section class="ul-products ul-products--related mt-5">
            <h2 class="ul-section-title text-center mb-4">You may also like</h2>
            <div class="swiper ul-flash-sale-slider">
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $related)
                        <div class="swiper-slide">
                            <x-product.card :product="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layout.page>
@endsection
