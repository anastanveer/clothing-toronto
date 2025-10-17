@php
    $alertsUrl = route('header.inbox');
@endphp
<nav class="ul-footer-appbar d-lg-none" data-mobile-appbar>
    @auth
        @php
            $cartItems = auth()->user()->cartItems()->with('product')->get();
            $footerWishlistCount = auth()->user()->wishlistItems()->count();
            $footerCartCount = $cartItems->sum('quantity');
            $footerCartValue = $cartItems->sum('line_total');
            $footerLoyaltySummary = \App\Support\Loyalty::summarize((float) auth()->user()->orders()->sum('total'), (float) $footerCartValue);

            $wishlistLabel = match (true) {
                $footerWishlistCount === 1 => '1 item in wishlist',
                $footerWishlistCount > 1 => $footerWishlistCount . ' items in wishlist',
                default => 'Wishlist is empty',
            };
            $cartLabel = match (true) {
                $footerCartCount === 1 => '1 item in your bag',
                $footerCartCount > 1 => $footerCartCount . ' items in your bag',
                default => 'Bag is empty',
            };
        @endphp
        <a href="{{ route('account.dashboard') }}" class="ul-footer-appbar__btn" aria-label="Account">
            <i class="flaticon-user"></i>
            <span>Account</span>
        </a>
        <a
            href="{{ route('wishlist') }}"
            class="ul-footer-appbar__btn"
            aria-label="Wishlist"
            data-header-wishlist
            data-count="{{ $footerWishlistCount }}"
        >
            <i class="flaticon-heart"></i>
            @if($footerWishlistCount)
                <span class="ul-footer-appbar__badge">{{ $footerWishlistCount }}</span>
            @endif
            <span>Wishlist</span>
            <span class="visually-hidden js-wishlist-label">{{ $wishlistLabel }}</span>
        </a>
        <a
            href="{{ route('account.dashboard') }}"
            class="ul-footer-appbar__btn"
            aria-label="Loyalty"
            data-header-loyalty
            data-points="{{ $footerLoyaltySummary['loyaltyPoints'] }}"
            data-pending="{{ $footerLoyaltySummary['cartPoints'] }}"
        >
            <i class="flaticon-star"></i>
            <span>Loyalty</span>
        </a>
        <button
            type="button"
            class="ul-footer-appbar__btn"
            data-header-alerts
            data-alerts-url="{{ $alertsUrl }}"
            aria-label="Alerts"
            aria-expanded="false"
        >
            <i class="flaticon-star-2"></i>
            <span>Alerts</span>
            <span class="ul-footer-appbar__dot is-idle" data-alerts-dot aria-hidden="true"></span>
        </button>
        <a
            href="{{ route('cart') }}"
            class="ul-footer-appbar__btn"
            aria-label="Bag"
            data-header-cart
            data-count="{{ $footerCartCount }}"
        >
            <i class="flaticon-shopping-bag"></i>
            @if($footerCartCount)
                <span class="ul-footer-appbar__badge">{{ $footerCartCount }}</span>
            @endif
            <span>Bag</span>
            <span class="visually-hidden js-cart-label">{{ $cartLabel }}</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="ul-footer-appbar__btn" aria-label="Sign in">
            <i class="flaticon-user"></i>
            <span>Sign in</span>
        </a>
        <a
            href="{{ route('login') }}"
            class="ul-footer-appbar__btn"
            aria-label="Wishlist"
            data-header-wishlist
            data-count="0"
        >
            <i class="flaticon-heart"></i>
            <span>Wishlist</span>
            <span class="visually-hidden js-wishlist-label">Wishlist — sign in to save favourites</span>
        </a>
        <a
            href="{{ route('login') }}"
            class="ul-footer-appbar__btn"
            aria-label="Loyalty"
            data-header-loyalty
            data-points="0"
            data-pending="0"
        >
            <i class="flaticon-star"></i>
            <span>Loyalty</span>
        </a>
        <button
            type="button"
            class="ul-footer-appbar__btn"
            data-header-alerts
            data-alerts-url="{{ $alertsUrl }}"
            aria-label="Alerts"
            aria-expanded="false"
        >
            <i class="flaticon-star-2"></i>
            <span>Alerts</span>
            <span class="ul-footer-appbar__dot is-idle" data-alerts-dot aria-hidden="true"></span>
        </button>
        <a
            href="{{ route('login') }}"
            class="ul-footer-appbar__btn"
            aria-label="Bag"
            data-header-cart
            data-count="0"
        >
            <i class="flaticon-shopping-bag"></i>
            <span>Bag</span>
            <span class="visually-hidden js-cart-label">Bag is empty</span>
        </a>
    @endauth
</nav>
