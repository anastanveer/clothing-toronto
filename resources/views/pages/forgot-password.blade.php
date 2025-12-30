@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<x-layout.page>
    <x-page.header
        title="Forgot Password"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Forgot Password', 'is_current' => true],
        ]"
    />

    <div class="ul-container py-5">
        <div class="ul-inner-page-container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7">
                    <div class="ul-card p-4">
                        <h3 class="mb-2">Reset your password</h3>
                        <p class="text-secondary">Enter the email tied to your Glamer account. We’ll send you a secure link to set a new password.</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="status">{{ session('status') }}</div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST" class="ul-contact-form">
                            @csrf
                            <div class="form-group">
                                <div class="position-relative">
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        placeholder="you@example.com"
                                        required
                                        autocomplete="email"
                                    >
                                    <span class="field-icon"><i class="flaticon-email"></i></span>
                                </div>
                                @error('email')
                                    <span class="ul-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit">Send reset link</button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Remembered it? <a href="{{ route('login') }}">Back to login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.page>
@endsection
