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
    $imageAlt = $data->get('image_alt', 'Product Image');
    $detailsUrl = $data->get('details_url', '#');
    $category = $data->get('category');
    $categoryUrl = $data->get('category_url', '#');
    $title = $data->get('title');
    $productId = $data->get('id');
    $inWishlist = (bool) $data->get('in_wishlist');
    $inCart = (bool) $data->get('in_cart');
    $imageUrl = $imagePath;
    $rating = $data->get('rating');
    $ratingLabel = $data->get('rating_label');
    $reviews = $data->get('reviews');
    $colors = collect($data->get('colors', []))->filter();
    $primaryColor = $data->get('primary_color');
    $shareTitle = $data->get('share_title', $title);
    $shareUrl = $data->get('share_url', $detailsUrl);
    $shareMessage = $data->get('share_text', 'Discover this edit on Glamer: ' . ($title ?? '')); 
    $encodedShareUrl = rawurlencode($shareUrl);
    $encodedShareMessage = rawurlencode(trim($shareMessage . ' ' . $shareUrl));

    if ($imagePath && ! preg_match('#^(https?:)?//#', $imagePath)) {
        $imageUrl = asset($imagePath);
    }
@endphp

<div {{ $attributes->class('ul-product') }}>
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

    <div class="ul-product-img">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
        @endif

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
