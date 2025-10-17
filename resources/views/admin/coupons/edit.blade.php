@extends('admin.layouts.app')

@section('title', 'Edit coupon | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Edit coupon</h1>
            <p class="text-secondary mb-0">Fine tune the experience for your insiders.</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Back to coupons</a>
    </div>

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="card border-0 shadow-sm p-4">
        @csrf
        @method('PUT')
        @include('admin.coupons.form')
        <div class="text-end mt-4">
            <button class="ul-btn" style="background: var(--admin-accent); border: none;">Update coupon</button>
        </div>
    </form>
@endsection
