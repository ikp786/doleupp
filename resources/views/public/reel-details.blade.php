@extends('layouts.public')

@section('title', 'Reel Details')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <style>
        div#social-links {
            margin: 0 auto;
            margin-left: -30px;
            max-width: 500px;
        }

        div#social-links ul li {
            display: inline-block;
        }

        div#social-links ul li a {
            padding: 10px;
            /*border: 1px solid #ccc;*/
            margin: 1px;
            font-size: 30px;
            /*color: #222;*/
            /*background-color: #ccc;*/
        }

        div#social-links2 {
            margin: 0 auto;
            margin-left: -60px;
            max-width: 500px;
        }

        div#social-links2 ul li {
            display: inline-block;
        }

        div#social-links2 ul li a {
            padding: 10px;
            /*border: 1px solid #ccc;*/
            margin: 1px;
            font-size: 30px;
            /*color: #222;*/
            /*background-color: #ccc;*/
        }

        .don-req-sbt span {
            color: #93C01F;
            font-size: 27px;
            margin: 5px 10px;
            display: block;
        }

        .don-req-sbt div#social-links ul li a {
            padding: 0;
        }

        #main-icon-div {
            display: -webkit-inline-box;
        }
        .full-ajax-load {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999999;
            overflow: hidden;
            background-color: rgba(255,255,255,0.5);
        }
        .full-ajax-load:before {
            content: "";
            position: fixed;
            top: calc(50% - 30px);
            left: calc(50% - 30px);
            border: 6px solid #93C01F;
            border-top-color: #e2eefd;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            -webkit-animation: animate-preloader 1s linear infinite;
            animation: animate-preloader 1s linear infinite;
        }
    </style>
@endsection

@section('content')
    <div class="full-ajax-load text-center" style="display:none"></div>
    @include('public.header')
    @php $remainamount= $reel->donation_amount- $reel->donation_received; @endphp
    <main id="main" class="reels-detail">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1>{{ $reel->caption ?? '' }}</h1>
                    <div class="icon-box">
                        <div class="icon">
                             {{--<video class="reel-img" width="100%" poster="{{ $reel->thumbnail }}" controls>
                                 <source src="{{ $reel->video }}" type="video/mp4">
                                 <source src="{{ $reel->video }}" type="video/ogg">
                                 <source src="{{ $reel->video }}" type="video/webm">
                                 <source src="{{ $reel->video }}" type="video/webm">
                                 <object data="{{ $reel->video }}" width="100%">
                                    <embed src="{{ $reel->video }}" width="100%">
                                 </object>
                            </video>--}}
                            <img src="{{ $reel->thumbnail }}" class="reel-img-big" alt="" style="min-height: 500px;">
                        </div>
                        <a href="{{ $reel->video }}" class="ply-btn" data-id="{{ $reel->id }}">
                            <img src="{{ asset('assets/img/ply-btn.svg') }}" class="" alt="">
                        </a>
                        @if ($reel->is_prime == 'Yes')
                        <a href="#" class="prmem-tag">
                            <img src="{{ asset('assets/img/prmem-tag.svg') }}" class="" alt="">
                        </a>
                        @endif
                        {{--<a href="javascript:" class="prmem-tag-footer">
                            <img class="{{ ($reel->wishlist_count >= 1) ? 'wishlistRemove' : 'wishlistCreate' }}" data-id="{{ $reel->id }}" src="{{ ($reel->wishlist_count >= 1) ? asset('assets/img/add-holi.svg') : asset('assets/img/add-holi-2.svg') }}" alt="">
                        </a>--}}
                        <div class="reel-views">
                            <img src="{{ asset('assets/img/eye.svg') }}">&nbsp; {{ $reel->views_count ?? 0 }}
                            <div class="rating-icon3">
                                <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="25">
                                {{ number_format($reel->rating_count, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="row ftr">
                        <div class="col-md-4 ftrs"><img src="{{ asset('assets/img/calendar1.svg') }}">&nbsp;
                            Created {{ $reel->created_at->diffInDays(\Carbon\Carbon::now()) ?? 0 }} days ago
                        </div>
                        <div class="col-md-4 ftrs text-center"><img
                                src="{{ asset('assets/img/tag.svg') }}">&nbsp; {{ $reel->category->name }}
                        </div>
                        @if($reel->donation_received < $reel->donation_amount)
                            <div class="col-md-4 ftrs text-center wishlist-show" style="cursor: pointer;">
                                @if($reel->wishlist_count > 0)
                                    <span class="wishlist-remove"><img src="{{ asset('assets/img/add-holi.svg') }}">&nbsp; Added from DoleUpp Cart</span>
                                @else
                                    <span class="wishlist-create"><img src="{{ asset('assets/img/add-holi-2.svg') }}">&nbsp; Add to DoleUpp Cart</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <h6 class="mt-5">
                        {{ $reel->Description ?? '' }}
                    </h6>
                    <div class="row mt-5">
                        <div class="col-md-6 text-end">
                            @if($reel->donation_received < $reel->donation_amount)
                                <a href="javascript:" data-id="{{$reel->id}}" data-amount="{{$remainamount}}"
                                   class="btn-get-started donation-now">DoleUpp Now</a>
                            <!-- <a href="javascript:" data-id="{{$reel->id}}" data-amount="{{$remainamount}}" class="btn-get-started donation-now "  data-bs-toggle="modal" data-bs-target="#exampleModal3">DoleUpp Now</a> -->
                            @endif
                        </div>
                        <div class="col-md-6 see-mor" style="display: contents;" style="margin-left: -20px;">
                            {{--<div id="social-links2">
                                <ul>
                                    <l>
                                        |
                                    </l>
                                    <li>
                                        <a href="sms:?&body={{ $reel->caption.' '.route('reels.show', ['slug' => $reel->id]) }}" class="social-button "><span class="fas fa-sms"></span></a>
                                    </li>
                                    <li>
                                        <a href="mailto:?subject=Share Reel - {{ $reel->caption ?? '' }}&body={{ $reel->caption.' '.route('reels.show', ['slug' => $reel->id]) }}">
                                            <span class="fas fa-envelope"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            {!! $shareReel !!}--}}
                            <a href="" class="see-mor" data-bs-toggle="modal" data-bs-target="#exampleModal2"><img
                                    src="{{ asset('assets/img/sharee.svg') }}"> Share</a>
                        </div>
                    </div>


                    <div class="d-flex justify-content-center row mt-5">
                        <div class="col-md-12">
                            <div class="d-flex flex-column comment-section">
                                <h3>Comments</h3>
                                <div class="comment-box p-3">
                                    <form action="{{ route('comments.store') }}" method="POST" class="commentCreate">
                                        @csrf
                                        <div class="d-flex flex-row align-items-start">
                                            <img class="rounded-circle"
                                                 src="{{ auth()->user()->image ?? 'https://i.imgur.com/RpzrMR2.jpg' }}"
                                                 width="50" height="50">
                                            <input type="hidden" name="donation_request_id" value="{{ $reel->id }}"/>
                                            <input type="hidden" name="comment_type" value="text"/>
                                            <textarea class="form-control ml-1 shadow-none textarea"
                                                      name="comment"></textarea>
                                        </div>
                                        <div class="text-end cmnt-btns">
                                            <button class="btn btn-primary btn-sm shadow-none" type="submit">Post
                                                Comment
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm ml-1 shadow-none"
                                                    type="reset">Cancel
                                            </button>
                                            <button type="button" class="popup-gif" data-parent_id="" data-tag_id=""
                                                    data-bs-toggle="modal" data-bs-target="#gifModal">Gif
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @include('public.comments')
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 mt-5">
                    <div class="reel-det-rght">
                        <p>${{ $reel->donation_received ?? 0 }} raised of ${{ $reel->donation_amount ?? 0 }}</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ round(100/$reel->donation_amount*$reel->donation_received) }}%"
                                 aria-valuenow="{{ round(100/$reel->donation_amount*$reel->donation_received) }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row border-top mt-4 mb-4 pt-4">
                            <div class="col-md-6 text-center border-end">
                                <h3 class="mb-0">{{ $reel->comments_count ?? 0 }}</h3>
                                <p class="mb-0">Comments</p>
                            </div>
                            <div class="col-md-6 text-center">
                                <h3 class="mb-0">{{ $reel->shares_count ?? 0 }}</h3>
                                <p class="mb-0">Shares</p>
                            </div>
                        </div>
                        <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal2"><img
                                src="{{ asset('assets/img/share.svg') }}"> Share</a>
                        @if($reel->donation_received < $reel->donation_amount)
                            <a href="javascript:" data-id="{{$reel->id}}" data-amount="{{$remainamount}}"
                               class="bg-grn donation-now">DoleUpp Now</a>
                            <!-- <a href="javascript:" class="bg-grn donation-now"  data-bs-toggle="modal" data-bs-target="#exampleModal3" >DoleUpp Now</a> -->
                        @endif
                        <p class="mt-4"><img src="{{ asset('assets/img/donted.svg') }}"
                                             class="me-2"> {{ $reel->real_donors_count ?? 0 }} people just donated</p>
                        @foreach ($reel->latest_donors as $donor)
                            <div class="donrs">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img
                                            src="{{ $donor->donation_by_user->image ?? asset('assets/img/img-1.png') }}"
                                            width="50">
                                    </div>
                                    <div class="col-md-9">
                                        <h4>{{ $donor->donation_by_user->name ?? '' }}</h4>
                                        <p>Amount : ${{ $donor->amount ?? 0 }}</p>
                                        <span>Category: {{ $reel->category->name ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($reel->real_donors_count > 3)
                            <a href="" class="see-mor" data-bs-toggle="modal" data-bs-target="#exampleModal">See More
                                <img
                                    src="{{ asset('assets/img/dbl-arow.svg') }}"></a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="featrs">
                <div class="row">
                    <div class="col-md-4 px-5">
                        <img src="{{ asset('assets/img/trophy.svg') }}">
                        <h3>#1 Social Donation Platform</h3>
                        <p>More people start fundraisers on GoFundMe than on any other platform.</p>
                    </div>
                    <div class="col-md-4 px-5">
                        <img src="{{ asset('assets/img/guarantee.svg') }}">
                        <h3>DoleUpp Guarantee</h3>
                        <p>In the rare case something isn’t right, we will work with you to determine if misuse
                            occurred.
                        </p>
                    </div>
                    <div class="col-md-4 px-5">
                        <img src="{{ asset('assets/img/24hours.svg') }}">
                        <h3>Expert advice, 24/7</h3>
                        <p>Contact us with your questions and we’ll answer, day or night.</p>
                    </div>
                </div>
            </div>
        </div>

    </main><!-- End #main -->

    <div class="modal fade all-dnrs" id="gifModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header px-5">
                    <h5 class="modal-title" id="exampleModalLabel">{{--{{ $reel->caption ?? '' }}--}}</h5>
                    {{--@if($reel->donation_received < $reel->donation_amount)
                     <a href="{{ route('holding-area') }}" data-id="{{$reel->id}}" data-amount="{{$remainamount}}" class="btn-get-started d-block mt-3 donation-now">DoleUpp Now</a>
                    @endif--}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="row">
                        <div class="col-md-12 input-group">
                            <input type="search" id="gif-search" class="form-control" value=""
                                   placeholder="Search Gif's">
                            <div class="input-group-append">
                                <input type="button" id="gif-search-btn" class="form-control" value="Search">
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('comments.gif') }}" method="POST">
                    @csrf
                    <input type="hidden" name="comment_type" id="comment_type" value="image">
                    <input type="hidden" name="donation_request_id" id="donation_request_id" value="{{ $reel->id }}">
                    <input type="hidden" name="parent_id" id="parent_id" value="">
                    <input type="hidden" name="tag_id" id="tag_id" value="">
                    <input type="hidden" name="comment" id="comment" value="">
                    <div class="modal-body" id="gif-container">
                        <div class="row" id="gifs">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade all-dnrs" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header px-5">
                    <h5 class="modal-title" id="exampleModalLabel">DoleUpp ({{ $reel->real_donors_count }})</h5>
                    @if($reel->donation_received < $reel->donation_amount)
                        <a href="{{ route('holding-area') }}" data-id="{{$reel->id}}" data-amount="{{$remainamount}}"
                           class="btn-get-started d-block mt-3 donation-now">DoleUpp Now</a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach($reel->real_donors as $donor)
                        <div class="donrs">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="{{ $donor->donation_by_user->image ?? asset('assets/img/img-1.png') }}"
                                         width="50">
                                </div>
                                <div class="col-md-9">
                                    <h4>{{ $donor->donation_by_user->name ?? '' }}</h4>
                                    <p>Amount : ${{ $donor->amount ?? 0 }}</p>
                                    <span>Category: {{ $donor->donation_request->category->name ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade don-req-sbt" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-0">

                    <h3>Share reel by</h3>
                    <div id="main-icon-div">
                        <div id="social-links2">
                            <ul>
                                <li>
                                    <a href="sms:?&body={{ $reel->caption.' '.route('reels.show', ['slug' => $reel->id]) }}"
                                       class="social-button "><span class="fas fa-sms"></span></a>
                                </li>
                                <li>
                                    <a href="mailto:?subject=Share Reel - {{ $reel->caption ?? '' }}&body={{ $reel->caption.' '.route('reels.show', ['slug' => $reel->id]) }}">
                                        <span class="fas fa-envelope"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {!! $shareReel !!}
                    </div>
                    {{--<span>and</span>
                    <p>will be posted on app within 2 days once approved by admin</p>--}}
                </div>
            </div>
        </div>
    </div>

    <?php $remainamount = $reel->donation_amount - $reel->donation_received; ?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="padding-bottom:24px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">DoleUpp Now</h5>
                    <!--   <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </a> -->
                </div>
                <form method="post" action="{{url('/donation/make-payment')}}">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="number" id="donationamount" name="donations[0][amount]" value="{{$remainamount}}"
                               class="form-control">
                        <input type="hidden" id="request_id" name="donations[0][donation_request_id]"
                               value="{{$reel->id}}" class="form-control">

                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-primary">Donate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('public.footer')
@endsection

@section('script')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).on('load', function() {
            var donation_request_id = '{{$reel->id}}';
            $('#share_reel_id').val(donation_request_id);
            $.ajax({
                type: "GET",
                url: '{{ route('reels.views') }}',
                data: {
                    'donation_request_id': donation_request_id
                },
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    if (data.success === true) {
                        // toastr.success(data.message)
                    } else {
                        // toastr.error(data.message)
                    }
                }
            });
        });
        $(document).on("click", ".popup-gif", function () {
            $("#parent_id").val($(this).data('parent_id'));
            $("#tag_id").val($(this).data('tag_id'));
        });
        $('#gifModal').on('click', '.form-img', function (e) {
            $('.full-ajax-load').show();
            $("#comment").val($(this).attr('src'));
            // $(this).closest("form").submit();
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: '{{ route('comments.gif') }}',
                data: $(this).closest("form").serialize(),
                dataType: "json",
                beforeSend: function () {
                    $(".cmnt-btns button").prop('disabled', true);
                },
                success: function (data) {
                    // console.log(data);
                    if (data.success === true) {
                        $('#gifModal').hide();
                        toastr.success(data.message)
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        $('.full-ajax-load').hide();
                        toastr.error(data.message)
                        $(".cmnt-btns button").prop('disabled', false);
                    }
                }
            });
        });
        $('.commentCreate').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: '{{ route('comments.store') }}',
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $(".cmnt-btns button").prop('disabled', true);
                },
                success: function (data) {
                    // console.log(data);
                    if (data.success === true) {
                        toastr.success(data.message)
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(data.message)
                        $(".cmnt-btns button").prop('disabled', false);
                    }
                }
            });
        });
        $(document).on("click", ".wishlist-create", function () {
            const donation_request_id = "{{ $reel->id }}";
            $.ajax({
                url: "{{ route('wishlist.create') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function () {
                    $('.ajax-load').show();
                }
            }).done(function (data) {
                if (data.success == true) {
                    $('.wishlist-show').html('<span class="wishlist-remove"><img src="{{ asset('assets/img/add-holi.svg') }}">&nbsp; Added from DoleUpp Cart</span>');
                    toastr.options = {"progressBar": true}
                    toastr.success(data.message)
                } else {
                    toastr.options = {"progressBar": true}
                    toastr.error(data.message)
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                // toastr.options = {"progressBar": true}
                // toastr.warning('server not responding...')
            });
        });
        $(document).on("click", ".wishlist-remove", function () {
            const donation_request_id = "{{ $reel->id }}";
            $.ajax({
                url: "{{ route('wishlist.remove') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function () {
                    $('.ajax-load').show();
                }
            }).done(function (data) {
                if (data.success == true) {
                    $('.wishlist-show').html('<span class="wishlist-create"><img src="{{ asset('assets/img/add-holi-2.svg') }}">&nbsp; Add to DoleUpp Cart</span>');
                    toastr.options = {"progressBar": true}
                    toastr.success(data.message)
                } else {
                    toastr.options = {"progressBar": true}
                    toastr.error(data.message)
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                // toastr.options = {"progressBar": true}
                // toastr.warning('server not responding...')
            });
        });
        /*$('.donation-now').click (function (e) {
            $(this).prop('disabled', true);
            e.preventDefault();
            setTimeout(function () {
                window.location.href = "{{ route('holding-area') }}";
            }, 5000);
        });*/
        loadMoreData(1);
        var page = 1;
        $('#gif-search-btn').on('click', function () {
            page = 1;
            loadData(page);
        });
        $('#gif-container').scroll(function () {
            if ($('#gif-container').scrollTop() + $('#gif-container').height() >= $('#gifs').height()) {
                page++;
                loadMoreData(page);
            }
        });
        // $(window).scroll(function() {
        //     if($(window).scrollTop() + $(window).height() >= $(window).height()) {
        //         page++;
        //         loadMoreData(page);
        //     }
        // });
        function loadMoreData(page) {
            var search = $('#gif-search').val();
            $.ajax({
                url: "{{ route('gifs', ['search' => 'search']) }}?page=" + page + "&search=" + search,
                type: "get",
                beforeSend: function () {
                    $('.ajax-load').show();
                }
            }).done(function (data) {
                    $("#gifs").append(data);
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                // toastr.options = {"progressBar": true}
                // toastr.warning('server not responding...')
            });
        }

        function loadData(page) {
            var search = $('#gif-search').val();
            $.ajax({
                url: "{{ route('gifs', ['search' => 'search']) }}?page=" + page + "&search=" + search,
                type: "get",
                beforeSend: function () {
                    $('.ajax-load').show();
                }
            }).done(function (data) {
                    $("#gifs").html(data);
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                // toastr.options = {"progressBar": true}
                // toastr.warning('server not responding...')
            });
        }
    </script>
@endsection
