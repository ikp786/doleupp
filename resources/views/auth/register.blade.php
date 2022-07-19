@php
$referral_code = $_GET['referral_code'] ?? '';
session()->put('referral_code', $referral_code);
@endphp
@extends('layouts.public')

@section('title', 'Register')

@section('meta')
    <meta content="Register" name="description">
    <meta content="Register" name="keywords">
@endsection

@section('style')
    <link rel="stylesheet" href="assets/css/intlTelInput.css">
    <style>
        .iti--separate-dial-code .iti__selected-flag {
            background: none !important;
        }
    </style>
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            <div class="container">
                <div class="section-title">
                    <h3>Sign Up</h3>
                </div>
                <input type="tel" id="phone" name="phone" required>

                @error('phone')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button type="submit">Continue</button>
                {{--<p class="d-block text-center mt-3">or</p>
                <a href="{{ route('auth.provider', ['provider' => 'facebook']) }}" class="fb-login"><img src="{{ asset('assets/img/facebook.svg') }}" alt="">&nbsp; Continue with Facebook</a>
                <a href="{{ route('auth.provider', ['provider' => 'google']) }}" class="fb-login mt-3"><img src="{{ asset('assets/img/google.svg') }}" alt="">&nbsp; Continue with Google</a>
                <a href="{{ route('login.byemail') }}" class="fb-login mt-3"><img src="{{ asset('assets/img/email.svg') }}" alt="">&nbsp; Sign In With Email</a>--}}
                <div class="alrdy-act">Already have an account? <a href="{{ route('login') }}">Sign In</a></div>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('script')
    <script src="assets/js/intlTelInput.js"></script>
    <script>
        var input = window.intlTelInput(document.querySelector("#phone"), {
            separateDialCode: true,
            preferredCountries: ['in', 'us', 'gb'],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });//.setCountry("in");

        $('#registerForm').on('submit', function(e) {
            e.preventDefault();

            var my_phone = document.querySelector("#phone");
            if (my_phone.value.trim()) {
                if (input.isValidNumber()) {
                    var phone = $('#phone').val();
                    var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
                    var country_code = full_number.replace(phone,'');

                    $.ajax({
                        type: "POST",
                        url: '{{ route('register') }}',
                        data: {phone:phone, country_code:country_code, _token: "{{ csrf_token() }}", },
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            if (data.success === true) {
                                toastr.success(data.message)
                                setTimeout(function () {
                                    window.location.href = 'otp-verification/' + data.data.token + '?country_code='+ data.data.country_code +'&phone=' + data.data.phone;
                                }, 2000);
                            } else {
                                toastr.error(data.message)
                            }
                        }
                    });
                } else {
                    toastr.error('Invalid phone number.');
                }
            }
        });
    </script>
@endsection
