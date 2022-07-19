@extends('layouts.public')

@section('title', 'Login')

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
                <div class="section-title">
                    <h3>Sign In</h3>
                </div>
                <input type="text" placeholder="Username" name="uname" required>
                <input type="password" placeholder="Password" name="psw" required>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                </div>
                <span class="psw">Forgot <a href="#">password?</a></span>
                <button onclick="location.href='verify'" type="button">Login</button>
                <p class="d-block text-center mt-3">or</p>
                <a href="" class="fb-login"><img src="assets/img/facebook.svg" alt="">&nbsp; Continue with Facebook</a>
                <div class="alrdy-act">Don't have an account? <a href="signup">Sign up</a></div>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')

@endsection
