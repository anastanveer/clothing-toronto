@extends('admin.layouts.app')

@section('title', 'Edit Alert | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Edit alert</h1>
            <p class="text-secondary mb-0">Update copy, timing, or CTA without disrupting the feed.</p>
        </div>
    </div>

    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.announcements.form')
    </form>
@endsection
