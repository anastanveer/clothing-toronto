<div class="ul-sidebar">
    <div class="ul-sidebar-header">
        <div class="ul-sidebar-header-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/logo.svg') }}" alt="logo" class="logo">
            </a>
        </div>
        <button class="ul-sidebar-closer"><i class="flaticon-close"></i></button>
    </div>

    <div class="ul-sidebar-header-nav-wrapper d-block d-lg-none"></div>

    <div class="ul-sidebar-about d-none d-lg-block">
        <span class="title">About Toronto Textile</span>
        <p class="mb-0">Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra velit.</p>
    </div>

    @php
        $sidebarProducts = \App\Models\Product::query()
            ->where('status', 'published')
            ->latest('created_at')
            ->with('brand')
            ->take(4)
            ->get();
    @endphp

    <div class="ul-sidebar-products-wrapper d-none d-lg-flex">
        <div class="ul-sidebar-products-slider swiper">
            <div class="swiper-wrapper">
                @forelse ($sidebarProducts as $product)
                    @php
                        $detailsUrl = route('shop.details', ['slug' => $product->slug ?? $product->id]);
                        $imagePath = $product->featured_image ?: null;
                        if ($imagePath) {
                            if (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])) {
                                $imagePath = $imagePath;
                            } elseif (\Illuminate\Support\Str::startsWith($imagePath, ['storage/', 'assets/', 'img/', 'images/'])) {
                                $imagePath = asset($imagePath);
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                                $imagePath = asset('storage/' . ltrim($imagePath, '/'));
                            } else {
                                $imagePath = asset($imagePath);
                            }
                        } else {
                            $imagePath = asset('assets/img/product-img-1.jpg');
                        }
                        $categorySlug = $product->category;
                        $categoryLabel = $categorySlug ? \Illuminate\Support\Str::headline($categorySlug) : 'Shop now';
                        $categoryUrl = $categorySlug ? route('shop.category', $categorySlug) : route('shop');
                        $basePrice = (float) ($product->price ?? 0);
                        $baseSale = $product->sale_price ? (float) $product->sale_price : null;
                        $hasSale = $baseSale && $baseSale > 0 && $baseSale < $basePrice;
                        $displayBasePrice = $hasSale ? $baseSale : $basePrice;
                        $priceLabel = $displayBasePrice > 0 ? \App\Support\Money::format($displayBasePrice) : null;
                        $discountPercent = $hasSale
                            ? round((($basePrice - $baseSale) / $basePrice) * 100)
                            : null;
                        $discountLabel = $discountPercent ? $discountPercent . '% Off' : null;
                        $shareUrl = $detailsUrl;
                        $shareMessage = 'Discover this Toronto Textile find: ' . $product->name;
                        $encodedSidebarShare = rawurlencode($shareUrl);
                        $encodedSidebarMessage = rawurlencode($shareMessage . ' ' . $shareUrl);
                    @endphp
                    <div class="swiper-slide">
                        <div class="ul-product">
                            <div class="ul-product-heading">
                                @if ($priceLabel)
                                    <span class="ul-product-price">{{ $priceLabel }}</span>
                                @endif
                                @if ($discountLabel)
                                    <span class="ul-product-discount-tag">{{ $discountLabel }}</span>
                                @endif
                            </div>

                            <div class="ul-product-img">
                                <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="is-active" loading="lazy">
                                <div class="ul-product-actions">
                                    <a href="{{ route('cart') }}" class="ul-product-action__link" title="View bag"><i class="flaticon-shopping-bag"></i></a>
                                    <a href="{{ route('wishlist') }}" class="ul-product-action__link" title="Wishlist"><i class="flaticon-heart"></i></a>
                                    <div
                                        class="ul-product-share js-product-share"
                                        data-share-title="Toronto Textile Drop"
                                        data-share-url="{{ $shareUrl }}"
                                        data-share-message="Discover this Toronto Textile find"
                                    >
                                        <button type="button" class="ul-product-share__toggle js-share-toggle" aria-label="Share" aria-expanded="false">
                                            <i class="flaticon-social-media"></i>
                                        </button>
                                        <div class="ul-product-share__menu" role="menu">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedSidebarShare }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Facebook" title="Facebook">
                                                <span class="ul-product-share__abbr">FB</span>
                                            </a>
                                            <a href="https://www.messenger.com/share?link={{ $encodedSidebarShare }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Messenger" title="Messenger">
                                                <span class="ul-product-share__abbr">MS</span>
                                            </a>
                                            <a href="https://wa.me/?text={{ $encodedSidebarMessage }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on WhatsApp" title="WhatsApp">
                                                <span class="ul-product-share__abbr">WA</span>
                                            </a>
                                            <a href="https://www.instagram.com/?url={{ $encodedSidebarShare }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Instagram" title="Instagram">
                                                <span class="ul-product-share__abbr">IG</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ul-product-txt">
                                <h4 class="ul-product-title"><a href="{{ $detailsUrl }}">{{ $product->name }}</a></h4>
                                {{-- Category link hidden for now --}}
                            </div>
                        </div>
                    </div>
                @empty
                    @foreach ([1, 2, 3] as $img)
                        <div class="swiper-slide">
                            <div class="ul-product">
                                <div class="ul-product-heading">
                                    <span class="ul-product-price">$99.00</span>
                                    <span class="ul-product-discount-tag">25% Off</span>
                                </div>

                                <div class="ul-product-img">
                                    <img src="{{ asset('assets/img/product-img-' . $img . '.jpg') }}" alt="Product Image" class="is-active" loading="lazy">
                                    @php
                                        $shareUrl = route('shop');
                                        $encodedSidebarShare = rawurlencode($shareUrl);
                                        $encodedSidebarMessage = rawurlencode('Discover this Toronto Textile find: ' . $shareUrl);
                                    @endphp
                                    <div class="ul-product-actions">
                                        <a href="{{ route('cart') }}" class="ul-product-action__link" title="View bag"><i class="flaticon-shopping-bag"></i></a>
                                        <a href="{{ route('wishlist') }}" class="ul-product-action__link" title="Wishlist"><i class="flaticon-heart"></i></a>
                                        <div
                                            class="ul-product-share js-product-share"
                                            data-share-title="Toronto Textile Drop"
                                            data-share-url="{{ $shareUrl }}"
                                            data-share-message="Discover this Toronto Textile find"
                                        >
                                            <button type="button" class="ul-product-share__toggle js-share-toggle" aria-label="Share" aria-expanded="false">
                                                <i class="flaticon-social-media"></i>
                                            </button>
                                            <div class="ul-product-share__menu" role="menu">
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedSidebarShare }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Facebook" title="Facebook">
                                                    <span class="ul-product-share__abbr">FB</span>
                                                </a>
                                                <a href="https://www.messenger.com/share?link={{ $encodedSidebarShare }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Messenger" title="Messenger">
                                                    <span class="ul-product-share__abbr">MS</span>
                                                </a>
                                                <a href="https://wa.me/?text={{ $encodedSidebarMessage }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on WhatsApp" title="WhatsApp">
                                                    <span class="ul-product-share__abbr">WA</span>
                                                </a>
                                                <a href="https://www.instagram.com/?url={{ $encodedSidebarShare }}" target="_blank" rel="noopener" role="menuitem" aria-label="Share on Instagram" title="Instagram">
                                                    <span class="ul-product-share__abbr">IG</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ul-product-txt">
                                    <h4 class="ul-product-title"><a href="{{ route('shop.details') }}">Orange Airsuit</a></h4>
                                    {{-- Category link hidden for now --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>

        <div class="ul-sidebar-products-slider-nav flex-shrink-0">
            <button class="prev"><i class="flaticon-left-arrow"></i></button>
            <button class="next"><i class="flaticon-arrow-point-to-right"></i></button>
        </div>
    </div>

    <div class="ul-sidebar-about d-none d-lg-block">
        <p class="mb-0">Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra velit.</p>
    </div>

    <div class="ul-sidebar-footer">
        <span class="ul-sidebar-footer-title">Follow us</span>
        <div class="ul-sidebar-footer-social">
            <a href="#"><i class="flaticon-facebook-app-symbol"></i></a>
            <a href="#"><i class="flaticon-twitter"></i></a>
            <a href="#"><i class="flaticon-instagram"></i></a>
            <a href="#"><i class="flaticon-youtube"></i></a>
        </div>
    </div>
</div>
