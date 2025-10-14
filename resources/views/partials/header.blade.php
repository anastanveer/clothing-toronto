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
                            <a href="{{ route('shop') }}">Women</a>
                            <a href="{{ route('shop') }}">Men's</a>
                            <a href="{{ route('shop') }}">Kids</a>
                            <a href="{{ route('blog') }}">Blog</a>

                            <div class="has-sub-menu has-mega-menu">
                                <a role="button">Pages</a>

                                <div class="ul-header-submenu ul-header-megamenu">
                                    <div class="mega-menu-top">
                                        <div class="mega-menu-hero">
                                            <img src="{{ asset('assets/img/mega-menu-img.jpg') }}" alt="mega menu image">

                                            <div class="mega-menu-hero-txt">
                                                <span class="tag">HOT</span>
                                                <h4 class="title">Luxury Watches</h4>
                                                <p class="desc">Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id.</p>
                                                <a href="{{ route('shop') }}" class="ul-btn">Order Now <i class="flaticon-up-right-arrow"></i></a>
                                            </div>
                                        </div>

                                        <div class="mega-menu-links">
                                            <a href="{{ route('shop') }}">Men's Fashion</a>
                                            <a href="{{ route('shop') }}">Kid's</a>
                                            <a href="{{ route('shop') }}">Women</a>
                                            <a href="{{ route('shop') }}">Accessories</a>
                                            <a href="{{ route('shop') }}">Watches</a>
                                            <a href="{{ route('shop') }}">Jewellery</a>
                                        </div>

                                        <div class="mega-menu-collection">
                                            <div class="top">
                                                <h4 class="title">New Collection</h4>
                                                <a href="{{ route('shop') }}"><i class="flaticon-up-right-arrow"></i></a>
                                            </div>

                                            <div class="ul-collection-card">
                                                <div class="txt">
                                                    <span class="tag">Trend</span>
                                                    <h4 class="title">Classic<br>Colection</h4>
                                                </div>
                                                <img src="{{ asset('assets/img/collection-card-img.jpg') }}" alt="collection card image">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mega-menu-bottom">
                                        <div class="single-col">
                                            <span class="single-col-title">Collections</span>
                                            <ul>
                                                <li><a href="{{ route('shop') }}">All</a></li>
                                                <li><a href="{{ route('shop') }}">Men</a></li>
                                                <li><a href="{{ route('shop') }}">Women</a></li>
                                                <li><a href="{{ route('shop') }}">Children</a></li>
                                                <li><a href="{{ route('shop') }}">Jewellery</a></li>
                                                <li><a href="{{ route('shop') }}">Accessories</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Quick Links</span>
                                            <ul>
                                                <li><a href="{{ route('about') }}">About</a></li>
                                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                                <li><a href="{{ route('faq') }}">FAQ</a></li>
                                                <li><a href="{{ route('reviews') }}">Reviews</a></li>
                                                <li><a href="{{ route('blog') }}">Blog</a></li>
                                                <li><a href="{{ route('our-store') }}">Store</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Men's</span>
                                            <ul>
                                                <li><a href="{{ route('shop') }}">Clothing</a></li>
                                                <li><a href="{{ route('shop') }}">Footwear</a></li>
                                                <li><a href="{{ route('shop') }}">Accessories</a></li>
                                                <li><a href="{{ route('shop') }}">Activewear</a></li>
                                                <li><a href="{{ route('shop') }}">Grooming</a></li>
                                                <li><a href="{{ route('shop') }}">Ethnic Wear</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Women's</span>
                                            <ul>
                                                <li><a href="{{ route('shop') }}">Clothing</a></li>
                                                <li><a href="{{ route('shop') }}">Footwear</a></li>
                                                <li><a href="{{ route('shop') }}">Bags & Accessories</a></li>
                                                <li><a href="{{ route('shop') }}">Activewear</a></li>
                                                <li><a href="{{ route('shop') }}">Beauty & Grooming</a></li>
                                                <li><a href="{{ route('shop') }}">Ethnic Wear</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Children's</span>
                                            <ul>
                                                <li><a href="{{ route('shop') }}">Clothing</a></li>
                                                <li><a href="{{ route('shop') }}">Footwear</a></li>
                                                <li><a href="{{ route('shop') }}">Accessories</a></li>
                                                <li><a href="{{ route('shop') }}">Toys & Games</a></li>
                                                <li><a href="{{ route('shop') }}">Baby Essentials</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Jewellery</span>
                                            <ul>
                                                <li><a href="{{ route('shop') }}">Ethnic & Traditional Jewellery</a></li>
                                                <li><a href="{{ route('shop') }}">Bridal Jewellery</a></li>
                                                <li><a href="{{ route('shop') }}">Bracelets</a></li>
                                                <li><a href="{{ route('shop') }}">Rings</a></li>
                                                <li><a href="{{ route('shop') }}">Earrings</a></li>
                                                <li><a href="{{ route('shop') }}">Chains & Pendants</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
