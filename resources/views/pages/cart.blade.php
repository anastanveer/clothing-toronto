@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<x-layout.page class="ul-page--stretch">
    <x-page.header
        title="Cart List"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Cart List', 'is_current' => true],
        ]"
    />

    <div class="ul-page--stretch__body">
        <div class="ul-cart-container">
            @if(session('status'))
                <div class="alert alert-success mb-4" role="alert">{{ session('status') }}</div>
            @endif

            @if(session('coupon_success'))
                <div class="alert alert-success mb-3" role="status">{{ session('coupon_success') }}</div>
            @endif

            @if(session('coupon_status'))
                <div class="alert alert-info mb-3" role="status">{{ session('coupon_status') }}</div>
            @endif

            @if(!empty($couponNotice))
                <div class="alert alert-warning mb-3" role="status">{{ $couponNotice }}</div>
            @endif

            <div class="alert alert-info mb-4 {{ empty($loyaltyBanner) ? 'd-none' : '' }}" role="status" data-loyalty-banner>{{ $loyaltyBanner ?? '' }}</div>

            @if($lines->isEmpty())
                <div class="py-5 text-center">
                    <p class="text-secondary mb-3">Your bag is currently empty.</p>
                    <a href="{{ route('shop') }}" class="ul-btn">Discover new arrivals</a>
                </div>
            @else
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
                                @foreach($lines as $line)
                                    @php
                                        $cartItem = $line['model'];
                                        $product = $line['product'];
                                    @endphp
                                    <tr data-remove-row>
                                        <td>
                                            <div class="ul-cart-product">
                                                <a href="{{ $product ? route('shop.details', ['slug' => $product->slug ?? $product->id]) : '#' }}" class="ul-cart-product-img">
                                                    <img src="{{ $product?->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-sm-6.jpg') }}" alt="{{ $product?->name ?? 'Product' }}">
                                                </a>
                                                <div class="text-start">
                                                    <a href="{{ $product ? route('shop.details', ['slug' => $product->slug ?? $product->id]) : '#' }}" class="ul-cart-product-title">{{ $product?->name ?? 'Product unavailable' }}</a>
                                                    @if($product?->brand)
                                                        <p class="mb-0 text-secondary small">{{ $product->brand->name }}</p>
                                                    @endif
                                                    @if(! $line['in_stock'])
                                                        <span class="badge bg-warning-subtle text-warning mt-1">Back ordered</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="ul-cart-item-price">{{ $line['unit_price_formatted'] }}</span></td>
                                        <td>
                                            <form action="{{ route('cart.items.update', $cartItem) }}" method="POST" class="d-inline-flex align-items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <div class="ul-product-details-quantity mt-0">
                                                    <div class="ul-product-quantity-wrapper">
                                                        <input
                                                            type="number"
                                                            name="quantity"
                                                            class="ul-product-quantity"
                                                            value="{{ $cartItem->quantity }}"
                                                            min="1"
                                                            max="10"
                                                        >
                                                        <div class="btns">
                                                            <button type="button" class="quantityIncreaseButton"><i class="flaticon-plus"></i></button>
                                                            <button type="button" class="quantityDecreaseButton"><i class="flaticon-minus-sign"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="ul-cart-update-cart-btn">Update</button>
                                            </form>
                                        </td>
                                        <td><span class="ul-cart-item-subtotal">{{ $line['line_total_formatted'] }}</span></td>
                                        <td>
                                            <div class="ul-cart-item-remove">
                                                <form action="{{ route('cart.items.destroy', $cartItem) }}" method="POST" class="js-remove-form" data-remove-type="cart" data-remove-selector="tr" data-confirm-title="Remove item?" data-confirm="Remove this item from your bag?" data-confirm-label="Remove" data-cancel-label="Keep item">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" aria-label="Remove from cart"><i class="flaticon-close"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-3 justify-content-between align-items-center">
                        <a href="{{ route('shop') }}" class="ul-btn ul-btn--soft">Continue Shopping</a>
                        <div class="ul-cart-coupon-code-form-wrapper ms-auto">
                            <span class="title">Have a code?</span>
                            <div class="ul-cart-applied-coupon {{ $appliedCoupon ? '' : 'is-hidden' }}" data-cart-summary-block="coupon-pill">
                                <div>
                                    <span class="ul-cart-applied-coupon__eyebrow">Applied</span>
                                    <strong class="ul-cart-applied-coupon__code" data-cart-summary="coupon-code">{{ $appliedCoupon->code ?? '' }}</strong>
                                    <span class="ul-cart-applied-coupon__title" data-cart-summary="coupon-title">{{ $appliedCoupon->title ?? '' }}</span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ul-cart-applied-coupon__remove">Remove</button>
                                </form>
                            </div>
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="ul-cart-coupon-code-form">
                                @csrf
                                <input
                                    name="code"
                                    placeholder="Enter coupon code"
                                    type="text"
                                    value="{{ old('code', $couponCode) }}"
                                    autocomplete="off"
                                >
                                <button class="ul-btn" type="submit">Apply code</button>
                            </form>
                            <p class="small text-secondary mb-0 mt-2 {{ ($couponMessage && ! $appliedCoupon) ? '' : 'd-none' }}" data-cart-summary="coupon-message">{{ ($couponMessage && ! $appliedCoupon) ? $couponMessage : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="cart-bottom">
                    <div class="ul-cart-expense-overview">
                        <h3 class="ul-cart-expense-overview-title">Order summary</h3>
                        <div class="middle">
                            <div class="single-row">
                                <span class="inner-title">Subtotal</span>
                                <span class="number" data-cart-summary="subtotal">{{ $summary['subtotal_formatted'] }}</span>
                            </div>

                            <div class="single-row">
                                <span class="inner-title">Shipping</span>
                                <span class="number" data-cart-summary="shipping">{{ $summary['shipping_formatted'] }}</span>
                            </div>

                            <div class="single-row single-row--discount {{ $summary['coupon_discount'] > 0 ? '' : 'is-hidden' }}" data-cart-summary-block="coupon">
                                <span class="inner-title">Coupon savings</span>
                                <span class="number text-success" data-cart-summary="coupon-discount">{{ $summary['coupon_discount_formatted'] ?? '' }}</span>
                            </div>

                            <div class="single-row single-row--discount {{ $summary['loyalty_discount'] > 0 ? '' : 'is-hidden' }}" data-cart-summary-block="loyalty">
                                <span class="inner-title">Loyalty reward</span>
                                <span class="number text-success" data-cart-summary="loyalty-discount">{{ $summary['loyalty_discount_formatted'] ?? '' }}</span>
                            </div>

                            <div class="single-row">
                                <span class="inner-title">Tax ({{ number_format(config('commerce.tax_rate', 0) * 100, 1) }}%)</span>
                                <span class="number" data-cart-summary="tax">{{ $summary['tax_formatted'] }}</span>
                            </div>
                        </div>
                        <div class="ul-cart-expense-overview__coupon {{ $appliedCoupon ? '' : 'is-hidden' }}" data-cart-summary-block="coupon-card">
                            <span class="badge bg-primary-subtle text-primary text-uppercase" data-cart-summary="coupon-code">{{ $appliedCoupon->code ?? '' }}</span>
                            <p class="mb-0 small text-secondary" data-cart-summary="coupon-description">{{ $appliedCoupon->description ?? 'Preferred guest savings active.' }}</p>
                        </div>
                        <div class="bottom">
                            <div class="single-row">
                                <span class="inner-title">Total</span>
                                <span class="number" data-cart-summary="total">{{ $summary['total_formatted'] }}</span>
                            </div>

                            <a href="{{ route('checkout') }}" class="ul-cart-checkout-direct-btn">Proceed to checkout</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout.page>
@endsection
