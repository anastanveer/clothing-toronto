@extends('layouts.app')

@section('title', 'Blog Classic')

@section('content')
<x-layout.page>
    <x-page.header
        title="Blog Standard"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Blog Standard', 'is_current' => true],
        ]"
    />

    <section>
        <div class="ul-inner-page-container">
            <div class="row ul-bs-row">
                <div class="col-xxxl-9 col-lg-8 col-md-7">
                    <div>
                        @forelse($posts as $post)
                            <div class="ul-blog ul-blog-big">
                                <div class="ul-blog-img">
                                    <img src="{{ asset($post->featured_image ?? 'assets/img/blog-big-img-1.jpg') }}" alt="{{ $post->title }}">

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
                                        <div class="ul-blog-info">
                                            <span class="icon"><i class="flaticon-calendar"></i></span>
                                            <span class="text font-normal text-[14px] text-etGray">{{ optional($post->published_at)->format('M d, Y') ?? 'Draft' }}</span>
                                        </div>
                                    </div>

                                    <h3 class="ul-blog-title"><a href="{{ route('blog.details', $post->slug ?? $post->id) }}">{{ $post->title }}</a></h3>
                                    <p class="ul-blog-descr">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 220) }}</p>

                                    <a href="{{ route('blog.details', $post->slug ?? $post->id) }}" class="ul-blog-btn ul-blog-big-btn">Read More <span class="icon"><i class="flaticon-up-right-arrow"></i></span></a>
                                </div>
                            </div>
                        @empty
                            <div class="py-5 text-center text-muted fw-semibold">No stories available.</div>
                        @endforelse

                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>

                <div class="col-xxxl-3 col-lg-4 col-md-5">
                    <div class="ul-sidebar">
                        <div class="ul-blog">
                            <div class="ul-blog-txt">
                                <h3 class="ul-blog-title">About Glamer</h3>
                                <p class="ul-blog-descr mb-0">Curated notes from the studio and seasonal inspirations for the modern wardrobe. Discover upcoming launches before anyone else.</p>
                            </div>
                        </div>

                        <div class="ul-blog">
                            <div class="ul-blog-txt">
                                <h3 class="ul-blog-title">Categories</h3>
                                <ul class="ul-products-categories-link mb-0">
                                    <li><a href="{{ route('shop') }}"><span><i class="flaticon-arrow-point-to-right"></i> Lookbooks</span></a></li>
                                    <li><a href="{{ route('shop') }}"><span><i class="flaticon-arrow-point-to-right"></i> Designer Notes</span></a></li>
                                    <li><a href="{{ route('shop') }}"><span><i class="flaticon-arrow-point-to-right"></i> Care Guides</span></a></li>
                                    <li><a href="{{ route('shop') }}"><span><i class="flaticon-arrow-point-to-right"></i> Launch Announcements</span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="ul-blog">
                            <div class="ul-blog-txt">
                                <h3 class="ul-blog-title">Stay connected</h3>
                                <p class="ul-blog-descr">Follow @glamer for daily edits and backstage teasers.</p>
                                <div class="d-flex gap-2">
                                    <a href="#" class="ul-btn">Instagram</a>
                                    <a href="#" class="ul-btn">Pinterest</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout.page>
@endsection
