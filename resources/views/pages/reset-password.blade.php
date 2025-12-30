@extends('layouts.app')

@section('title', 'Create New Password')

@section('content')
<x-layout.page>
    <x-page.header
        title="Create New Password"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Reset Password', 'is_current' => true],
        ]"
    />

    <div class="ul-container py-5">
        <div class="ul-inner-page-container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7">
                    <div class="ul-card p-4">
                        <h3 class="mb-2">Choose a new password</h3>
                        <p class="text-secondary">Create a strong password to secure your Glamer account.</p>
                        <form action="{{ route('password.update') }}" method="POST" class="ul-contact-form">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <div class="position-relative">
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email', $email) }}"
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
                            <div class="form-group">
                                <div class="position-relative">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="New password"
                                        required
                                        autocomplete="new-password"
                                    >
                                    <span class="field-icon"><i class="flaticon-lock"></i></span>
                                </div>
                                @error('password')
                                    <span class="ul-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="position-relative">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        placeholder="Confirm new password"
                                        required
                                        autocomplete="new-password"
                                    >
                                    <span class="field-icon"><i class="flaticon-lock"></i></span>
                                </div>
                            </div>
                            <button type="submit">Update password</button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Back to <a href="{{ route('login') }}">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.page>
@endsection
