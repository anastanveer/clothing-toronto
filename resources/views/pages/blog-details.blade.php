@extends('layouts.app')

@section('title', $post->meta_title ?? $post->title)

@section('content')
<x-layout.page>
    <x-page.header
        :title="$post->title"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Blog', 'url' => route('blog')],
            ['label' => $post->title, 'is_current' => true],
        ]"
    />

    <div class="ul-inner-page-container">
        <div class="row ul-bs-row">
            <div class="col-xxxl-9 col-lg-8 col-md-7">
                <article class="ul-blog-details">
                    <h3 class="ul-blog-title mb-4">{{ $post->title }}</h3>

                    <div class="ul-blog-img mb-4">
                        <img src="{{ asset($post->featured_image ?? 'assets/img/blog-big-img-1.jpg') }}" alt="{{ $post->title }}">
                        <div class="ul-blog-infos ul-blog-details-infos flex gap-x-[30px] mb-[16px]">
                            <div class="ul-blog-info">
                                <span class="icon"><i class="flaticon-user-2"></i></span>
                                <span class="text font-normal text-[14px] text-etGray">{{ $post->author->name ?? 'Editorial Team' }}</span>
                            </div>
                            <div class="ul-blog-info">
                                <span class="icon"><i class="flaticon-calendar"></i></span>
                                <span class="text font-normal text-[14px] text-etGray">{{ optional($post->published_at)->format('M d, Y') ?? 'Draft' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="ul-blog-txt">
                        {!! $post->content !!}
                    </div>

                    @if(!empty($post->tags))
                        <div class="mt-5">
                            <h4 class="fw-semibold">Tags</h4>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($post->tags as $tag)
                                    <span class="badge-soft">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </article>
            </div>

            <div class="col-xxxl-3 col-lg-4 col-md-5">
                <div class="ul-sidebar">
                    <div class="ul-blog">
                        <div class="ul-blog-txt">
                            <h3 class="ul-blog-title">More from Glamer</h3>
                            <ul class="ul-products-categories-link mb-0">
                                @foreach($relatedPosts as $related)
                                    <li>
                                        <a href="{{ route('blog.details', $related->slug ?? $related->id) }}">
                                            <span><i class="flaticon-arrow-point-to-right"></i> {{ $related->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.page>
@endsection
