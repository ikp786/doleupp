@extends('layouts.public')

@section('title', 'Rating - Success')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <div class="modal-content animate" method="post">
            <div class="container">
                <div class="section-title pb-0">
                    <h3 style="color: #93C01F;">Congratulations !</h3>
                    @if(auth()->user())
                        @if(!empty($badge))
                            <img width="120" class="m-5" src="{{ asset('assets/img/badge/'.$badge.'.png') }}">
                            <p class="w-100">You Got {{ucfirst($badge)}} Badge</p>
                        @else
                            <img width="100" class="m-5" src="{{ asset('assets/img/thankyou.png') }}">
                        @endif
                        <p class="w-100">Your Total DoleUpps: {{$donation_send}} DoleUpps.</p>
                    @else
                        <img width="100" class="m-5" src="{{ asset('assets/img/thankyou.png') }}">
                        <p class="w-100">Thank you for your donation.</p>
                    @endif
                    @if($type == 'donation')
                        <a href="{{route('fundraisers')}}" class="btn-get-started scrollto">OKAY</a>
                    @elseif($type == 'donation-request')
                        <a href="{{route('home')}}" class="btn-get-started scrollto">OKAY</a>
                    @elseif($type == 'corporation')
                        <a href="{{route('corporate.success')}}" class="btn-get-started scrollto">CONTINUE</a>
                    @else
                        <a href="{{route('home')}}" class="btn-get-started scrollto">OKAY</a>
                    @endif
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('my-script')
    <script>
        history.pushState(null, null, window.location.href);
        history.back();
        window.onpopstate = () => history.forward();
    </script>
@endsection
