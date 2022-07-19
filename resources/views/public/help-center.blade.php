@extends('layouts.public')

@section('title', 'Help Center')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="assets/js/accordion.js"></script>
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="privacy-policy profile">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">

                <div class="icon-box">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-hdr d-block">
                                Help Center
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-10 p-5">
                            <div id="content" class="tab-content" role="tablist">
                                <div class="card">
                                    <div class="card-body">
                                        {{--<div class="border rounded p-5 text-center mb-3">
                                            <h2>Can't Find What You're Looking For?</h2>
                                            <a href="" class="btn-get-started text-center">Ask Us A Question</a>
                                        </div>--}}

                                        <h4>Help Center</h4>
                                        <div class="accordion mt-2">
                                            @foreach($faqs as $key => $faq)
                                                <div class="accordion-head">
                                                    <h4>Q{{ $key+1 }}. {{ $faq->question ?? '' }}</h4>
                                                    <div class="arrow down"></div>

                                                </div>
                                                <div class="accordion-body">
                                                    <p>{{ $faq->answer ?? '' }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

    @include('public.footer')
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript">
        $('.accordion').each(function () {
            var $accordian = $(this);
            $accordian.find('.accordion-head').on('click', function () {
                $(this).removeClass('open').addClass('close');
                $accordian.find('.accordion-body').slideUp();
                if (!$(this).next().is(':visible')) {
                    $(this).removeClass('close').addClass('open');
                    $(this).next().slideDown();
                }
            });
        });
    </script>
@endsection
