@props([
    'product' => [],
])

@php
    $data = collect($product);

    $price = $data->get('price');
    $discount = $data->get('discount');
    $originalPrice = $data->get('original_price');
    $priceValue = $data->get('price_value');
    $badges = collect($data->get('badges', []));
    $imagePath = $data->get('image');
    $gallery = collect($data->get('gallery', []));
    $imageAlt = $data->get('image_alt', 'Product Image');
    $detailsUrl = $data->get('details_url', '#');
    $category = $data->get('category');
    $categoryUrl = $data->get('category_url', '#');
    $title = $data->get('title');
    $productId = $data->get('id');
    $inWishlist = (bool) $data->get('in_wishlist');
    $inCart = (bool) $data->get('in_cart');
    $gallery = $gallery
        ->prepend($imagePath)
        ->filter()
        ->map(function ($path) {
            return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
                ? $path
                : asset($path);
        })
        ->unique()
        ->values();

    $imageUrl = $gallery->first();
    $rating = $data->get('rating');
    $ratingLabel = $data->get('rating_label');
    $reviews = $data->get('reviews');
    $colors = collect($data->get('colors', []))->filter();
    $primaryColor = $data->get('primary_color');
    $categorySlug = $category ? \Illuminate\Support\Str::slug($category) : null;
    $variantClass = $categorySlug ? 'ul-product--' . $categorySlug : null;
    $shareTitle = $data->get('share_title', $title);
    $shareUrl = $data->get('share_url', $detailsUrl);
    $shareMessage = $data->get('share_text', 'Discover this edit on Glamer: ' . ($title ?? ''));
    $encodedShareUrl = rawurlencode($shareUrl);
    $encodedShareMessage = rawurlencode(trim($shareMessage . ' ' . $shareUrl));

    if (! $imageUrl) {
        $imageUrl = asset('assets/img/product-img-1.jpg');
        $gallery = collect([$imageUrl]);
    }

    $rotationMin = 5000;
    $rotationMax = 7200;
@endphp

<div
    {{ $attributes->class([
        'ul-product',
        $variantClass => ! is_null($variantClass),
    ]) }}
    @if($categorySlug)
        data-category="{{ $categorySlug }}"
    @endif
>
    @if($price || $discount || $badges->isNotEmpty())
        <div class="ul-product-heading">
            @if($price)
                <span class="ul-product-price">{{ $price }}</span>
                @if($originalPrice && $originalPrice !== $price)
                    <span class="ul-product-price-compare">{{ $originalPrice }}</span>
                @endif
            @endif

            @if($discount)
                <span class="ul-product-discount-tag">{{ $discount }}</span>
            @endif

            @foreach($badges as $badge)
                @php
                    $label = is_array($badge) ? ($badge['label'] ?? '') : (string) $badge;
                    $class = is_array($badge) ? ($badge['class'] ?? '') : '';
                @endphp
                <span class="ul-product-discount-tag {{ $class }}">{{ $label }}</span>
            @endforeach
        </div>
    @endif

    <div
        class="ul-product-img js-product-gallery"
        data-gallery-interval-min="{{ $rotationMin }}"
        data-gallery-interval-max="{{ $rotationMax }}"
    >
        @if($category)
            <span class="ul-product-category-badge {{ $categorySlug ? 'is-' . $categorySlug : '' }}">
                {{ $category }}
            </span>
        @endif
        @foreach($gallery->take(4) as $index => $galleryImage)
            <img
                src="{{ $galleryImage }}"
                alt="{{ $imageAlt }}"
                class="{{ $index === 0 ? 'is-active' : '' }}"
                loading="lazy"
            >
        @endforeach

        <div class="ul-product-actions">
            @auth
                <form
                    action="{{ route('cart.items.store') }}"
                    method="POST"
                    class="ul-product-action js-product-action"
                    data-action="cart"
                    data-product-id="{{ $productId }}"
                    data-login-url="{{ route('login') }}"
                    data-success-label="Added to bag"
                >
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $productId }}">
                    <button
                        type="submit"
                        aria-label="Add to bag"
                        aria-pressed="{{ $inCart ? 'true' : 'false' }}"
                        class="{{ $inCart ? 'is-active' : '' }}"
                    >
                        <i class="flaticon-shopping-bag"></i>
                    </button>
                </form>

                <form
                    action="{{ route('wishlist.toggle') }}"
                    method="POST"
                    class="ul-product-action js-product-action"
                    data-action="wishlist"
                    data-product-id="{{ $productId }}"
                    data-login-url="{{ route('login') }}"
                    data-success-label="Saved to wishlist"
                    data-active-label="Removed from wishlist"
                >
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $productId }}">
                    <button
                        type="submit"
                        aria-label="Save to wishlist"
                        aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
                        class="js-wishlist-toggle {{ $inWishlist ? 'is-active' : '' }}"
                    >
                        <i class="flaticon-heart"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="ul-product-action__link" title="Sign in to add to bag">
                    <i class="flaticon-shopping-bag"></i>
                </a>
                <a href="{{ route('login') }}" class="ul-product-action__link" title="Sign in to save">
                    <i class="flaticon-heart"></i>
                </a>
            @endauth

            <div
                class="ul-product-share js-product-share"
                data-share-title="{{ e($shareTitle) }}"
                data-share-url="{{ $shareUrl }}"
                data-share-message="{{ e($shareMessage) }}"
            >
                <button type="button" class="ul-product-share__toggle js-share-toggle" aria-label="Share product" aria-haspopup="true" aria-expanded="false">
                    <i class="flaticon-social-media"></i>
                </button>
                <div class="ul-product-share__menu" role="menu">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedShareUrl }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Facebook" title="Facebook">
                        <span class="ul-product-share__abbr">FB</span>
                    </a>
                    <a href="https://www.messenger.com/share?link={{ $encodedShareUrl }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Messenger" title="Messenger">
                        <span class="ul-product-share__abbr">MS</span>
                    </a>
                    <a href="https://wa.me/?text={{ $encodedShareMessage }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on WhatsApp" title="WhatsApp">
                        <span class="ul-product-share__abbr">WA</span>
                    </a>
                    <a href="https://www.instagram.com/?url={{ $encodedShareUrl }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Instagram" title="Instagram">
                        <span class="ul-product-share__abbr">IG</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="ul-product-txt">
        @if($title)
            <h4 class="ul-product-title"><a href="{{ $detailsUrl }}">{{ $title }}</a></h4>
        @endif

        @if($category)
            <h5 class="ul-product-category"><a href="{{ $categoryUrl }}">{{ $category }}</a></h5>
        @endif

        @if($rating)
            <div class="ul-product-rating" aria-label="Rated {{ $ratingLabel }}">
                <i class="flaticon-star"></i>
                <span class="fw-semibold">{{ number_format($rating, 1) }}</span>
                @if($reviews)
                    <span class="text-muted">({{ $reviews }})</span>
                @endif
            </div>
        @endif

        @if($colors->isNotEmpty())
            <div class="ul-product-swatches" aria-hidden="true">
                @foreach($colors->take(4) as $color)
                    @php
                        $swatchClass = 'swatch-' . \Illuminate\Support\Str::slug($color);
                        $isPrimary = $primaryColor && strcasecmp($primaryColor, $color) === 0;
                    @endphp
                    <span class="ul-product-swatch {{ $swatchClass }} {{ $isPrimary ? 'is-primary' : '' }}" title="{{ $color }}"></span>
                @endforeach
                @if($colors->count() > 4)
                    <span class="ul-product-swatch more">+{{ $colors->count() - 4 }}</span>
                @endif
            </div>
        @endif

        {{ $slot }}
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.js-product-gallery').forEach((gallery) => {
                    const images = Array.from(gallery.querySelectorAll('img'));
                    if (images.length <= 1) {
                        images.forEach((image) => image.classList.add('is-active'));
                        return;
                    }

                    let index = images.findIndex((img) => img.classList.contains('is-active'));
                    if (index < 0) {
                        index = 0;
                        images[0].classList.add('is-active');
                    }

                    let timerId;

                    const showImage = (nextIndex) => {
                        images.forEach((img, i) => {
                            img.classList.toggle('is-active', i === nextIndex);
                        });
                        index = nextIndex;
                    };

                    const startCycle = () => {
                        clearInterval(timerId);
                        timerId = setInterval(() => {
                            const next = (index + 1) % images.length;
                            showImage(next);
                        }, 1800);
                    };

                    const reset = () => {
                        clearInterval(timerId);
                        showImage(0);
                    };

                    gallery.addEventListener('mouseenter', startCycle);
                    gallery.addEventListener('mouseleave', reset);
                    gallery.addEventListener('focusin', startCycle);
                    gallery.addEventListener('focusout', reset);
                });
            });
        </script>
    @endpush
@endonce
