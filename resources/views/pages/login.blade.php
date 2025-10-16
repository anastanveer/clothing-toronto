@extends('layouts.app')

@section('title', 'Login')

@section('content')
<x-layout.page>
    <x-page.header
        title="Log In"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Log In', 'is_current' => true],
        ]"
    />



        <div class="ul-container">
            <div class="ul-login">
                <div class="ul-inner-page-container">
                    <div class="row justify-content-evenly align-items-center flex-column-reverse flex-md-row">
                        <div class="col-md-5">
                            <div class="ul-login-img text-center">
                                <img src="{{ asset('assets/img/login-img.svg') }}" alt="Login Image">
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-7">
                            <form action="{{ route('login.submit') }}" method="POST" class="ul-contact-form">
                                @csrf
                                <div class="row">
                                    <!-- email -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input
                                                type="email"
                                                name="email"
                                                id="email"
                                                value="{{ old('email', 'customer@glamer.local') }}"
                                                placeholder="Enter Email Address"
                                                required
                                                autocomplete="email"
                                            >
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
                                                value="{{ old('password', 'Customer123!') }}"
                                                placeholder="Enter Password"
                                                required
                                                autocomplete="current-password"
                                            >
                                        </div>
                                        @error('password')
                                            <span class="ul-form-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                    <label class="ul-form-remember">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span>Keep me signed in</span>
                                    </label>
                                    <a href="#" class="ul-form-link">Forgot password?</a>
                                </div>
                                <!-- submit btn -->
                                <button type="submit">Log In</button>
                            </form>

                            <div class="text-center mt-4">
                                <strong>Demo credentials</strong>
                                <p class="mb-0 text-secondary small">Email: customer@glamer.local<br>Password: Customer123!</p>
                            </div>

                            <p class="text-center mt-4 mb-0">New to Glamer? <a href="{{ route('signup') }}">Create an account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
