@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<x-layout.page>
    <x-page.header
        title="Wishlist"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Wishlist', 'is_current' => true],
        ]"
    />


        <div class="ul-cart-container">
            <div class="cart-top">
                <div class="text-center">
                    <!-- cart header -->
                    <div class="ul-cart-header ul-wishlist-header">
                        <div>Product</div>
                        <div>Price</div>
                        <div>Stock</div>
                        <div>Remove</div>
                    </div>

                    <!-- cart body -->
                    <div>
                        <!-- single wishlist item -->
                        <div class="ul-cart-item">
                            <!-- product -->
                            <div class="ul-cart-product">
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-img"><img src="{{ asset('assets/img/product-img-sm-6.jpg') }}" alt="Product"></a>
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-title">Simple Things You to Save Book</a>
                            </div>
                            <!-- price -->
                            <span class="ul-cart-item-price">$60.00</span>

                            <!-- subtotal -->
                            <span class="ul-cart-item-subtotal ul-wislist-item-stock green">in stock</span>

                            <!-- remove -->
                            <div class="ul-cart-item-remove">
                                <button><i class="flaticon-close"></i></button>
                            </div>
                        </div>

                        <!-- single wishlist item -->
                        <div class="ul-cart-item">
                            <!-- product -->
                            <div class="ul-cart-product">
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-img"><img src="{{ asset('assets/img/product-img-sm-6.jpg') }}" alt="Product"></a>
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-title">Simple Things You to Save Book</a>
                            </div>
                            <!-- price -->
                            <span class="ul-cart-item-price">$60.00</span>

                            <!-- subtotal -->
                            <span class="ul-cart-item-subtotal ul-wislist-item-stock red">Out of Stock</span>

                            <!-- remove -->
                            <div class="ul-cart-item-remove">
                                <button><i class="flaticon-close"></i></button>
                            </div>
                        </div>

                        <!-- single wishlist item -->
                        <div class="ul-cart-item">
                            <!-- product -->
                            <div class="ul-cart-product">
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-img"><img src="{{ asset('assets/img/product-img-sm-6.jpg') }}" alt="Product"></a>
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-title">Simple Things You to Save Book</a>
                            </div>
                            <!-- price -->
                            <span class="ul-cart-item-price">$60.00</span>

                            <!-- subtotal -->
                            <span class="ul-cart-item-subtotal ul-wislist-item-stock green">in stock</span>

                            <!-- remove -->
                            <div class="ul-cart-item-remove">
                                <button><i class="flaticon-close"></i></button>
                            </div>
                        </div>

                        <!-- single wishlist item -->
                        <div class="ul-cart-item">
                            <!-- product -->
                            <div class="ul-cart-product">
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-img"><img src="{{ asset('assets/img/product-img-sm-6.jpg') }}" alt="Product"></a>
                                <a href="{{ route('shop.details') }}" class="ul-cart-product-title">Simple Things You to Save Book</a>
                            </div>
                            <!-- price -->
                            <span class="ul-cart-item-price">$60.00</span>

                            <!-- subtotal -->
                            <span class="ul-cart-item-subtotal ul-wislist-item-stock red">Out of Stock</span>

                            <!-- remove -->
                            <div class="ul-cart-item-remove">
                                <button><i class="flaticon-close"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
