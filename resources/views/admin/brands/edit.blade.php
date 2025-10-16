@extends('admin.layouts.app')

@section('title', 'Edit brand | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Edit brand</h1>
            <p class="text-secondary mb-0">Keep the brand story aligned with your latest campaigns.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.brands.toggle-status', $brand) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button class="btn btn-outline-dark">{{ $brand->is_published ? 'Unpublish' : 'Publish' }}</button>
            </form>
            <a href="{{ route('admin.brands.show', $brand) }}" class="btn btn-outline-secondary">View overview</a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Back to brands</a>
        </div>
    </div>

    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.brands.form', ['brand' => $brand])
        <div class="text-end mt-4">
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Update brand</button>
        </div>
    </form>
@endsection
