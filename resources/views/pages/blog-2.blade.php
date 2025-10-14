@extends('layouts.app')

@section('title', 'Blog Classic')

@section('content')
<x-layout.page>
    <x-page.header
        title="Blog Standard"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Blog Standard', 'is_current' => true],
        ]"
    />


        <!-- BLOG SECTION START -->
        <section>
            <div class="ul-inner-page-container">
                <div class="row ul-bs-row">
                    <div class="col-xxxl-9 col-lg-8 col-md-7">
                        <!-- blogs -->
                        <div>
                            <!-- single blog -->
                            <div class="ul-blog ul-blog-big">
                                <div class="ul-blog-img">
                                    <img src="{{ asset('assets/img/blog-big-img-1.jpg') }}" alt="Blog Image">

                                    <div class="date">
                                        <span class="number">15</span>
                                        <span class="txt">Dec</span>
                                    </div>
                                </div>

                                <div class="ul-blog-txt">
                                    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
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

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Fashion is what you’re offered four times a year by designers. And style is what you choose</a></h3>
                                    <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet </p>

                                    <a href="{{ route('blog.details') }}" class="ul-blog-btn ul-blog-big-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>

                            <!-- single blog -->
                            <div class="ul-blog ul-blog-big">
                                <div class="ul-blog-img">
                                    <img src="{{ asset('assets/img/blog-big-img-2.jpg') }}" alt="Blog Image">

                                    <div class="date">
                                        <span class="number">15</span>
                                        <span class="txt">Dec</span>
                                    </div>
                                </div>

                                <div class="ul-blog-txt">
                                    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
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

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Fashion is what you’re offered four times a year by designers. And style is what you choose</a></h3>
                                    <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet </p>

                                    <a href="{{ route('blog.details') }}" class="ul-blog-btn ul-blog-big-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>

                            <!-- single blog -->
                            <div class="ul-blog ul-blog-big">
                                <div class="ul-blog-img">
                                    <img src="{{ asset('assets/img/blog-big-img-3.jpg') }}" alt="Blog Image">

                                    <div class="date">
                                        <span class="number">15</span>
                                        <span class="txt">Dec</span>
                                    </div>
                                </div>

                                <div class="ul-blog-txt">
                                    <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
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

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Fashion is what you’re offered four times a year by designers. And style is what you choose</a></h3>
                                    <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet </p>

                                    <a href="{{ route('blog.details') }}" class="ul-blog-btn ul-blog-big-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>
                        </div>

                        <!-- pagination -->
                        <div class="ul-pagination pt-0 border-0">
                            <ul class="justify-content-start">
                                <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                <li class="pages">
                                    <a href="#" class="active">01</a>
                                    <a href="#">02</a>
                                    <a href="#">03</a>
                                    <a href="#">04</a>
                                    <a href="#">05</a>
                                </li>
                                <li><a href="#"><i class="flaticon-arrow-point-to-right"></i></a></li>
                            </ul>
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
        </section>
        <!-- BLOG SECTION END -->
</x-layout.page>
@endsection
