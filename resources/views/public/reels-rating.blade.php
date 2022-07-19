@extends('layouts.public')

@section('title', 'Rating')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <style>
        .modal-content {
            padding: 25px 70px !important;
        }
        .rating {
            /*font-size: 45px;*/
            /*height: 60px;*/
            /*line-height: 60px;*/
            margin: 40px 0px 30px 0px;
            border: 1px solid #000000;
            border-radius: 20px;
            padding: 40px 0px 30px 0px;
            height: auto;
        }
        .rating input {
            display: none;
        }
        .rating label font {
            color: rgba(100%, 0%, 0%, 0.05);
            text-shadow: 0 0 0 #DFDFDF;
        }
        .rating1:hover:before,
        .rating1.active:before {
            content: url({{asset('images/emojis/angry.svg')}});
            position: absolute;
            /*font-size: 45px;*/
            margin-left: -22px;
            /*-webkit-transform: scale(1.2);*/
            /*-webkit-transform: rotate(30deg);*/
        }
        .rating2:hover:before,
        .rating2.active:before {
            content: url({{asset('images/emojis/sad.svg')}});
            position: absolute;
            margin-left: -22px;
        }
        .rating3:hover:before,
        .rating3.active:before {
            content: url({{asset('images/emojis/confused.svg')}});
            position: absolute;
            margin-left: -23px;
        }
        .rating4:hover:before,
        .rating4.active:before {
            content: url({{asset('images/emojis/angel.svg')}});
            position: absolute;
            margin-left: -23px;
        }
        .rating5:hover:before,
        .rating5.active:before {
            content: url({{asset('images/emojis/in-love.svg')}});
            position: absolute;
            margin-left: -23px;
        }
    </style>
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="{{ route('reels.rating') }}" method="post">
            @csrf
            <div class="container">
                <div class="section-title pb-0">
                    <h3>Rate your experience</h3>
                    {{--<p class="w-100">Please Select One</p>--}}
                    <input name="donation_request_id" value="{{ $donations }}" type="hidden">
                    <input name="type" value="{{ $type }}" type="hidden">
                    <div class="rating">
                        <input type="radio" name="rating" id="rating01" value="1"/>
                        <label for="rating01"><span class="rating1"><div style="content: url({{asset('images/emojis/angry-black.svg')}})"></div></span></label>
                        <input type="radio" name="rating" id="rating02" value="2"/>
                        <label for="rating02"><span class="rating2"><div style="content: url({{asset('images/emojis/sad-black.svg')}})"></div></span></label>
                        <input type="radio" name="rating" id="rating03" value="3"/>
                        <label for="rating03"><span class="rating3"><div style="content: url({{asset('images/emojis/confused-black.svg')}})"></div></span></label>
                        <input type="radio" name="rating" id="rating04" value="4"/>
                        <label for="rating04"><span class="rating4"><div style="content: url({{asset('images/emojis/angel-black.svg')}})"></div></span></label>
                        <input type="radio" name="rating" id="rating05" value="5"/>
                        <label for="rating05"><span class="rating5"><div style="content: url({{asset('images/emojis/in-love-black.svg')}})"></div></span></label>
                        {{--<span class="rating1"><font>😠</font></span>
                        <span class="rating2"><font>🙁</font></span>
                        <span class="rating3"><font>😐</font></span>
                        <span class="rating4"><font>🙂</font></span>
                        <span class="rating5"><font>😍</font></span>--}}
                    </div>
                    <button type="submit">Submit</button>
                </div>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')
    <script>
        $(".rating span").click(function(){
            $('.rating span').removeClass("active");
            $(this).addClass("active");
        });
    </script>
@endsection
