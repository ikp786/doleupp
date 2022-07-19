@extends('layouts.public')

@section('title', 'News '.$news->title ?? '')

@section('meta')
    <meta content="{{ $mews->description ?? '' }}" name="description">
    <meta content="{{ $news->title ?? '' }}" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="fundraisers lazor-news lazor-news-detail">

        <!-- ======= Hero Section ======= -->
        <section id="hero" class="d-flex  align-items-center">
            <div class="container text-center" data-aos="zoom-out" data-aos-delay="100">
                <h1>DoleUpp News</h1>
                <h2>It is a long established fact that a reader will be distracted by the readable content of a page when
                    looking at its layout. </h2>
            </div>
        </section><!-- End Hero -->


        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">

                <div class="icon-box">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($news->type == 'image')
                                <a href="{{ $news->imgae }}" class="full-image" style="position: relative; left: 0%; top: 0%;">
                                    <img src="{{ $news->imgae }}" class="reel-img" alt="">
                                </a>
                            @else
                            <!--<a href="{{ $news->video }}" class="ply-btn" data-id="{{ $news->id }}" style="position: relative; left: 0%; top: 0%;">-->
                                <img src="{{ $news->thumbnail }}" class="reel-img" alt="">
                                <!--</a>-->
                                <a href="{{ $news->video }}" class="ply-btn"><img src="{{ asset('assets/img/ply-btn.svg') }}" class="" alt=""></a>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="cat-p border-top-0 mb-4">
                                <p class="grn-col">Category: {{ $news->category->name ?? '' }}</p>
                                <p>{{ date('M d, Y', strtotime($news->created_at)) ?? '' }}</p>
                            </div>
                            <h4>{{ $news->title ?? '' }}</h4>
                            <p>{{ $news->description ?? '' }}</p>

                            <p class="mt-4"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Services Section -->

    </main><!-- End #main -->

    @include('public.footer')
@endsection

@section('script')

@endsection
