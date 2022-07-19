@if(auth()->user()->subscription_ends_at == NULL)
    <script>window.location = "{{ route('subscription') }}";</script>
    {{ exit(); }}
@endif

@extends('layouts.public')

@section('title', 'Subscription Renew')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <div class="modal-content animate" action="/action_page.php" method="post">
            <div class="container">
                <div class="section-title">
                    <h3 style="color: #FF0000;">
                        @if(auth()->user()->subscription_ends_at < \Carbon\Carbon::now())
                            Subscription Expired at {{ auth()->user()->subscription_ends_at }}
                        @else
                            @php
                            $date = \Carbon\Carbon::parse(auth()->user()->subscription_ends_at);
                            $now = \Carbon\Carbon::now();
                            $diff = $date->diffInDays($now);
                            @endphp
                            Subscription Expiring in {{ $diff }} Days
                        @endif
                    </h3>
                </div>
                <div class="subscription">
                    <span>Yearly Subscription</span>
                    <h2>$60.00</h2>
                    <p>per year</p>
                    <a class="bg-grn" href="{{ route('subscription.payment') }}"
                        onclick="event.preventDefault();
                            document.getElementById('subscription-payment').submit();">
                        {{ __('Renew Subscription') }}
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
