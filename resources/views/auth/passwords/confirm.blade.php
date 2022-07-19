@extends('layouts.public')

@section('title', 'Confirm')

@section('meta')
    <meta content="Confirm" name="description">
    <meta content="Confirm" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')
    <main id="main" class="login-pg">
        <div class="modal-content animate">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="">
                            <div class="section-title">
                                <h3>{{ __('Confirm Password') }}</h3>
                            </div>

                            <div class="">
                                {{ __('Please confirm your password before continuing.') }}

                                <form method="POST" action="{{ route('password.confirm') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="password"
                                            class="col-md-12 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-12">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-12">
                                            <button type="submit">
                                                {{ __('Confirm Password') }}
                                            </button>

                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
