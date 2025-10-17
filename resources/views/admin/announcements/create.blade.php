@extends('admin.layouts.app')

@section('title', 'New Alert | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Create alert</h1>
            <p class="text-secondary mb-0">Build a notification tile for the customer alerts panel.</p>
        </div>
    </div>

    <form action="{{ route('admin.announcements.store') }}" method="POST">
        @include('admin.announcements.form')
    </form>
@endsection
