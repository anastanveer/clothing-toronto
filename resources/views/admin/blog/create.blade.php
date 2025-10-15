@extends('admin.layouts.app')

@section('title', 'Create story | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">New editorial</h1>
            <p class="text-secondary mb-0">Share the narrative behind your latest collection.</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST">
        @csrf
        @include('admin.blog.form')
        <div class="text-end mt-4">
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Publish story</button>
        </div>
    </form>
@endsection
