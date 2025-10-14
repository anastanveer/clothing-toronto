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
                            <form action="#" class="ul-contact-form">
                                <div class="row">
                                    <!-- email -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="email" name="email" id="email" placeholder="Enter Email Address">
                                        </div>
                                    </div>

                                    <!-- password -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="password" name="password" id="password" placeholder="Enter Password">
                                        </div>
                                    </div>
                                </div>
                                <!-- submit btn -->
                                <button type="submit">Log In</button>
                            </form>

                            <p class="text-center mt-4 mb-0">Already have an account? <a href="{{ route('signup') }}">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-layout.page>
@endsection
