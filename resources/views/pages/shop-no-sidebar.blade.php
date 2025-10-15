@extends('layouts.app')

@section('title', 'Shop No Sidebar')

@section('content')
<x-layout.page>
    <x-page.header
        title="Shop Without Sidebar"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Shop', 'is_current' => true],
        ]"
    />

    <!-- MAIN CONTENT SECTION START -->
    <div class="ul-inner-page-container">
        <div class="ul-inner-products-wrapper">
            <div class="row ul-bs-row flex-column-reverse flex-md-row">
                <!-- right products container -->
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('shop') }}" class="btn btn-sm {{ ($activeCategory ?? null) ? 'btn-outline-dark' : 'btn-dark' }}">All</a>
                        @foreach(($categories ?? []) as $key => $label)
                            <a href="{{ route('shop.category', $key) }}" class="btn btn-sm {{ ($activeCategory ?? null) === $key ? 'btn-dark' : 'btn-outline-dark' }}">{{ $label }}</a>
                        @endforeach
                    </div>

                    <div class="row ul-bs-row row-cols-lg-4 row-cols-sm-3 row-cols-2 row-cols-xxs-1">
                        @forelse($products as $product)
                            <div class="col">
                                <x-product.card :product="$product" />
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="py-5 text-center text-muted fw-semibold">No products available right now. Please check again soon.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT SECTION END -->
</x-layout.page>
@endsection
