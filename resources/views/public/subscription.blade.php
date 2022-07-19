@if(auth()->user()->subscription_ends_at != NULL)
    <script>window.location = "{{ route('subscription-renew') }}";</script>
    {{ exit(); }}
@endif

@extends('layouts.public')

@section('title', 'Subscription')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <div class="modal-content animate" action="#" method="post">
            <div class="container">
                <div class="section-title">
                    <h3>Accept Recipient Subscription</h3>
                </div>
                <div class="subscription">
                    <span>Yearly Subscription</span>
                    <h2>$60.00</h2>
                    <p>per year</p>
                    <a href="{{ route('home') }}">Donor $0</a>
                    <a class="bg-grn" href="{{ route('subscription.payment') }}"
                        onclick="event.preventDefault();
                            document.getElementById('subscription-payment').submit();">
                        {{ __('Accept Subscription') }}
                    </a>

                    <form id="subscription-payment" action="{{ route('subscription.payment') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <a href="{{ route('index') }}">
                        {{ __('Pay Later') }}
                    </a>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')

@endsection
