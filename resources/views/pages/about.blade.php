@extends('layouts.app')

@section('title', 'About')

@section('content')
<x-layout.page>
    <x-page.header
        title="About us"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'About us', 'is_current' => true],
        ]"
    />


        <!-- ABOUT COVER AREA START -->
        <div class="ul-container">
            <div class="ul-about-cover-img">
                <img src="{{ asset('assets/img/about-cover-img.jpg') }}" alt="Cover Image">
            </div>
        </div>
        <!-- ABOUT COVER AREA END -->


        <!-- ABOUT SECTION START -->
        <div class="ul-inner-page-container my-0">
            <section class="ul-about">
                <div class="row row-cols-md-2 row-cols-1 align-items-center ul-bs-row">
                    <!-- txt -->
                    <div class="col">
                        <div class="ul-about-txt">
                            <span class="ul-section-sub-title">About us</span>
                            <h2 class="ul-section-title">We are glamers people</h2>
                            <p>Vestibulum quis lobortis mauris. Donec molestie porta nibh quis tristique. Vivamus pharetra pretium augue a tempus. Nunc eu lorem quis ex vestibulum dignissim accumsan id velit. Pellentesque pretium, mi in posuere euismod, nulla dolor blandit purus, a eleifend velit massa quis nisi. Integer gravida dictum ipsum ac fringilla. Sed non neque est. Fusce faucibus velit ac volutpat faucibus. In sapien tellus, viverra vitae elementum eu, hendrerit id eros. Duis libero turpis, elementum non molestie ornare, dictum et odio. Quisque dui dolor, commodo in malesuada id, porttitor in enim. Suspendisse elementum ante at venenatis tristique. Nam non ex porta, aliquam tellus vitae, vulputate mauris.</p>
                        </div>
                    </div>

                    <!-- img -->
                    <div class="col">
                        <div class="ul-about-img"><img src="{{ asset('assets/img/about-img-1.jpg') }}" alt="About Image"></div>
                    </div>
                </div>
            </section>
        </div>
        <!-- ABOUT SECTION END -->


        <!-- ABOUT SECTION START -->
        <div class="ul-inner-page-container my-0">
            <section class="ul-about">
                <div class="row row-cols-md-2 row-cols-1 align-items-center ul-bs-row">
                    <!-- img -->
                    <div class="col">
                        <div class="ul-about-img"><img src="{{ asset('assets/img/about-img-2.jpg') }}" alt="About Image"></div>
                    </div>

                    <!-- txt -->
                    <div class="col">
                        <div class="ul-about-txt">
                            <span class="ul-section-sub-title">our history</span>
                            <h2 class="ul-section-title">Established - 1995</h2>
                            <p>Vestibulum quis lobortis mauris. Donec molestie porta nibh quis tristique. Vivamus pharetra pretium augue a tempus. Nunc eu lorem quis ex vestibulum dignissim accumsan id velit. Pellentesque pretium, mi in posuere euismod, nulla dolor blandit purus, a eleifend velit massa quis nisi. Integer gravida dictum ipsum ac fringilla. Sed non neque est. Fusce faucibus velit ac volutpat faucibus. In sapien tellus, viverra vitae elementum eu, hendrerit id eros. Duis libero turpis, elementum non molestie ornare, dictum et odio. Quisque dui dolor, commodo in malesuada id, porttitor in enim. Suspendisse elementum ante at venenatis tristique. Nam non ex porta, aliquam tellus vitae, vulputate mauris.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- ABOUT SECTION END -->


        <!-- MORE ABOUT US SECTION START -->
        <div class="ul-inner-page-container mb-0">
            <div class="ul-more-about">
                <!-- heading -->
                <div class="ul-more-about-heading">
                    <h2 class="ul-section-title">Quality is our priority</h2>
                    <p class="ul-more-about-heading-descr">Our talented stylists have put together outfits that are perfect for the season. They’ve variety of ways to inspire your next fashion-forward look.</p>
                </div>

                <!-- row -->
                <div class="row row-cols-lg-3 row-cols-sm-2 row-cols-1 ul-more-about-row">
                    <!-- single point -->
                    <div class="col">
                        <div class="ul-more-about-point">
                            <h3 class="ul-more-about-point-title">Rending Design</h3>
                            <p class="ul-more-about-point-descr">Vestibulum quis lobortis mauris. Donec molestie porta nibh quis tristique. Vivamus pharetra pretium augue a tempus. Nunc eu lorem quis ex vestibulum dignissim accumsan id velit.</p>
                        </div>
                    </div>

                    <!-- single point -->
                    <div class="col">
                        <div class="ul-more-about-point">
                            <h3 class="ul-more-about-point-title">Multiple Sizes</h3>
                            <p class="ul-more-about-point-descr">Vestibulum quis lobortis mauris. Donec molestie porta nibh quis tristique. Vivamus pharetra pretium augue a tempus. Nunc eu lorem quis ex vestibulum dignissim accumsan id velit.</p>
                        </div>
                    </div>

                    <!-- single point -->
                    <div class="col">
                        <div class="ul-more-about-point">
                            <h3 class="ul-more-about-point-title">High Quality Matters</h3>
                            <p class="ul-more-about-point-descr">Vestibulum quis lobortis mauris. Donec molestie porta nibh quis tristique. Vivamus pharetra pretium augue a tempus. Nunc eu lorem quis ex vestibulum dignissim accumsan id velit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MORE ABOUT US SECTION END -->


        <!-- REVIEWS SECTION START -->
        <section class="ul-reviews overflow-hidden">
            <div class="ul-section-heading text-center justify-content-center">
                <div>
                    <span class="ul-section-sub-title">Customer Reviews</span>
                    <h2 class="ul-section-title">Product Reviews</h2>
                    <p class="ul-reviews-heading-descr">Our references are very valuable, the result of a great effort...</p>
                </div>
            </div>

            <!-- slider -->
            <div class="ul-reviews-slider swiper">
                <div class="swiper-wrapper">
                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-1.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Esther Howard</h3>
                                        <span class="reviewer-role">Web Designer</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-2.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Wade Warren</h3>
                                        <span class="reviewer-role">Marketing Coordinator</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-3.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Esther Howard</h3>
                                        <span class="reviewer-role">Nursing Assistant</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-4.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">John Doe</h3>
                                        <span class="reviewer-role">Medical Assistant</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- single review -->
                    <div class="swiper-slide">
                        <div class="ul-review">
                            <div class="ul-review-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star-3"></i>
                            </div>
                            <p class="ul-review-descr">Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros. In imperdiet accumsan.</p>
                            <div class="ul-review-bottom">
                                <div class="ul-review-reviewer">
                                    <div class="reviewer-image"><img src="{{ asset('assets/img/review-author-2.png') }}" alt="reviewer image"></div>
                                    <div>
                                        <h3 class="reviewer-name">Leslie Alexander</h3>
                                        <span class="reviewer-role">Medical Assistant</span>
                                    </div>
                                </div>

                                <!-- icon -->
                                <div class="ul-review-icon"><i class="flaticon-left"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- REVIEWS SECTION END -->
</x-layout.page>
@endsection
