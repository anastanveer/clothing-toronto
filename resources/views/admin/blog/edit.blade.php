@extends('admin.layouts.app')

@section('title', 'Edit story | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Edit editorial</h1>
            <p class="text-secondary mb-0">Keep the content as fresh as your garments.</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.blog.form', ['post' => $post])
        <div class="d-flex justify-content-between mt-4">
            <span class="text-secondary small">Last updated {{ $post->updated_at->diffForHumans() }}</span>
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Update story</button>
        </div>
    </form>
@endsection
