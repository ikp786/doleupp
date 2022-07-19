@extends('layouts.public')

@section('title', 'Email')

@section('meta')
    <meta content="Email" name="description">
    <meta content="Email" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')
    <main id="main" class="login-pg">
        <form class="modal-content animate" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="container">
                <div class="section-title">
                    <h3>{{ __('Reset Password') }}</h3>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <input id="email" type="text" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <button type="submit">{{ __('Send Password Reset Link') }}</button>
            </div>
        </form>
    </main><!-- End #main -->
@endsection
