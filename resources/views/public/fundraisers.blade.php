@extends('layouts.public')

@section('title', 'Fundraisers')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="fundraisers">

        <!-- ======= Hero Section ======= -->
        <section id="hero" class="d-flex  align-items-center">
            <div class="container text-center" data-aos="zoom-out" data-aos-delay="100">
                <h1>Browse DoleUpp Reels</h1>
                <h2>People around the world are raising money for what they are passionate about.</h2>
                {{--<div class="">
                    <a href="{{ route('donation-request') }}" class="btn-get-started scrollto">Browse</a>
                </div>--}}
            </div>
        </section><!-- End Hero -->


        <!-- ======= Services Section ======= -->
        {{--<section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">

                <div class="row">
                    @foreach ($top_reels as $key => $reel)
                    <?php $remainamount= $reel->donation_amount- $reel->donation_received; ?>

                    <div class="col-lg-4 col-md-6 d-flex @if($key>2) mt-5 @endif align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon">
                                --}}{{-- <video class="reel-img" width="100%" poster="{{ $reel->thumbnail }}" controls>
                                    <source src="{{ $reel->video }}" type="video/mp4">
                                    <source src="{{ $reel->video }}" type="video/ogg">
                                    <source src="{{ $reel->video }}" type="video/webm">
                                    <object data="{{ $reel->video }}" width="100%">
                                        <embed src="{{ $reel->video }}" width="100%">
                                    </object>
                                </video> --}}{{--
                                <img src="{{ $reel->thumbnail }}" class="reel-img" alt="">
                            </div>
                            <a href="{{ $reel->video }}" class="ply-btn" data-id="{{ $reel->id }}"><img src="{{ asset('assets/img/ply-btn.svg') }}" class="" alt=""></a>
                            --}}{{-- @if ($reel->is_prime == 'Yes') --}}{{--
                            <a href="#" class="prmem-tag"><img src="{{ asset('assets/img/prmem-tag.svg') }}" class="" alt=""></a>
                            --}}{{-- @endif --}}{{--
                            <a href="javascript:" class="prmem-tag-footer"><img class="{{ ($reel->wishlist_count >= 1) ? 'wishlistRemove' : 'wishlistCreate' }}" data-id="{{ $reel->id }}" src="{{ ($reel->wishlist_count >= 1) ? asset('assets/img/add-holi.svg') : asset('assets/img/add-holi-2.svg') }}" class="" alt=""></a>
                            <a href="{{ route('reels.show', ['slug' => $reel->id]) }}" style="coloro: #FFFFFF !important;">
                                <div class="reel-views"><img src="{{ asset('assets/img/eye.svg') }}">&nbsp; {{ $reel->views_count ?? 0 }}
                                </div>
                                <div class="rating-icon2">
                                    <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                                    {{ number_format($reel->rating_count, 1) }}
                                </div>
                            </a>
                            <a class="donation-now wishlist-create" data-id="{{$reel->id}}" data-amount="{{$remainamount}}" style="color: #FFFFFF !important;">
                                <div class="donate-to-reel">
                                    --}}{{-- <img src="{{ asset('assets/img/donate-now.svg') }}" width="50"> --}}{{--
                                    DoleUpp Now
                                </div>
                            </a>
                            <h4>
                                <span>Posted By : <a href="{{ route('donors', ['username' => $reel->user->id]) }}">{{ $reel->user->name ?? '' }}</a></span>
                                Title : {!! \Str::limit($reel->caption ?? '', 90, $end='... <a href="'.route("reels.show", ["slug" => $reel->id]).'">view more</a>') !!}
                            </h4>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ round(100/$reel->donation_amount*$reel->donation_received) }}%" aria-valuenow="{{ round(100/$reel->donation_amount*$reel->donation_received) }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p><b>${{ $reel->donation_received ?? 0 }} raised</b> of ${{ $reel->donation_amount ?? 0 }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </section><!-- End Services Section -->--}}

        <section class="requst-cat">
            <div class="container">
                <h2>DoleUpp Request Category</h2>
                <div class="text-center">
                    @foreach ($donation_categories as $category)
                    <a href="{{ route('fundraisers.show', ['slug' => $category->slug]) }}">
                        <div class="bx-shd">
                            <img src="{{ $category->icon }}" alt="">
                            <h3>{{ $category->name ?? '' }}</h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ======= Services Section ======= -->
        @foreach ($donation_by_category as $dbc)
        @if(count($dbc->fundraisers) > 0)
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">
                <h1 class="text-center mb-4">{{ $dbc->name ?? '' }} DoleUpp Request</h1>
                <div class="row">
                    @foreach ($dbc->fundraisers as $key => $reel)
                    <?php $remainamount= $reel->donation_amount- $reel->donation_received; ?>
                    <!-- {{$reel}} -->
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon">
                                <img src="{{ $reel->thumbnail }}" class="reel-img" alt="">
                            </div>
                            <a href="{{ $reel->video }}" class="ply-btn" data-id="{{ $reel->id }}"><img src="{{ asset('assets/img/ply-btn.svg') }}" class="" alt=""></a>
                            {{-- @if ($reel->is_prime == 'Yes') --}}
                            <a href="#" class="prmem-tag"><img src="{{ asset('assets/img/prmem-tag.svg') }}" class="" alt=""></a>
                            {{-- @endif --}}
                            <a href="javascript:" class="prmem-tag-footer"><img class="{{ ($reel->wishlist_count >= 1) ? 'wishlistRemove' : 'wishlistCreate' }}" data-id="{{ $reel->id }}" src="{{ ($reel->wishlist_count >= 1) ? asset('assets/img/add-holi.svg') : asset('assets/img/add-holi-2.svg') }}" class="" alt=""></a>
                            <a href="{{ route('reels.show', ['slug' => $reel->id]) }}" style="color: #FFFFFF !important;">
                                <div class="reel-views">
                                    <img src="{{ asset('assets/img/eye.svg') }}">&nbsp; {{ $reel->views_count ?? 0 }}
                                </div>
                                <div class="reel-comments">
                                    <img width="15" src="{{ asset('assets/img/comment.svg') }}">&nbsp; {{ $reel->comments_count ?? 0 }}
                                </div>
                            </a>
                            <div class="reel-shares share_reel" data-id="{{$reel->id}}" data-caption="{{$reel->caption}}">
                                <img width="14" src="{{ asset('assets/img/share-white.svg') }}">&nbsp; {{ $reel->shares_count ?? 0 }}
                            </div>
                            <a href="{{ route('reels.show', ['slug' => $reel->id]) }}" style="color: #FFFFFF !important;">
                                <div class="rating-icon2">
                                    <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                                    {{ number_format($reel->rating_count, 1) }}
                                </div>
                            </a>
                            <a class="donation-now wishlist-create"  data-id="{{$reel->id}}" data-amount="{{$remainamount}}" style="color: #FFFFFF !important;">
                                <div class="donate-to-reel">
                                    {{-- <img src="{{ asset('assets/img/donate-now.svg') }}" width="50"> --}}
                                    DoleUpp Now
                                </div>
                            </a>
                            <div class="reel-text">
                                <span>Posted By : <a href="{{ route('donors', ['username' => $reel->user->id]) }}">{{ $reel->user->name ?? '' }}</a></span>
                                {{--@if($reel->user->live_status == 'online') <i class="fas fa-circle text-success" style="font-size: 0.5em"></i> @else <i class="fas fa-circle text-danger" style="font-size: 0.5em"></i> @endif--}}
                                <h6>
                                    Title : {!! \Str::limit($reel->caption ?? '', 55, $end='... <a href="'.route("reels.show", ["slug" => $reel->id]).'">view more</a>') !!}
                                    @if(\Str::length($reel->Description) > 0)
                                        <br/><small>
                                            Description : {!! \Str::limit($reel->Description ?? '', 65, $end='... <a href="'.route("reels.show", ["slug" => $reel->id]).'">view more</a>') !!}
                                        </small>
                                    @endif
                                </h6>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ round(100/$reel->donation_amount*$reel->donation_received) }}%" aria-valuenow="{{ round(100/$reel->donation_amount*$reel->donation_received) }}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p><b>${{ $reel->donation_received ?? 0 }} raised</b> of ${{ $reel->donation_amount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach


                    <div class="col-md-12 text-center mt-4">
                        <a href="{{ route('fundraisers.show', ['slug' => $dbc->slug]) }}" class="see-mor">See More <img src="{{ asset('assets/img/dbl-arow.svg') }}"></a>
                    </div>
                </div>

            </div>
        </section>
        @endif
        @endforeach
        <!-- End Services Section -->

        <!-- ======= Portfolio Section ======= -->
        {{-- <section id="portfolio" class="portfolio pb-0 pt-0 text-center">
            <div class="container" data-aos="fade-up">
                <a href="" class="btn-get-started scrollto">Show more categories</a>
            </div>
        </section> --}}
        <!-- End Portfolio Section -->

    </main><!-- End #main -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="padding-bottom:24px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">DoleUpp Now</h5>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <form method="post" action="{{url('/donation/make-payment')}}">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="number" id="donationamount" name="donations[0][amount]" value="" class="form-control">
                    <input type="hidden" id="request_id" name="donations[0][donation_request_id]" value="" class="form-control">
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary">DoleUpp</button>
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
        /*$(document).on("click", ".wishlist-create", function () {
            $('.wishlist-create').prop('disabled', true);
            const donation_request_id = $(this).data('id');
            // console.log(donation_request_id);
            $.ajax({
                url: "{{ route('wishlist.create') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();
                }
            })
            .done(function(data){
                if(data.success == true) {
                    toastr.options = { "progressBar" : true }
                    toastr.success(data.message)
                    setTimeout(function () {
                        window.location.href = "{{ route('holding-area') }}";
                    }, 5000);
                } else {
                    toastr.options = { "progressBar" : true }
                    toastr.error(data.message)
                    $('.wishlist-create').prop('disabled', false);
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                toastr.options = { "progressBar" : true }
                toastr.warning('server not responding...')
                $('.wishlist-create').prop('disabled', false);
            });
        });*/
      /*   $(document).on("click", ".wishlist-create", function () {

            const donation_request_id = $(this).data('id');
            const amount = $(this).data('amount');
            $("#donationamount").val(amount);
            $("#request_id").val(donation_request_id);
            $("#exampleModal").modal("toggle")


        }); */
    </script>
@endsection
