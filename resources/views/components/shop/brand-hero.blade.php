@props(['brand' => null])

@if($brand)
    @php
        $image = $brand->hero_image ? asset($brand->hero_image) : null;
        $tagline = $brand->tagline;
        $summary = $brand->summary;
    @endphp
    <section class="ul-brand-hero mb-4">
        <div class="ul-brand-hero__media" @if($image) style="background-image: url('{{ $image }}');" @endif></div>
        <div class="ul-brand-hero__body">
            <span class="ul-brand-hero__label">Featured brand</span>
            <h2 class="ul-brand-hero__title">{{ $brand->name }}</h2>
            @if($tagline)
                <p class="ul-brand-hero__tagline">{{ $tagline }}</p>
            @endif
            @if($summary)
                <p class="ul-brand-hero__summary">{{ $summary }}</p>
            @endif
            <a href="{{ route('shop.brand', $brand->slug) }}" class="btn btn-dark btn-sm">View full range</a>
        </div>
    </section>
@endif
