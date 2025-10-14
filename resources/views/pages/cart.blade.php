@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<x-layout.page>
    <x-page.header
        title="Cart List"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Cart List', 'is_current' => true],
        ]"
    />


        <div class="ul-cart-container">
            <div class="cart-top">
                <div class="table-responsive">
                    <table class="ul-cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <div class="ul-cart-product">
                                        <a href="{{ route('shop.details') }}" class="ul-cart-product-img"><img src="{{ asset('assets/img/product-img-sm-6.jpg') }}" alt="Product"></a>
                                        <a href="{{ route('shop.details') }}" class="ul-cart-product-title">Simple Things You to Save Book</a>
                                    </div>
                                </td>
                                <td><span class="ul-cart-item-price">$10.00</span></td>
                                <td>
                                    <div class="ul-product-details-quantity mt-0">
                                        <form action="#" class="ul-product-quantity-wrapper">
                                            <input type="number" name="product-quantity" class="ul-product-quantity" value="1" min="1" readonly="">
                                            <div class="btns">
                                                <button type="button" class="quantityIncreaseButton"><i class="flaticon-plus"></i></button>
                                                <button type="button" class="quantityDecreaseButton"><i class="flaticon-minus-sign"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                <td><span class="ul-cart-item-subtotal">$10.00</span></td>
                                <td>
                                    <div class="ul-cart-item-remove"><button><i class="flaticon-close"></i></button></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="ul-cart-actions">
                        <div class="ul-cart-coupon-code-form-wrapper">
                            <span class="title">Coupon:</span>
                            <form action="#" class="ul-cart-coupon-code-form">
                                <input name="coupon-code" placeholder="Enter Coupon Code" type="text">
                                <button class="mb-btn">Apply</button>
                            </form>
                        </div>

                        <button class="ul-cart-update-cart-btn">Update Cart</button>
                    </div>
                </div>
            </div>

            <div class="cart-bottom">
                <div class="ul-cart-expense-overview">
                    <h3 class="ul-cart-expense-overview-title">Total</h3>
                    <div class="middle">
                        <div class="single-row">
                            <span class="inner-title">Subtotal</span>
                            <span class="number">$999.00</span>
                        </div>

                        <div class="single-row">
                            <span class="inner-title">Shipping Fee</span>
                            <span class="number">$15.00</span>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="single-row">
                            <span class="inner-title">Total</span>
                            <span class="number">$999.00</span>
                        </div>

                        <button class="ul-cart-checkout-direct-btn">CHECKOUT</button>
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
