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

                        <p class="text-center mt-4 mb-0">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
