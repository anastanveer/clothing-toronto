<header class="kb-header">
  <div class="container py-2">
    @php
      $primaryCategories = $catalogCategories['primary'] ?? [];
      $accessoryCategories = $catalogCategories['accessories'] ?? [];
      $enabledBrands = collect($catalogBrands ?? [])
          ->filter(fn ($brand) => !empty($brand['enabled']))
          ->all();
      $currentBrandKey = request()->route('brand');
      $useBrandMenu = $currentBrandKey && isset($enabledBrands[$currentBrandKey]);
      $showKhanabadoshLogo = $currentBrandKey === 'khanabadosh';
    @endphp
    <div class="d-flex align-items-center justify-content-between">

      <div class="d-flex align-items-center gap-3">
        <a class="kb-brand" href="{{ route('home') }}">
          @if ($showKhanabadoshLogo)
            <img src="{{ asset('assets/brand/logo.avif') }}" alt="Khanabadosh logo">
          @else
            <span class="kb-logo-text" aria-label="Toronto Textile">
              <span class="kb-logo-word">Toronto</span>
              <span class="kb-logo-word kb-logo-word--accent">Textile</span>
            </span>
          @endif
        </a>
      </div>

      <nav class="kb-nav" aria-label="Main" id="kb-mobile-nav">
        <div class="kb-nav-mobile-head">
          <span class="kb-nav-mobile-title">Menu</span>
          <button class="kb-nav-close" type="button" aria-label="Close menu" data-nav-close>&times;</button>
        </div>
        <ul class="kb-menu">
          <li><a class="kb-nav-link" href="{{ route('home') }}">Home</a></li>
          <li class="kb-dropdown">
            <a class="kb-nav-link" href="{{ $useBrandMenu
                ? route('brands.collections.show', ['brand' => $currentBrandKey, 'slug' => $primaryCategories[0]['slug'] ?? 'men-all'])
                : route('collections.show', ['slug' => $primaryCategories[0]['slug'] ?? 'men-all']) }}">
              <span>Shop</span>
              <i class="bi bi-chevron-down kb-nav-caret" aria-hidden="true"></i>
            </a>
            <div class="kb-dropdown-menu" aria-label="Shop">
              <ul class="kb-dropdown-list">
                @foreach ($primaryCategories as $category)
                  <li>
                    <a href="{{ $useBrandMenu
                        ? route('brands.collections.show', ['brand' => $currentBrandKey, 'slug' => $category['slug']])
                        : route('collections.show', ['slug' => $category['slug']]) }}">{{ $category['label'] }}</a>
                  </li>
                @endforeach
                @if (!empty($accessoryCategories))
                  <li class="has-submenu">
                    <a href="{{ $useBrandMenu
                        ? route('brands.collections.show', ['brand' => $currentBrandKey, 'slug' => 'accessories'])
                        : route('collections.show', ['slug' => 'accessories']) }}">
                      <span>Accessories</span>
                      <span class="arrow">&gt;</span>
                    </a>
                    <ul class="kb-submenu">
                      @foreach ($accessoryCategories as $category)
                        <li>
                          <a href="{{ $useBrandMenu
                              ? route('brands.collections.show', ['brand' => $currentBrandKey, 'slug' => $category['slug']])
                              : route('collections.show', ['slug' => $category['slug']]) }}">{{ $category['label'] }}</a>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                @endif
              </ul>
            </div>
          </li>
          @if (!empty($enabledBrands))
            <li class="kb-dropdown">
              <a class="kb-nav-link" href="{{ route('brands.show', ['brand' => array_key_first($enabledBrands)]) }}">
                <span>Brands</span>
                <i class="bi bi-chevron-down kb-nav-caret" aria-hidden="true"></i>
              </a>
              <div class="kb-dropdown-menu kb-dropdown-menu--compact" aria-label="Brands">
                <ul class="kb-dropdown-list">
                  @foreach ($enabledBrands as $brandKey => $brand)
                    <li><a class="kb-plain-link" href="{{ route('brands.show', ['brand' => $brandKey]) }}">{{ $brand['label'] }}</a></li>
                  @endforeach
                </ul>
              </div>
            </li>
          @endif
          <li class="kb-dropdown">
            <a class="kb-nav-link" href="{{ route('policy') }}">
              <span>Policies</span>
              <i class="bi bi-chevron-down kb-nav-caret" aria-hidden="true"></i>
            </a>
            <div class="kb-dropdown-menu kb-dropdown-menu--policies" aria-label="Policies">
              <ul class="kb-dropdown-list">
                <li>
                  <a class="kb-policy-link" href="{{ route('policy', ['title' => 'Shipping Policy']) }}">
                    <i class="bi bi-box-seam kb-policy-icon kb-icon-shipping"></i>
                    <span>Shipping Policy</span>
                  </a>
                </li>
                <li>
                  <a class="kb-policy-link" href="{{ route('policy', ['title' => 'Exchange & Return Policy']) }}">
                    <i class="bi bi-arrow-repeat kb-policy-icon kb-icon-exchange"></i>
                    <span>Exchange & Return Policy</span>
                  </a>
                </li>
                <li>
                  <a class="kb-policy-link" href="{{ route('policy', ['title' => 'FAQs']) }}">
                    <i class="bi bi-question-circle kb-policy-icon kb-icon-faq"></i>
                    <span>FAQs</span>
                  </a>
                </li>
                <li>
                  <a class="kb-policy-link" href="{{ route('policy', ['title' => 'Terms & Conditions']) }}">
                    <i class="bi bi-file-text kb-policy-icon kb-icon-terms"></i>
                    <span>Terms & Conditions</span>
                  </a>
                </li>
                <li>
                  <a class="kb-policy-link" href="{{ route('policy', ['title' => 'Privacy Policy']) }}">
                    <i class="bi bi-shield-lock kb-policy-icon kb-icon-privacy"></i>
                    <span>Privacy Policy</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li><a class="kb-nav-link" href="{{ route('lookbook') }}">Lookbook</a></li>
        </ul>
      </nav>

      <div class="kb-header-icons d-flex align-items-center">
        <button class="kb-burger" type="button" aria-label="Open menu" aria-controls="kb-mobile-nav" aria-expanded="false" data-nav-toggle>
          <span class="kb-burger-bars" aria-hidden="true"></span>
        </button>
        <button class="btn" type="button" aria-label="Search" data-search-open><i class="bi bi-search"></i></button>
        <div class="position-relative">
          <a class="btn" href="{{ route('wishlist') }}" aria-label="Wishlist"><i class="bi bi-heart"></i></a>
          <span class="kb-icon-badge" data-wishlist-count>0</span>
        </div>
        <div class="position-relative">
          <a class="btn" href="{{ route('cart') }}" aria-label="Cart"><i class="bi bi-bag"></i></a>
          <span class="kb-icon-badge" data-cart-count>0</span>
        </div>
      </div>

    </div>
  </div>
</header>

<div class="kb-nav-overlay" data-nav-overlay data-nav-close></div>
