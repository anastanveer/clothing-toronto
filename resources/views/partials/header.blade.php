<header class="ul-header">
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
                        <a href="{{ route('home') }}" class="d-inline-block"><img src="{{ asset('assets/img/logo.svg') }}" alt="logo" class="logo"></a>
                    </div>

                    <div class="ul-header-search-form-wrapper flex-grow-1 flex-shrink-0">
                        <form action="#" class="ul-header-search-form">
                            <div class="dropdown-wrapper">
                                <select name="search-category" id="ul-header-search-category">
                                    <option data-placeholder="true">Select Category</option>
                                    <option value="1">Clothing</option>
                                    <option value="2">Watches</option>
                                    <option value="3">Jewellery</option>
                                    <option value="4">Glasses</option>
                                </select>
                            </div>
                            <div class="ul-header-search-form-right">
                                <input type="search" name="header-search" id="ul-header-search" placeholder="Search Here">
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
                            <a href="{{ route('shop.category', 'kids') }}">Kids</a>
                            <a href="{{ route('blog') }}">Blog</a>

                        </nav>
                    </div>
                </div>

                <div class="ul-header-actions">
                    <button class="ul-header-mobile-search-opener d-xxl-none"><i class="flaticon-search-interface-symbol"></i></button>
                    <a href="{{ route('login') }}"><i class="flaticon-user"></i></a>
                    <a href="{{ route('wishlist') }}"><i class="flaticon-heart"></i></a>
                    <a href="{{ route('cart') }}"><i class="flaticon-shopping-bag"></i></a>
                </div>

                <div class="d-inline-flex">
                    <button class="ul-header-sidebar-opener"><i class="flaticon-hamburger"></i></button>
                </div>
            </div>
        </div>
    </div>
</header>
