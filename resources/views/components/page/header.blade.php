@props([
    'title',
    'breadcrumbs' => [],
    'subtitle' => null,
])

@php
    $items = collect($breadcrumbs)->map(function ($item, $index) {
        $item = is_array($item) ? $item : ['label' => (string) $item];

        return [
            'label' => $item['label'] ?? '',
            'url' => $item['url'] ?? null,
            'icon' => $item['icon'] ?? null,
            'isCurrent' => $item['is_current'] ?? ($item['url'] ?? null) === null,
            'attributes' => $item['attributes'] ?? [],
        ];
    });
@endphp

@php
    $outerAttributes = $attributes->class('ul-container');
@endphp

<div {{ $outerAttributes }}>
    <div class="ul-breadcrumb">
        <h2 class="ul-breadcrumb-title">{{ $title }}</h2>

        @if($subtitle)
            <p class="ul-breadcrumb-subtitle">{{ $subtitle }}</p>
        @endif

        @if($items->isNotEmpty())
            <div class="ul-breadcrumb-nav">
                @foreach($items as $item)
                    @php
                        $itemAttributes = new \Illuminate\View\ComponentAttributeBag($item['attributes']);
                    @endphp
                    @if($item['url'])
                        <a href="{{ $item['url'] }}" {{ $itemAttributes }}>
                            @if(! empty($item['icon']))
                                <i class="{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="{{ $item['isCurrent'] ? 'current-page' : '' }}">
                            @if(! empty($item['icon']))
                                <i class="{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @endif

                    @if(! $loop->last)
                        <i class="flaticon-arrow-point-to-right"></i>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
