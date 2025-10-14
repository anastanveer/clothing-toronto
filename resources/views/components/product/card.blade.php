@props([
    'product' => [],
])

@php
    $data = collect($product);

    $price = $data->get('price');
    $discount = $data->get('discount');
    $badges = collect($data->get('badges', []));
    $imagePath = $data->get('image');
    $imageAlt = $data->get('image_alt', 'Product Image');
    $detailsUrl = $data->get('details_url', '#');
    $category = $data->get('category');
    $categoryUrl = $data->get('category_url', '#');
    $title = $data->get('title');
    $actions = collect($data->get('actions', [
        ['type' => 'button', 'icon' => 'flaticon-shopping-bag'],
        ['type' => 'link', 'icon' => 'flaticon-hide', 'url' => '#'],
        ['type' => 'button', 'icon' => 'flaticon-heart'],
    ]));
    $imageUrl = $imagePath;

    if ($imagePath && ! preg_match('#^(https?:)?//#', $imagePath)) {
        $imageUrl = asset($imagePath);
    }
@endphp

<div {{ $attributes->class('ul-product') }}>
    @if($price || $discount || $badges->isNotEmpty())
        <div class="ul-product-heading">
            @if($price)
                <span class="ul-product-price">{{ $price }}</span>
            @endif

            @if($discount)
                <span class="ul-product-discount-tag">{{ $discount }}</span>
            @endif

            @foreach($badges as $badge)
                @php
                    $label = is_array($badge) ? ($badge['label'] ?? '') : (string) $badge;
                    $class = is_array($badge) ? ($badge['class'] ?? '') : '';
                @endphp
                <span class="ul-product-discount-tag {{ $class }}">{{ $label }}</span>
            @endforeach
        </div>
    @endif

    <div class="ul-product-img">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
        @endif

        @if($actions->isNotEmpty())
            <div class="ul-product-actions">
                @foreach($actions as $action)
                    @php
                        $action = is_array($action) ? $action : ['icon' => (string) $action];
                        $type = $action['type'] ?? 'button';
                        $icon = $action['icon'] ?? 'flaticon-up-right-arrow';
                        $url = $action['url'] ?? '#';
                    @endphp

                    @if($type === 'link')
                        <a href="{{ $url }}"><i class="{{ $icon }}"></i></a>
                    @else
                        <button type="button"><i class="{{ $icon }}"></i></button>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div class="ul-product-txt">
        @if($title)
            <h4 class="ul-product-title"><a href="{{ $detailsUrl }}">{{ $title }}</a></h4>
        @endif

        @if($category)
            <h5 class="ul-product-category"><a href="{{ $categoryUrl }}">{{ $category }}</a></h5>
        @endif

        {{ $slot }}
    </div>
</div>
