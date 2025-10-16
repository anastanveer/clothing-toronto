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
                        @if(session('status'))
                            <div class="alert alert-success mb-4" role="alert">{{ session('status') }}</div>
                        @endif

                        @forelse ($items as $item)
                            @php
                                $product = $item->product;
                                $price = $product?->sale_price ?? $product?->price ?? 0;
                                $inStock = ($product?->stock ?? 0) > 0;
                            @endphp
                            <div class="ul-cart-item">
                                <!-- product -->
                                <div class="ul-cart-product">
                                    <a href="{{ route('shop.details', ['slug' => $product?->slug ?? $product?->id]) }}" class="ul-cart-product-img">
                                        <img src="{{ $product?->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-sm-6.jpg') }}" alt="{{ $product?->name }}">
                                    </a>
                                    <a href="{{ route('shop.details', ['slug' => $product?->slug ?? $product?->id]) }}" class="ul-cart-product-title">{{ $product?->name ?? 'Product unavailable' }}</a>
                                </div>
                                <!-- price -->
                                <span class="ul-cart-item-price">${{ number_format($price, 2) }}</span>

                                <!-- stock -->
                                <span class="ul-cart-item-subtotal ul-wislist-item-stock {{ $inStock ? 'green' : 'red' }}">
                                    {{ $inStock ? 'In stock' : 'Out of stock' }}
                                </span>

                                <!-- remove -->
                                <div class="ul-cart-item-remove">
                                    <form action="{{ route('wishlist.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove this item from your wishlist?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" aria-label="Remove from wishlist">
                                            <i class="flaticon-close"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-5">
                                <p class="text-secondary mb-2">No saved looks just yet.</p>
                                <a href="{{ route('shop') }}" class="ul-btn">Start shopping</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
