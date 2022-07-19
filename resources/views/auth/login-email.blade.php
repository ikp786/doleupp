@extends('layouts.public')

@section('title', 'Login')

@section('meta')
    <meta content="Login" name="description">
    <meta content="Login" name="keywords">
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <style>
        .passwordeyespan {
            float: right;
            margin-right: 12px;
            margin-top: -38px;
            cursor: pointer;
            position: relative;
            z-index: 2;
        }
    </style>
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" method="POST" action="{{ route('login.email') }}">
            @csrf
            <div class="container">
                <div class="section-title">
                    <h3>{{ __('Sign In') }}</h3>
                </div>
                <input id="email" type="text" placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                @error('email')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <input id="password" type="password" placeholder="Password" name="password" value="{{ old('password') }}" autocomplete="current-password">
                <i class="far fa-eye-slash passwordeyespan" id="togglePassword"></i>
                @error('password')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <br>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                <span class="psw">{{ __('Forgot') }} <a href="{{ route('password.request') }}">{{ __('password?') }}</a></span>
                <button type="submit">{{ __('Login') }}</button>
                {{-- <p class="d-block text-center mt-3">{{ __('or') }}</p>
                <a href="{{ route('auth.provider', ['provider' => 'facebook']) }}" class="fb-login"><img src="{{ asset('assets/img/facebook.svg') }}" alt="">&nbsp; {{ __('Continue with Facebook') }}</a>
                <a href="{{ route('auth.provider', ['provider' => 'google']) }}" class="fb-login mt-3"><img src="{{ asset('assets/img/google.svg') }}" alt="">&nbsp; {{ __('Continue with Google') }}</a> --}}
                <div class="alrdy-act">{{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Sign up') }}</a></div>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('layouts.footer') --}}
@endsection

@section('script')
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            //this.classList.toggle('fa-eye');
            if(type == 'password') {
                this.classList.add('fa-eye-slash');
                this.classList.remove('fa-eye');
            } else {
                this.classList.add('fa-eye');
                this.classList.remove('fa-eye-slash');
            }
        });
    </script>

@endsection

