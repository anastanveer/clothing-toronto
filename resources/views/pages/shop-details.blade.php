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
    $inWishlist = auth()->check() ? auth()->user()->wishlistItems()->where('product_id', $product->id)->exists() : false;
    $cartQuantity = auth()->check() ? (int) auth()->user()->cartItems()->where('product_id', $product->id)->sum('quantity') : 0;
    $cartQuantity = max(1, $cartQuantity);
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
                            @php
                                $averageRating = round($product->average_rating ?? 0, 1);
                                $reviewsCount = (int) ($product->reviews_count ?? 0);
                                $ratingCopy = $averageRating > 0
                                    ? $averageRating . ' / 5' . ($reviewsCount ? ' · ' . $reviewsCount . ' review' . ($reviewsCount === 1 ? '' : 's') : '')
                                    : 'Be the first to review';
                                $colorPalette = collect($product->options['colors'] ?? [])
                                    ->push($product->primary_color)
                                    ->filter()
                                    ->unique()
                                    ->values();
                                $colorSwatches = $colorPalette->map(function ($label) {
                                    $label = trim((string) $label);
                                    $map = [
                                        'Black' => '#111827',
                                        'White' => '#ffffff',
                                        'Olive' => '#4d7c0f',
                                        'Stone' => '#a8a29e',
                                        'Sand' => '#f5deb3',
                                        'Navy' => '#1e3a8a',
                                        'Blush' => '#f9a8d4',
                                        'Burgundy' => '#7f1d1d',
                                        'Emerald' => '#047857',
                                        'Mustard' => '#d97706',
                                        'Green' => '#15803d',
                                        'Blue' => '#2563eb',
                                        'Red' => '#ef4444',
                                    ];

                                    return [
                                        'label' => $label,
                                        'value' => $map[$label] ?? '#e2e8f0',
                                    ];
                                });
                            @endphp

                            <div class="ul-product-details-rating d-flex align-items-center gap-3">
                                <span class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="flaticon-star {{ $i <= round($averageRating) ? '' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </span>
                                <span class="text-secondary">{{ $ratingCopy }}</span>
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

                                @if($colorSwatches->isNotEmpty())
                                    <div class="ul-product-details-option ul-product-details-colors">
                                        <span class="title">Color</span>
                                        <form action="#" class="variants">
                                            @foreach($colorSwatches as $index => $color)
                                                @php $colorId = 'ul-product-details-color-' . $index; @endphp
                                                <label for="{{ $colorId }}">
                                                    <input type="radio" name="product-color" id="{{ $colorId }}" @checked($loop->first) hidden>
                                                    <span class="color" style="background: {{ $color['value'] }}"></span>
                                                    <span class="visually-hidden">{{ $color['label'] }}</span>
                                                </label>
                                            @endforeach
                                        </form>
                                    </div>
                                @endif

                                <div class="ul-product-details-option ul-product-details-quantity">
                                    <span class="title">Quantity</span>
                                    <div class="ul-product-quantity-wrapper">
                                        <input type="number" name="quantity" id="ul-product-details-quantity" class="ul-product-quantity" value="{{ $cartQuantity }}" min="1" max="10" form="product-detail-add-to-cart">
                                        <div class="btns">
                                            <button type="button" class="quantityIncreaseButton"><i class="flaticon-plus"></i></button>
                                            <button type="button" class="quantityDecreaseButton"><i class="flaticon-minus-sign"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ul-product-details-actions">
                                <form
                                    action="{{ route('cart.items.store') }}"
                                    method="POST"
                                    class="ul-product-details-action js-product-action"
                                    id="product-detail-add-to-cart"
                                    data-action="cart"
                                    data-product-id="{{ $product->id }}"
                                    data-login-url="{{ route('login') }}"
                                    data-success-label="Added to bag"
                                >
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="ul-btn ul-btn--primary">
                                        <span class="icon"><i class="flaticon-shopping-bag"></i></span>
                                        <span>Add to bag</span>
                                    </button>
                                </form>

                                <form
                                    action="{{ route('wishlist.toggle') }}"
                                    method="POST"
                                    class="ul-product-details-action js-product-action"
                                    data-action="wishlist"
                                    data-product-id="{{ $product->id }}"
                                    data-login-url="{{ route('login') }}"
                                    data-success-label="Saved to wishlist"
                                    data-active-label="Removed from wishlist"
                                    data-label-active="Wishlist saved"
                                    data-label-inactive="Add to wishlist"
                                >
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" aria-pressed="{{ $inWishlist ? 'true' : 'false' }}" class="ul-btn ul-btn--ghost {{ $inWishlist ? 'is-active' : '' }}">
                                        <span class="icon"><i class="flaticon-heart"></i></span>
                                        <span data-button-label>{{ $inWishlist ? 'Wishlist saved' : 'Add to wishlist' }}</span>
                                    </button>
                                </form>

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
