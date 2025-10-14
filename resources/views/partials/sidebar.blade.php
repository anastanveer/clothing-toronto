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
        <span class="title">About glamer</span>
        <p class="mb-0">Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra velit.</p>
    </div>

    <div class="ul-sidebar-products-wrapper d-none d-lg-flex">
        <div class="ul-sidebar-products-slider swiper">
            <div class="swiper-wrapper">
                @foreach ([1, 2, 2] as $img)
                    <div class="swiper-slide">
                        <div class="ul-product">
                            <div class="ul-product-heading">
                                <span class="ul-product-price">$99.00</span>
                                <span class="ul-product-discount-tag">25% Off</span>
                            </div>

                            <div class="ul-product-img">
                                <img src="{{ asset('assets/img/product-img-' . $img . '.jpg') }}" alt="Product Image">
                                <div class="ul-product-actions">
                                    <button><i class="flaticon-shopping-bag"></i></button>
                                    <a href="#"><i class="flaticon-hide"></i></a>
                                    <button><i class="flaticon-heart"></i></button>
                                </div>
                            </div>

                            <div class="ul-product-txt">
                                <h4 class="ul-product-title"><a href="{{ route('shop.details') }}">Orange Airsuit</a></h4>
                                <h5 class="ul-product-category"><a href="{{ route('shop') }}">Fashion Bag</a></h5>
                            </div>
                        </div>
                    </div>
                @endforeach
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
