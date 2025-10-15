@extends('admin.layouts.app')

@section('title', 'Edit product | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Edit product</h1>
            <p class="text-secondary mb-0">Fine tune the details before it hits the storefront.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products.form', ['product' => $product])
        <div class="d-flex justify-content-between mt-4">
            <span class="text-secondary small">Last updated {{ $product->updated_at->diffForHumans() }}</span>
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Update product</button>
        </div>
    </form>
@endsection
