@extends('layouts.app')

@section('title', 'Blog Details')

@section('content')
<x-layout.page>
    <x-page.header
        title="Blog Details"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Blog Details', 'is_current' => true],
        ]"
    />


        <!-- BLOG SECTION START -->
        <div class="ul-inner-page-container">
            <div class="row ul-bs-row">
                <div class="col-xxxl-9 col-lg-8 col-md-7">
                    <!-- blog details -->
                    <div class="ul-blog-details">
                        <div class="ul-blog ul-blog-big">
                            <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Fashion is what you’re offered four times a year by designers. And style is what you choose</a></h3>

                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-big-img-1.jpg') }}" alt="Blog Image">

                                <div class="ul-blog-infos ul-blog-details-infos flex gap-x-[30px] mb-[16px]">
                                    <!-- single info -->
                                    <div class="ul-blog-info">
                                        <span class="icon"><i class="flaticon-user-2"></i></span>
                                        <span class="text font-normal text-[14px] text-etGray">By Admin</span>
                                    </div>
                                    <!-- single info -->
                                    <div class="ul-blog-info">
                                        <span class="icon"><i class="flaticon-calendar"></i></span>
                                        <span class="text font-normal text-[14px] text-etGray">Jun 12, 2024</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ul-blog-txt">
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet
                                    <br>
                                    <br>
                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum,
                                </p>

                                <div class="ul-blog-details-inner-img">
                                    <div><img src="{{ asset('assets/img/blog-big-img-2.jpg') }}" alt="blog inner image"></div>
                                    <div><img src="{{ asset('assets/img/blog-big-img-3.jpg') }}" alt="blog inner image"></div>
                                </div>

                                <blockquote><span class="icon"><i class="flaticon-quote"></i></span> There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a</blockquote>

                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet </p>
                            </div>

                            <!-- actions -->
                            <div class="ul-blog-details-actions">
                                <!-- tags -->
                                <div class="tags-wrapper">
                                    <div class="ul-blog-tags tags">
                                        <a href="#">Reseller</a>
                                        <a href="#">Hosting</a>
                                        <a href="#">WP Hosting</a>
                                    </div>
                                </div>

                                <!-- share -->
                                <div class="share">
                                    <div class="share-options">
                                        <a href="#"><i class="flaticon-facebook-app-symbol"></i></a>
                                        <a href="#"><i class="flaticon-twitter"></i></a>
                                        <a href="#"><i class="flaticon-linkedin-big-logo"></i></a>
                                        <a href="#"><i class="flaticon-youtube"></i></a>
                                    </div>
                                </div>
                            </div>

                            <!-- nav -->
                            <div class="ul-blog-details-nav">
                                <div class="nav-item prev">
                                    <a href="{{ route('blog.details') }}" class="icon-link"><i class="flaticon-left-arrow"></i></a>
                                    <a href="{{ route('blog.details') }}" class="text-link">Prev Post</a>
                                </div>

                                <div class="nav-item prev">
                                    <a href="{{ route('blog.details') }}" class="text-link">Next Post</a>
                                    <a href="{{ route('blog.details') }}" class="icon-link"><i class="flaticon-arrow-point-to-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="ul-blog-details-bottom">
                            <!-- reviews -->
                            <div class="ul-product-details-reviews">
                                <h3 class="ul-product-details-inner-title">02 Comments</h3>

                                <!-- single review -->
                                <div class="ul-product-details-review">
                                    <!-- reviewer image -->
                                    <div class="ul-product-details-review-reviewer-img">
                                        <img src="{{ asset('assets/img/reviewer-img-1.png') }}" alt="Reviewer Image">
                                    </div>

                                    <div class="ul-product-details-review-txt">
                                        <div class="header">
                                            <div class="left">
                                                <h4 class="reviewer-name">Temptics Pro</h4>
                                                <h5 class="review-date">March 20, 2023 at 2:37 pm</h5>
                                            </div>

                                            <div class="right">
                                                <div class="rating">
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star-3"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <p>Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla</p>

                                        <button class="ul-product-details-review-reply-btn">Reply</button>
                                    </div>
                                </div>

                                <!-- single review -->
                                <div class="ul-product-details-review">
                                    <!-- reviewer image -->
                                    <div class="ul-product-details-review-reviewer-img">
                                        <img src="{{ asset('assets/img/reviewer-img-2.png') }}" alt="Reviewer Image">
                                    </div>

                                    <div class="ul-product-details-review-txt">
                                        <div class="header">
                                            <div class="left">
                                                <h4 class="reviewer-name">Temptics Pro</h4>
                                                <h5 class="review-date">March 20, 2023 at 2:37 pm</h5>
                                            </div>

                                            <div class="right">
                                                <div class="rating">
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star"></i>
                                                    <i class="flaticon-star-3"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <p>Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla</p>

                                        <button class="ul-product-details-review-reply-btn">Reply</button>
                                    </div>
                                </div>
                            </div>

                            <!-- review form -->
                            <div class="ul-product-details-review-form-wrapper">
                                <h3 class="ul-product-details-inner-title ul-blog-details-comment-form-title">Leave a Comment</h3>

                                <form class="ul-product-details-review-form">
                                    <div class="row row-cols-2 row-cols-xxs-1 ul-bs-row">
                                        <div class="form-group">
                                            <input type="text" name="comment-name" id="comment-name" placeholder="Your Name">
                                        </div>

                                        <div class="form-group">
                                            <input type="email" name="comment-email" id="comment-email" placeholder="Your Email">
                                        </div>

                                        <div class="form-group col-12">
                                            <textarea name="comment-message" id="comment-message" placeholder="Write Message"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit">Post a Comment <span><i class="flaticon-up-right-arrow"></i></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sidebar -->
                <div class="col-xxxl-3 col-lg-4 col-md-5">
                    <div class="ul-blog-sidebar">
                        <!-- single widget /search -->
                        <div class="ul-blog-sidebar-widget ul-blog-sidebar-search">
                            <div class="ul-blog-sidebar-widget-content">
                                <form action="#" class="ul-blog-search-form">
                                    <input type="search" name="blog-search" id="ul-blog-search" placeholder="Search Here">
                                    <button type="submit"><span class="icon"><i class="flaticon-search-interface-symbol"></i></span></button>
                                </form>
                            </div>
                        </div>

                        <!-- single widget / Recent Posts -->
                        <div class="ul-blog-sidebar-widget ul-blog-sidebar-recent-post">
                            <h3 class="ul-blog-sidebar-widget-title">Recent Posts</h3>
                            <div class="ul-blog-sidebar-widget-content">
                                <div class="ul-blog-recent-posts">
                                    <!-- single post -->
                                    <div class="ul-blog-recent-post">
                                        <div class="img">
                                            <img src="{{ asset('assets/img/blog-2.jpg') }}" alt="Post Image">
                                        </div>

                                        <div class="txt">
                                            <span class="date">
                                                <span class="icon"><i class="flaticon-calendar"></i></span>
                                                <span>May 12, 2025</span>
                                            </span>

                                            <h4 class="title"><a href="{{ route('blog.details') }}">How to get the first 100 customers for</a></h4>
                                        </div>
                                    </div>

                                    <!-- single post -->
                                    <div class="ul-blog-recent-post">
                                        <div class="img">
                                            <img src="{{ asset('assets/img/blog-3.jpg') }}" alt="Post Image">
                                        </div>

                                        <div class="txt">
                                            <span class="date">
                                                <span class="icon"><i class="flaticon-calendar"></i></span>
                                                <span>May 12, 2025</span>
                                            </span>

                                            <h4 class="title"><a href="{{ route('blog.details') }}">How to get the first 100 customers for</a></h4>
                                        </div>
                                    </div>

                                    <!-- single post -->
                                    <div class="ul-blog-recent-post">
                                        <div class="img">
                                            <img src="{{ asset('assets/img/blog-1.jpg') }}" alt="Post Image">
                                        </div>

                                        <div class="txt">
                                            <span class="date">
                                                <span class="icon"><i class="flaticon-calendar"></i></span>
                                                <span>May 12, 2025</span>
                                            </span>

                                            <h4 class="title"><a href="{{ route('blog.details') }}">How to get the first 100 customers for</a></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- single widget / Recommended Topics -->
                        <div class="ul-blog-sidebar-widget ul-blog-sidebar-recent-post">
                            <h3 class="ul-blog-sidebar-widget-title">Recommended Topics</h3>
                            <div class="ul-blog-sidebar-widget-content">
                                <div class="ul-blog-tags">
                                    <a href="{{ route('blog.two') }}">Accessories</a>
                                    <a href="{{ route('blog.two') }}">Fashion</a>
                                    <a href="{{ route('blog.two') }}">Blog</a>
                                    <a href="{{ route('blog.two') }}">Lifestyle</a>
                                    <a href="{{ route('blog.two') }}">Tadatheme</a>
                                </div>
                            </div>
                        </div>

                        <div class="ul-blog-sidebar-widget ad-banner">
                            <a href="{{ route('shop') }}"><img src="{{ asset('assets/img/gallery-item-4.jpg') }}" alt="ad banner"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BLOG SECTION END -->
</x-layout.page>
@endsection
