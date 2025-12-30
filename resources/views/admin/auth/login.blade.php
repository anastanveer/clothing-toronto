@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<x-layout.page>
    <x-page.header
        title="Admin Login"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Admin Login', 'is_current' => true],
        ]"
    />

    <div class="ul-container py-5">
        <div class="ul-login">
            <div class="ul-inner-page-container">
                <div class="row justify-content-evenly align-items-center flex-column-reverse flex-md-row">
                    <div class="col-md-5">
                        <div class="ul-login-img text-center">
                            <img src="{{ asset('assets/img/login-img.svg') }}" alt="Admin Login Illustration">
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-7">
                        <form action="{{ route('admin.login.submit') }}" method="POST" class="ul-contact-form">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <div class="position-relative">
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter Admin Email" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" style="width: 24px; height: 24px; border-radius: 6px;" type="checkbox" value="1" name="remember" id="remember">
                                        <label class="form-check-label small mb-0" for="remember">Remember this device</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.page>
@endsection
