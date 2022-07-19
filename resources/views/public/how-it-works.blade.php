@extends('layouts.public')

@section('title', 'How it works')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="reels-detail howit-works">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">DoleUpp How It Works</h1>
                    <div class="mt-2">
                        {!! $settings->onboarding_text ?? '' !!}
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide mt-5" data-bs-ride="carousel">
                        {{--<div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>--}}
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                {{--{{ $settings }}--}}
                                <video src="{{ $settings->onboarding_video ?? '' }}" width="100%" poster="{{ $settings->thumbnail ?? '' }}" controls>
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            {{--<div class="carousel-item active">
                                <img src="assets/img/slider1.png" class="img-fluid" alt="">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/img/slider1.png" class="img-fluid" alt="">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/img/slider1.png" class="img-fluid" alt="">
                            </div>--}}
                        </div>
                        {{--<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>--}}
                    </div>
              </div>
            </div>

            <div class="mt-5">
                {!! $settings->onboarding_text_2 ?? '' !!}
            </div>

            <div class="featrs">
                <div class="row">
                    <div class="col-md-4 px-5 text-left">
                        <img src="assets/img/one.svg">
                        <h3>Start your DoleUpp request</h3>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Set your DoleUpp request goal</p>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Tell your story</p>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Add a picture or video</p>
                    </div>
                    <div class="col-md-4 px-5 text-left">
                        <img src="assets/img/two.svg">
                        <h3>Share with friends</h3>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Set your DoleUpp request goal</p>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Tell your story</p>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Add a picture or video</p>
                    </div>
                    <div class="col-md-4 px-5 text-left">
                        <img src="assets/img/three.svg">
                        <h3>Manage donations</h3>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Accept donations</p>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Thank donors</p>
                        <p><img src="assets/img/dbl-arow.svg">&nbsp; Withdraw funds</p>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                {!! $settings->onboarding_text_3 ?? '' !!}
            </div>

        </div>
    </main><!-- End #main -->
    @include('public.footer')
@endsection

@section('script')

@endsection
