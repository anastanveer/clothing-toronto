@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<x-layout.page>
    <x-page.header
        title="Faq"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Faq', 'is_current' => true],
        ]"
    />


        <!-- FAQ SECTION START -->
        <section class="ul-faq">
            <div class="ul-inner-page-container">
                <div class="ul-accordion">
                    <!-- single question -->
                    <div class="ul-single-accordion-item">
                        <div class="ul-single-accordion-item__header">
                            <div class="left">
                                <h3 class="ul-single-accordion-item__title">How do I get in touch with WooCommerce?</h3>
                            </div>
                            <span class="icon"><i class="flaticon-plus"></i></span>
                        </div>

                        <div class="ul-single-accordion-item__body">
                            <p class="mb-0">Nullam faucibus eleifend mi eu varius. Integer vel tincidunt massa, quis semper odio. Mauris et mollis quam. Nullam fringilla erat id ante commodo sodales. In maximus ultrices euismod. Vivamus porta justo ex.</p>
                        </div>
                    </div>

                    <!-- single question -->
                    <div class="ul-single-accordion-item open">
                        <div class="ul-single-accordion-item__header">
                            <div class="left">
                                <h3 class="ul-single-accordion-item__title">Do you have restock notifications?</h3>
                            </div>
                            <span class="icon"><i class="flaticon-plus"></i></span>
                        </div>

                        <div class="ul-single-accordion-item__body">
                            <p class="mb-0">Nullam faucibus eleifend mi eu varius. Integer vel tincidunt massa, quis semper odio. Mauris et mollis quam. Nullam fringilla erat id ante commodo sodales. In maximus ultrices euismod. Vivamus porta justo ex.</p>
                        </div>
                    </div>

                    <!-- single question -->
                    <div class="ul-single-accordion-item">
                        <div class="ul-single-accordion-item__header">
                            <div class="left">
                                <h3 class="ul-single-accordion-item__title">How do I care for my items?</h3>
                            </div>
                            <span class="icon"><i class="flaticon-plus"></i></span>
                        </div>

                        <div class="ul-single-accordion-item__body">
                            <p class="mb-0">Nullam faucibus eleifend mi eu varius. Integer vel tincidunt massa, quis semper odio. Mauris et mollis quam. Nullam fringilla erat id ante commodo sodales. In maximus ultrices euismod. Vivamus porta justo ex.</p>
                        </div>
                    </div>

                    <!-- single question -->
                    <div class="ul-single-accordion-item">
                        <div class="ul-single-accordion-item__header">
                            <div class="left">
                                <h3 class="ul-single-accordion-item__title">How do I know what size I am?</h3>
                            </div>
                            <span class="icon"><i class="flaticon-plus"></i></span>
                        </div>

                        <div class="ul-single-accordion-item__body">
                            <p class="mb-0">Nullam faucibus eleifend mi eu varius. Integer vel tincidunt massa, quis semper odio. Mauris et mollis quam. Nullam fringilla erat id ante commodo sodales. In maximus ultrices euismod. Vivamus porta justo ex.</p>
                        </div>
                    </div>

                    <!-- single question -->
                    <div class="ul-single-accordion-item">
                        <div class="ul-single-accordion-item__header">
                            <div class="left">
                                <h3 class="ul-single-accordion-item__title">How do I use a gift card?</h3>
                            </div>
                            <span class="icon"><i class="flaticon-plus"></i></span>
                        </div>

                        <div class="ul-single-accordion-item__body">
                            <p class="mb-0">Nullam faucibus eleifend mi eu varius. Integer vel tincidunt massa, quis semper odio. Mauris et mollis quam. Nullam fringilla erat id ante commodo sodales. In maximus ultrices euismod. Vivamus porta justo ex.</p>
                        </div>
                    </div>

                    <!-- single question -->
                    <div class="ul-single-accordion-item">
                        <div class="ul-single-accordion-item__header">
                            <div class="left">
                                <h3 class="ul-single-accordion-item__title">How often do you restock items?</h3>
                            </div>
                            <span class="icon"><i class="flaticon-plus"></i></span>
                        </div>

                        <div class="ul-single-accordion-item__body">
                            <p class="mb-0">Nullam faucibus eleifend mi eu varius. Integer vel tincidunt massa, quis semper odio. Mauris et mollis quam. Nullam fringilla erat id ante commodo sodales. In maximus ultrices euismod. Vivamus porta justo ex.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ SECTION END -->
</x-layout.page>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/accordion.js') }}"></script>
@endpush
