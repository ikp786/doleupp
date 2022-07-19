@extends('layouts.public')

@section('title', 'News')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="fundraisers lazor-news">

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

                @include('public.news')

                {{--<a href="" class="see-mor text-center d-block">View More <img src="assets/img/dbl-arow.svg"></a>--}}

            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

{{--    @include('public.footer')--}}
@endsection

@section('script')
    <script type="text/javascript">
        var page = 1;
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        });

        function loadMoreData(page){
            $.ajax({
                url: '?page=' + page,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();
                }
            })
                .done(function(data){
                    if(data.html == " " || data.html == ""){
                        $('.ajax-load').html("No more records found");
                        return;
                    }
                    $('.ajax-load').hide();
                    $("#post-data").append(data.html);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError){
                    toastr.options = { "progressBar" : true }
                    toastr.warning('server not responding...')
                });
        }
    </script>
@endsection
