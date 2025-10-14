@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<x-layout.page>
    <x-page.header
        title="Testimonial"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Testimonial', 'is_current' => true],
        ]"
    />


        <!-- REVIEWS SECTION START -->
        <section class="ul-reviews">
            <div class="row row-cols-xl-4 row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                <!-- single review -->
                <div class="col">
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
                <div class="col">
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
                <div class="col">
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
                <div class="col">
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
                <div class="col">
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

                <!-- single review -->
                <div class="col">
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
                <div class="col">
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
                <div class="col">
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
            </div>
        </section>
        <!-- REVIEWS SECTION END -->
</x-layout.page>
@endsection
