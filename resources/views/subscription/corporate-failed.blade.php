@extends('layouts.public')

@section('title', 'Corporate DoleUpp - Failed')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <div class="modal-content animate" method="post">
            <div class="container">
                <div class="section-title pb-0">
                    <h4>Oops</h4>
                    <p class="w-100">The donation amount is directly send to the DoleUpp app, DoleUpp will immediately send the donations out to their donation category of choice (to different users) and confirmation of all the users that we donated to on the donor's behalf will be shared to you through email.</p>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('my-script')
@endsection
