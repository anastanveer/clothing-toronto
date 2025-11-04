@php
    $headerMetricsUrl = route('header.metrics');
    $headerNotificationsUrl = route('header.inbox');
@endphp
<header
    class="ul-header"
    data-header-metrics-url="{{ $headerMetricsUrl }}"
    data-header-alerts-url="{{ $headerNotificationsUrl }}"
>
    <div class="ul-header-top">
        <div class="ul-header-top-slider splide">
            <div class="splide__track">
                <ul class="splide__list">
                    @for ($i = 0; $i < 10; $i++)
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
    </div>

    <div class="ul-header-bottom">
        <div class="ul-container">
            <div class="ul-header-bottom-wrapper">
                <div class="header-bottom-left">
                    <div class="logo-container">
                        <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none ul-header-wordmark">
                            <span>Toronto Textile</span>
                        </a>
                    </div>

                    <div class="ul-header-search-form-wrapper flex-grow-1 flex-shrink-0">
                        <form action="#" class="ul-header-search-form">
                            <div class="dropdown-wrapper">
                                <select name="search-category" id="ul-header-search-category">
                                    <option data-placeholder="true">Select Category</option>
                                    <option value="apparel-all">All Apparel</option>
                                    <option value="summer-staples">Summer Staples</option>
                                    <option value="winter-layers">Winter Layers</option>
                                    <option value="everyday-essentials">Everyday Essentials</option>
                                    <option value="occasion-wear">Occasion Wear</option>
                                    <option value="scarves-wraps">Scarves &amp; Wraps</option>
                                </select>
                            </div>
                            <div class="ul-header-search-form-right">
                                <input type="search" name="header-search" id="ul-header-search" placeholder="Search clothing, looks, or fabrics">
                                <button type="submit"><span class="icon"><i class="flaticon-search-interface-symbol"></i></span></button>
                            </div>
                        </form>

                        <button class="ul-header-mobile-search-closer d-xxl-none"><i class="flaticon-close"></i></button>
                    </div>
                </div>

                <div class="ul-header-nav-wrapper">
                    <div class="to-go-to-sidebar-in-mobile">
                        <nav class="ul-header-nav">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop') }}">Shop</a>
                            <a href="{{ route('shop.category', 'women') }}">Women</a>
                            <a href="{{ route('shop.category', 'men') }}">Men's</a>
                            <a href="{{ route('blog') }}">Blog</a>

                        </nav>
                    </div>
                </div>

                <div class="ul-header-actions">
                    <button class="ul-header-mobile-search-opener d-xxl-none"><i class="flaticon-search-interface-symbol"></i></button>
                    @auth
                        <a href="{{ route('account.dashboard') }}" title="My account" class="ul-header-app-button"><i class="flaticon-user"></i></a>
                        @php
                            $cartItems = auth()->user()->cartItems()->with('product')->get();
                            $headerWishlistCount = auth()->user()->wishlistItems()->count();
                            $headerCartCount = $cartItems->sum('quantity');
                            $headerCartValue = $cartItems->sum('line_total');
                            $loyaltySummary = \App\Support\Loyalty::summarize((float) auth()->user()->orders()->sum('total'), (float) $headerCartValue);
                        @endphp
                        @php
                            $wishlistScreenReaderLabel = match (true) {
                                $headerWishlistCount === 1 => '1 item in wishlist',
                                $headerWishlistCount > 1 => $headerWishlistCount . ' items in wishlist',
                                default => 'Wishlist is empty',
                            };
                            $cartScreenReaderLabel = match (true) {
                                $headerCartCount === 1 => '1 item in your bag',
                                $headerCartCount > 1 => $headerCartCount . ' items in your bag',
                                default => 'Bag is empty',
                            };
                        @endphp
                        <a
                            href="{{ route('wishlist') }}"
                            title="Wishlist"
                            class="ul-header-icon ul-header-icon--wishlist {{ $headerWishlistCount ? 'is-active' : '' }}"
                            data-header-wishlist
                            data-count="{{ $headerWishlistCount }}"
                            class="ul-header-icon ul-header-icon--wishlist {{ $headerWishlistCount ? 'is-active' : '' }} ul-header-app-button"
                        >
                            <i class="flaticon-heart"></i>
                            @if($headerWishlistCount)
                                <span class="ul-header-icon__dot" aria-hidden="true"></span>
                            @endif
                            <span class="visually-hidden js-wishlist-label">{{ $wishlistScreenReaderLabel }}</span>
                        </a>
                        <a
                            href="{{ route('account.dashboard') }}"
                            title="Loyalty status"
                            class="ul-header-loyalty"
                            data-header-loyalty
                            data-points="{{ $loyaltySummary['loyaltyPoints'] }}"
                            data-pending="{{ $loyaltySummary['cartPoints'] }}"
                        class="ul-header-loyalty ul-header-app-button"
                        >
                            <span class="ul-header-loyalty__icon"><i class="flaticon-star"></i></span>
                            <span class="ul-header-loyalty__meter">
                                <strong>{{ number_format($loyaltySummary['loyaltyPoints']) }} pts</strong>
                                @if($loyaltySummary['cartPoints'] > 0)
                                    <span class="ul-header-loyalty__pending">+{{ number_format($loyaltySummary['cartPoints']) }} pending</span>
                                @endif
                            </span>
                        </a>
                        <button
                            type="button"
                            class="ul-header-alerts-toggle"
                            data-header-alerts
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-label="Open Glamer alerts"
                            class="ul-header-alerts-toggle ul-header-app-button"
                        >
                            <span class="ul-header-alerts-toggle__icon"><i class="flaticon-star-2"></i></span>
                            <span class="ul-header-alerts-toggle__dot is-idle" data-alerts-dot aria-hidden="true"></span>
                        </button>
                        <a
                            href="{{ route('cart') }}"
                            title="Shopping bag"
                            class="ul-header-icon {{ $headerCartCount ? 'has-badge' : '' }}"
                            data-header-cart
                            data-count="{{ $headerCartCount }}"
                            class="ul-header-icon {{ $headerCartCount ? 'has-badge' : '' }} ul-header-app-button"
                        >
                            <i class="flaticon-shopping-bag"></i>
                            @if($headerCartCount)
                                <span class="ul-header-icon__badge">{{ $headerCartCount }}</span>
                            @endif
                            <span class="visually-hidden js-cart-label">{{ $cartScreenReaderLabel }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" title="Sign in" class="ul-header-app-button"><i class="flaticon-user"></i></a>
                        <a
                            href="{{ route('login') }}"
                            title="Wishlist"
                            class="ul-header-icon ul-header-icon--wishlist"
                            data-header-wishlist
                            data-count="0"
                            class="ul-header-icon ul-header-icon--wishlist ul-header-app-button"
                        >
                            <i class="flaticon-heart"></i>
                            <span class="visually-hidden js-wishlist-label">Wishlist — sign in to save favourites</span>
                        </a>
                        <a
                            href="{{ route('login') }}"
                            title="Loyalty status"
                            class="ul-header-loyalty"
                            data-header-loyalty
                            data-points="0"
                            data-pending="0"
                        class="ul-header-loyalty ul-header-app-button"
                        >
                            <span class="ul-header-loyalty__icon"><i class="flaticon-star"></i></span>
                            <span class="ul-header-loyalty__meter">
                                <strong>0 pts</strong>
                                <span class="ul-header-loyalty__pending">Join &amp; earn</span>
                            </span>
                        </a>
                        <button
                            type="button"
                            class="ul-header-alerts-toggle"
                            data-header-alerts
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-label="Open Glamer alerts"
                            class="ul-header-alerts-toggle ul-header-app-button"
                        >
                            <span class="ul-header-alerts-toggle__icon"><i class="flaticon-star-2"></i></span>
                            <span class="ul-header-alerts-toggle__dot is-idle" data-alerts-dot aria-hidden="true"></span>
                        </button>
                        <a
                            href="{{ route('login') }}"
                            title="Shopping bag"
                            class="ul-header-icon"
                            data-header-cart
                            data-count="0"
                            class="ul-header-icon ul-header-app-button"
                        >
                            <i class="flaticon-shopping-bag"></i>
                            <span class="visually-hidden js-cart-label">Bag is empty</span>
                        </a>
                    @endauth
                </div>

                <div class="d-inline-flex">
                    <button class="ul-header-sidebar-opener" type="button" aria-label="Open menu">
                        <i class="flaticon-hamburger"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="ul-header-alerts" data-header-alerts-panel hidden>
    <div class="ul-header-alerts__backdrop" data-header-alerts-close></div>
    <div class="ul-header-alerts__drawer" role="dialog" aria-modal="true" aria-labelledby="alertsTitle">
        <div class="ul-header-alerts__header">
            <div>
                <span class="ul-header-alerts__eyebrow">Glamer alerts</span>
                <h3 class="ul-header-alerts__title" id="alertsTitle">Fresh perks &amp; stories</h3>
            </div>
            <button type="button" class="ul-header-alerts__close" data-header-alerts-close aria-label="Close alerts">
                <i class="flaticon-close"></i>
            </button>
        </div>
        <div class="ul-header-alerts__body">
            <div class="ul-header-alerts__loading" data-header-alerts-loading>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="ms-2">Curating your perks…</span>
            </div>
            <div class="ul-header-alerts__content" data-header-alerts-content hidden>
                <section class="ul-header-alerts-section" data-alerts-section="coupons">
                    <header>
                        <span class="ul-header-alerts-section__eyebrow">Your perks</span>
                        <h4 class="ul-header-alerts-section__title">Exclusive coupons</h4>
                    </header>
                    <div class="ul-header-alerts-perks" data-alerts-list="coupons"></div>
                </section>

                <section class="ul-header-alerts-section" data-alerts-section="products">
                    <header>
                        <span class="ul-header-alerts-section__eyebrow">Under budget</span>
                        <h4 class="ul-header-alerts-section__title">Smart picks</h4>
                    </header>
                    <div class="ul-header-alerts-products" data-alerts-list="products"></div>
                </section>

                <section class="ul-header-alerts-section" data-alerts-section="articles">
                    <header>
                        <span class="ul-header-alerts-section__eyebrow">Latest reads</span>
                        <h4 class="ul-header-alerts-section__title">New on the journal</h4>
                    </header>
                    <div class="ul-header-alerts-articles" data-alerts-list="articles"></div>
                </section>
            </div>
        </div>
    </div>
</div>
