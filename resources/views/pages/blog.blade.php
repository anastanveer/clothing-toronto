@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<x-layout.page>
    <x-page.header
        title="Blog"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Blog', 'is_current' => true],
        ]"
    />

    <section>
        <div class="ul-inner-page-container">
            <div class="row ul-bs-row row-cols-md-3 row-cols-2 row-cols-xxs-1">
                @forelse($posts as $post)
                    <div class="col">
                        <div class="ul-blog">
                            <div class="ul-blog-img">
                                <img src="{{ asset($post->featured_image ?? 'assets/img/blog-1.jpg') }}" alt="{{ $post->title }}">

                                <div class="date">
                                    <span class="number">{{ optional($post->published_at)->format('d') ?? now()->format('d') }}</span>
                                    <span class="txt">{{ optional($post->published_at)->format('M') ?? now()->format('M') }}</span>
                                </div>
                            </div>

                            <div class="ul-blog-txt">
                                <div class="ul-blog-infos flex gap-x-[30px] mb-[16px]">
                                    <div class="ul-blog-info">
                                        <span class="icon"><i class="flaticon-user-2"></i></span>
                                        <span class="text font-normal text-[14px] text-etGray">{{ $post->author->name ?? 'Editorial Team' }}</span>
                                    </div>
                                </div>

                                <h3 class="ul-blog-title"><a href="{{ route('blog.details', $post->slug ?? $post->id) }}">{{ $post->title }}</a></h3>
                                <p class="ul-blog-descr">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 110) }}</p>

                                <a href="{{ route('blog.details', $post->slug ?? $post->id) }}" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="py-5 text-center text-muted fw-semibold">No stories have been published yet. Check back soon.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layout.page>
@endsection
