@extends('admin.layouts.app')

@section('title', 'Create product | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">New product</h1>
            <p class="text-secondary mb-0">Craft a new highlight for your clothing line.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.products.form')
        <div class="text-end mt-4">
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Save product</button>
        </div>
    </form>
@endsection
