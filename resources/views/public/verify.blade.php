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
        <form class="modal-content animate" action="/action_page.php" method="post">
            <div class="container">
                <div class="section-title pb-0">
                    <h3>Verification Code</h3>
                    <p class="w-100">Please Enter the Verification Code</p>
                </div>
                <input class="text-center" type="text" placeholder="0" name="uname" required>
                <button onclick="location.href='signup1'" type="button">Verify</button>
                <span class="psw w-100 text-center mt-3"><a href="#">Resend OTP</a></span>
            </div>
        </form>

    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')

@endsection
