@extends('layouts.public')

@section('title', 'Change Password')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="privacy-policy profile-edt">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">

                <div class="icon-box">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-hdr">
                                Profile - Password Change
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-10 p-5">
                            <div class="text-center"><img src="assets/img/profile-pic.png" alt=""></div>

                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <input type="text" class="mb-0" placeholder="New Password" name="uname">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="mb-0" placeholder="Confirm Password" name="uname">
                                </div>
                                <div class="col-md-12 text-center mt-4">
                                    <button type="submit" class="d-inline-block">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')

@endsection
