@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
<x-layout.page>
    <x-page.header
        title="Sign Up"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Sign Up', 'is_current' => true],
        ]"
    />


        <div class="ul-container">
            <div class="ul-inner-page-container">
                <div class="row justify-content-evenly align-items-center flex-column-reverse flex-md-row">
                    <div class="col-md-5">
                        <div class="ul-login-img text-center">
                            <img src="{{ asset('assets/img/login-img.svg') }}" alt="Login Image">
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-7">
                        <form action="{{ route('signup.submit') }}" method="POST" class="ul-contact-form">
                            @csrf
                            <div class="row">
                                <!-- email -->
                                <div class="form-group">
                                    <div class="position-relative">
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            value="{{ old('name') }}"
                                            placeholder="Full Name"
                                            required
                                            autocomplete="name"
                                        >
                                    </div>
                                    @error('name')
                                        <span class="ul-form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="position-relative">
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            value="{{ old('email') }}"
                                            placeholder="Enter Email Address"
                                            required
                                            autocomplete="email"
                                        >
                                        <span class="field-icon"><i class="flaticon-email"></i></span>
                                    </div>
                                    @error('email')
                                        <span class="ul-form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- password -->
                                <div class="form-group">
                                    <div class="position-relative">
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            placeholder="Enter Password"
                                            required
                                            autocomplete="new-password"
                                        >
                                        <span class="field-icon"><i class="flaticon-lock"></i></span>
                                    </div>
                                    @error('password')
                                        <span class="ul-form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- CONFIRM PASSWORD -->
                                <div class="form-group">
                                    <div class="position-relative">
                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            placeholder="Confirm Password"
                                            required
                                            autocomplete="new-password"
                                        >
                                        <span class="field-icon"><i class="flaticon-lock"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!-- submit btn -->
                            <button type="submit">Sign Up</button>
                        </form>

                        <div class="ul-social-login mt-4">
                            <p class="ul-social-login__eyebrow">Or join instantly with</p>
                            <div class="ul-social-login__actions">
                                <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="ul-social-login__btn ul-social-login__btn--google">
                                    <span class="ul-social-login__icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 10.2c0-.63-.05-1.25-.15-1.84H10v3.49h5.1a4.37 4.37 0 0 1-1.9 2.84v2.37h3.06c1.79-1.65 2.74-4.09 2.74-6.86Z" fill="#4285F4"/>
                                            <path d="M10 20c2.58 0 4.75-.85 6.34-2.3l-3.06-2.37c-.85.57-1.95.9-3.28.9-2.52 0-4.67-1.7-5.43-3.98H1.4v2.49A9.99 9.99 0 0 0 10 20Z" fill="#34A853"/>
                                            <path d="M4.57 12.35A5.98 5.98 0 0 1 4.26 10c0-.82.15-1.61.31-2.35V5.16H1.4a10 10 0 0 0 0 9.68l3.17-2.49Z" fill="#FBBC05"/>
                                            <path d="M10 3.96c1.41 0 2.66.49 3.64 1.46l2.71-2.7C14.75 1 12.58 0 10 0 6.13 0 2.79 2.21 1.4 5.16l3.17 2.49C5.34 5.47 7.49 3.96 10 3.96Z" fill="#EA4335"/>
                                        </svg>
                                    </span>
                                    <span>Google</span>
                                </a>
                                <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="ul-social-login__btn ul-social-login__btn--facebook">
                                    <span class="ul-social-login__icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.4 20v-8.7h2.92l.44-3.39h-3.36v-2.16c0-.98.27-1.64 1.68-1.64h1.8V.13C14.4.08 13.2 0 11.84 0 9.03 0 7.09 1.66 7.09 4.71v3.2H4v3.39h3.09V20h4.31Z"/>
                                        </svg>
                                    </span>
                                    <span>Facebook</span>
                                </a>
                            </div>
                            <p class="small text-muted mb-0">Use Google or Facebook to create and sign in to your Glamer account with one tap.</p>
                        </div>

                        <p class="text-center mt-4 mb-0">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
