@extends('admin.layouts.app')

@section('title', 'Create coupon | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">New coupon</h1>
            <p class="text-secondary mb-0">Inspire another wardrobe story with a limited perk.</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Back to coupons</a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="card border-0 shadow-sm p-4">
        @csrf
        @include('admin.coupons.form')
        <div class="text-end mt-4">
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Save coupon</button>
        </div>
    </form>
@endsection
