@extends('admin.layouts.app')

@section('title', 'Create brand | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">New brand</h1>
            <p class="text-secondary mb-0">Launch a fresh perspective for your storefront.</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Back to brands</a>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.brands.form')
        <div class="text-end mt-4">
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Save brand</button>
        </div>
    </form>
@endsection
