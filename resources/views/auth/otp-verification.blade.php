@extends('layouts.public')

@section('title', 'OTP Verification')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <div class="modal-content animate">
            <div class="container">
                <form action="{{ url('otp-verification') }}" method="post">
                    @csrf
                    <div class="section-title pb-0">
                        <h3>Verification Code</h3>
                        <p class="w-100">Please Enter the Verification Code</p>
                    </div>
                    <input type="hidden" name="token" value="{{ $token }}"/>
                    <input type="hidden" name="phone" value="{{ $phone }}"/>
                    <input class="text-center" type="text" placeholder="0000" name="otp" minlength="4" maxlenght="4" required>
                    <button type="submit">Verify</button>
                </form>
                <a href="{{ route('register') }}"
                   onclick="event.preventDefault();
                                        document.getElementById('register-form').submit();">
                    <span class="psw w-100 text-center mt-3">Resend OTP</span>
                </a>

                <form id="register-form" action="{{ route('register') }}" method="POST" class="d-none">
                    @csrf
                    <input type="hidden" name="resend" value="Yes">
                    <input type="hidden" name="country_code" value="{{ $country_code }}">
                    <input type="hidden" id="phone" name="phone" value="{{ $phone }}">
                </form>
            </div>
        </div>

    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')

@endsection
