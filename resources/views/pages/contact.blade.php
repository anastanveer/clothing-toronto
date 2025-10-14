@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<x-layout.page>
    <x-page.header
        title="Contact Us"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Contact Us', 'is_current' => true],
        ]"
    />


        <!-- CONTACT INFO SECTION START -->
        <section class="ul-contact-infos">
            <!-- single contact info -->
            <div class="ul-contact-info">
                <div class="icon"><i class="flaticon-location"></i></div>
                <div class="txt">
                    <h6 class="title">Our Address</h6>
                    <p class="descr mb-0">2715 Ash Dr. San Jose, South Dakota 83475</p>
                </div>
            </div>

            <!-- single contact info -->
            <div class="ul-contact-info">
                <div class="icon"><i class="flaticon-email"></i></div>
                <div class="txt">
                    <h6 class="title">Email Address</h6>
                    <p class="descr mb-0">
                        <a href="mailto:info@ticstube.com">info@ticstube.com</a>
                        <a href="mailto:contact@ticstube.com">contact@ticstube.com</a>
                    </p>
                </div>
            </div>

            <!-- single contact info -->
            <div class="ul-contact-info">
                <div class="icon"><i class="flaticon-stop-watch-1"></i></div>
                <div class="txt">
                    <h6 class="title">Hours of Operation</h6>
                    <p class="descr mb-0">
                        <span>Sunday-Fri: 9 AM – 6 PM</span><br>
                        <span>Saturday: 9 AM – 4 PM</span>
                    </p>
                </div>
            </div>
        </section>
        <!-- CONTACT INFO SECTION END -->


        <!-- MAP AREA START -->
        <div class="ul-contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8684.030842913655!2d90.36627512368048!3d23.776418440774698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8f3f608843b%3A0xf2c71ff392721324!2sLiberation%20War%20Museum!5e0!3m2!1sen!2sbd!4v1730028096808!5m2!1sen!2sbd" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <!-- MAP AREA END -->

        <div class="ul-contact-from-section">
            <div class="ul-contact-form-container">
                <h3 class="ul-contact-form-container__title">Get in Touch</h3>
                <form action="#" class="ul-contact-form">
                    <div class="grid">
                        <!-- firstname -->
                        <div class="form-group">
                            <div class="position-relative">
                                <input type="text" name="firstname" id="firstname" placeholder="First Name">
                                <span class="field-icon"><i class="flaticon-user"></i></span>
                            </div>
                        </div>

                        <!-- lastname -->
                        <div class="form-group">
                            <div class="position-relative">
                                <input type="text" name="lastname" id="lastname" placeholder="Last Name">
                                <span class="field-icon"><i class="flaticon-user"></i></span>
                            </div>
                        </div>

                        <!-- phone -->
                        <div class="form-group">
                            <div class="position-relative">
                                <input type="tel" name="phone-number" id="phone-number" placeholder="Phone Number">
                                <span class="field-icon"><i class="flaticon-user"></i></span>
                            </div>
                        </div>
                        <!-- email -->
                        <div class="form-group">
                            <div class="position-relative">
                                <input type="email" name="email" id="email" placeholder="Enter Email Address">
                                <span class="field-icon"><i class="flaticon-email"></i></span>
                            </div>
                        </div>
                        <!-- message -->
                        <div class="form-group">
                            <div class="position-relative">
                                <textarea name="message" id="message" placeholder="Write Message..."></textarea>
                                <span class="field-icon"><i class="flaticon-edit"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- submit btn -->
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
</x-layout.page>
@endsection
