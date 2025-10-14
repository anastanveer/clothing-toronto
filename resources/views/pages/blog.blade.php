@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<x-layout.page>
    <x-page.header
        title="Blog"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Blog', 'is_current' => true],
        ]"
    />


        <!-- BLOG SECTION START -->
        <section>
            <div class="ul-inner-page-container">
                <div class="row ul-bs-row row-cols-md-3 row-cols-2 row-cols-xxs-1">
                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-1.jpg') }}" alt="Article Image">

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
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>

                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-2.jpg') }}" alt="Article Image">

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
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>

                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-3.jpg') }}" alt="Article Image">

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
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>

                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-1.jpg') }}" alt="Article Image">

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
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>

                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-2.jpg') }}" alt="Article Image">

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
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>

                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset('assets/img/blog-3.jpg') }}" alt="Article Image">

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
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details') }}">Cuticle Pushers & Trimmers</a></h3>
                                <p class="ul-blog-descr">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                <a href="{{ route('blog.details') }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- pagination -->
                <div class="ul-pagination pt-0 border-0">
                    <ul>
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
        </section>
        <!-- BLOG SECTION END -->
</x-layout.page>
@endsection
