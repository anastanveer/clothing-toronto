@extends('layouts.app')

@section('title', 'Our Store')

@section('content')
<x-layout.page>
    <x-page.header
        title="Our Store"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Our Store', 'is_current' => true],
        ]"
    />


        <!-- STORE SECTION START -->
        <section class="ul-store">
            <div class="ul-inner-page-container">
                <div class="row g-lg-5 g-4 row-cols-sm-2 row-cols-1 align-items-center">
                    <!-- txt -->
                    <div class="col">
                        <div class="ul-store-txt">
                            <span class="ul-section-sub-title">Main store</span>
                            <h2 class="ul-section-title">Online Glamer’s</h2>
                            <div class="ul-store-infos">
                                <div class="ul-store-info">
                                    <span class="key">Address: </span>
                                    <span>House 6, Lane No. 6, Block A New york city 10 Roundabout, united state 1216</span>
                                </div>

                                <div class="ul-store-info">
                                    <span class="key">Opening Hour's: </span>
                                    <span>Sunday - Friday 8:00 AM - 10:00 PM</span>
                                </div>

                                <div class="ul-store-info">
                                    <span class="key">Phone number: </span>
                                    <a href="tel:+12365478009">+1 2365 478 009</a>
                                </div>
                            </div>

                            <a href="#" class="ul-store-btn">View Location</a>
                        </div>
                    </div>

                    <!-- img -->
                    <div class="col">
                        <div class="ul-store-img">
                            <img src="{{ asset('assets/img/store-1.jpg') }}" alt="store image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- STORE SECTION END -->


        <!-- STORE SECTION START -->
        <section class="ul-store">
            <div class="ul-inner-page-container">
                <div class="row g-lg-5 g-4 row-cols-sm-2 row-cols-1 align-items-center flex-sm-row flex-column-reverse">
                    <!-- img -->
                    <div class="col">
                        <div class="ul-store-img">
                            <img src="{{ asset('assets/img/store-2.jpg') }}" alt="store image">
                        </div>
                    </div>

                    <!-- txt -->
                    <div class="col">
                        <div class="ul-store-txt">
                            <span class="ul-section-sub-title">Branch store</span>
                            <h2 class="ul-section-title">Online Glamer’s 02</h2>
                            <div class="ul-store-infos">
                                <div class="ul-store-info">
                                    <span class="key">Address: </span>
                                    <span>House 6, Lane No. 6, Block A New york city 10 Roundabout, united state 1216</span>
                                </div>

                                <div class="ul-store-info">
                                    <span class="key">Opening Hour's: </span>
                                    <span>Sunday - Friday 8:00 AM - 10:00 PM</span>
                                </div>

                                <div class="ul-store-info">
                                    <span class="key">Phone number: </span>
                                    <a href="tel:+12365478009">+1 2365 478 009</a>
                                </div>
                            </div>

                            <a href="#" class="ul-store-btn">View Location</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- STORE SECTION END -->


        <!-- GALLERY SECTION START -->
        <div class="ul-gallery ul-inner-page-gallery overflow-hidden mx-auto">
            <div class="ul-gallery-slider swiper">
                <div class="swiper-wrapper">
                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-1.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-1.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-2.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-2.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-3.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-3.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-4.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-4.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-5.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-5.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-6.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-6.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-1.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-1.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>

                    <!-- single gallery item -->
                    <div class="ul-gallery-item swiper-slide">
                        <img src="{{ asset('assets/img/gallery-item-2.jpg') }}" alt="Gallery Image">
                        <div class="ul-gallery-item-btn-wrapper">
                            <a href="{{ asset('assets/img/gallery-item-2.jpg') }}" data-fslightbox="gallery"><i class="flaticon-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- GALLERY SECTION END -->
</x-layout.page>
@endsection
