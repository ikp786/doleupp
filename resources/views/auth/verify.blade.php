@extends('layouts.public')

@section('title', 'Verify')

@section('meta')
    <meta content="Verify" name="description">
    <meta content="Verify" name="keywords">
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
                                <h3>{{ __('Verify Your Email Address') }}</h3>
                            </div>

                            <div class="">
                                @if (session('resent'))
                                    <div class="alert alert-success" role="alert">
                                        {{ __('A fresh verification link has been sent to your email address.') }}
                                    </div>
                                @endif

                                {{ __('Before proceeding, please check your email for a verification link.') }}
                                {{ __('If you did not receive the email') }},
                                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
