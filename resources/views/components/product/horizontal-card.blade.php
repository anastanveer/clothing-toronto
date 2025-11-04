@props([
    'product' => [],
])

@php
    $data = collect($product);
    $imagePath = $data->get('image');
    $imageAlt = $data->get('image_alt', 'Product Image');
    $title = $data->get('title');
    $detailsUrl = $data->get('details_url', '#');
    $category = $data->get('category');
    $categoryUrl = $data->get('category_url', '#');
    $price = $data->get('price');
    $rating = $data->get('rating', 5);
    $imageUrl = $imagePath;

    if (is_numeric($price)) {
        $price = \App\Support\Money::format((float) $price);
    }

    if ($imagePath && ! preg_match('#^(https?:)?//#', $imagePath)) {
        $imageUrl = asset($imagePath);
    }
@endphp

<div {{ $attributes->class(['ul-product-horizontal']) }}>
    <div class="ul-product-horizontal-img">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
        @endif
    </div>

    <div class="ul-product-horizontal-txt">
        @if($price)
            <span class="ul-product-price">{{ $price }}</span>
        @endif

        @if($title)
            <h4 class="ul-product-title"><a href="{{ $detailsUrl }}">{{ $title }}</a></h4>
        @endif

        @if($category)
            <h5 class="ul-product-category"><a href="{{ $categoryUrl }}">{{ $category }}</a></h5>
        @endif

        @if($rating)
            <div class="ul-product-rating">
                @foreach(range(1, (int) $rating) as $star)
                    <span class="star"><i class="flaticon-star"></i></span>
                @endforeach
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
