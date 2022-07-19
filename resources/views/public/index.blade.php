@extends('layouts.public')

@section('title', 'Home')

@section('content')
    @include('public.header')

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container text-center" data-aos="zoom-out" data-aos-delay="100">
            <h1>Donations for the people and<br>
                causes you care about</h1>
            <h2>Get Started Today.</h2>
            <div class="">
                <a href="{{ route('donation-request') }}" class="btn-get-started scrollto">Start a DoleUpp</a>
                <a href="how-it-works" class="glightbox btn-watch-video"><i class="bi bi-play-circle"></i><span>See how
                        DoleUpp works</span></a>
            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Section ======= -->
        <section id="about" class="about section-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h3>The leader in online social donation platform</h3>
                </div>

                <div class="row">
                    <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
                        <img src="assets/img/about.png" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-7 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up"
                        data-aos-delay="100">
                        <ul>
                            <li>
                                <img src="assets/img/globe.svg" class="" alt="">
                                <div>
                                    <h5>Worldwide leader</h5>
                                    <p>DoleUpp is trusted around the world for its simple, reliable fundraising platform.</p>
                                </div>
                            </li>
                            <li>
                                <img src="assets/img/security.svg" class="" alt="">
                                <div>
                                    <h5>Simple setup</h5>
                                    <p>You can personalize and share your DoleUpp in just a few minutes.</p>
                                </div>
                            </li>
                            <li>
                                <img src="assets/img/simple.svg" class="" alt="">
                                <div>
                                    <h5>Secure</h5>
                                    <p>Our Trust & Safety team works around the clock to protect against fraud.</p>
                                </div>
                            </li>
                            <li>
                                <img src="assets/img/app.svg" class="" alt="">
                                <div>
                                    <h5>Mobile app</h5>
                                    <p>The DoleUpp app makes it simple to launch and manage your fundraiser on the go.</p>
                                </div>
                            </li>
                            <li>
                                <img src="assets/img/diagram.svg" class="" alt="">
                                <div>
                                    <h5>Social reach</h5>
                                    <p>Harness the power of social media to spread your story and get more support.</p>
                                </div>
                            </li>
                            <li>
                                <img src="assets/img/24-hours.svg" class="" alt="">
                                <div>
                                    <h5>24/7 expert advice</h5>
                                    <p>Our best-in-class Customer Happiness agents will answer your questions, day or night.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12 text-center">
                        <a href="{{ route('donation-request') }}" class="btn-get-started scrollto">Start a DoleUpp</a>
                    </div>
                </div>

            </div>
        </section><!-- End About Section -->

        <!-- ======= Portfolio Section ======= -->
        <section id="portfolio" class="portfolio pb-0 text-center">
            <div class="container pb-0" data-aos="fade-up">
                <div class="section-title">
                    <h3>Download Now</h3>
                    <p>Start and manage donation, engage with supporters, <br>and discover important causes — all on the
                        go</p>
                    <div class="app-btns">
                        <a href="javascript:"><img src="assets/img/app-store.svg" class="" alt=""></a>
                        <a href="javascript:"><img src="assets/img/play-store.svg" class="" alt=""></a>
                    </div>
                </div>
                <img src="assets/img/app-img.png" class="" alt="">
            </div>
        </section><!-- End Portfolio Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h3>DoleUpp Request Videos</h3>
                </div>

                <div class="row">
                    @foreach ($top_reels as $key => $reel)
                    @php $remainamount = $reel->donation_amount - $reel->donation_received; @endphp
                    <div class="col-lg-4 col-md-6 d-flex @if($key>2) mt-5 @endif align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon">
                                {{-- <video class="reel-img" width="100%" poster="{{ $reel->thumbnail }}" controls>
                                    <source src="{{ $reel->video }}" type="video/mp4">
                                    <source src="{{ $reel->video }}" type="video/ogg">
                                    <source src="{{ $reel->video }}" type="video/webm">
                                    <object data="{{ $reel->video }}" width="100%">
                                        <embed src="{{ $reel->video }}" width="100%">
                                    </object>
                                </video> --}}
                                <img src="{{ $reel->thumbnail }}" class="reel-img" alt="">
                            </div>
                            <a href="{{ $reel->video }}" class="ply-btn" data-id="{{ $reel->id }}"><img src="{{ asset('assets/img/ply-btn.svg') }}" class="" alt=""></a>
                            {{-- @if ($reel->is_prime == 'Yes') --}}
                            <a href="javascript:" class="prmem-tag"><img src="{{ asset('assets/img/prmem-tag.svg') }}" class="" alt=""></a>
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
                            <a class="donation-now wishlist-create" data-id="{{$reel->id}}" data-amount="{{$remainamount}}" style="color: #FFFFFF !important;">
                                <div class="donate-to-reel">
                                    {{-- <img src="{{ asset('assets/img/donate-now.svg') }}" width="50"> --}}
                                    DoleUpp Now
                                </div>
                            </a>
                            {{--<h4>
                                <a href="{{ route('donors', ['username' => $reel->user->id]) }}"><span>{{ $reel->user->name ?? '' }}</span></a>
                                <a href="{{ route('reels.show', ['slug' => $reel->id]) }}">{{ $reel->caption ?? '' }}</a>
                            </h4>--}}
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

                </div>

            </div>
        </section><!-- End Services Section -->

        <!-- ======= Portfolio Section ======= -->
        <section id="portfolio" class="portfolio pb-0 text-center">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h3>Ready to start donation?</h3>
                    <p class="mt-3">See how no code machine learning can transform your business and change how you make
                        decisions.</p>
                </div>
                <a href="{{ route('donation-request') }}" class="btn-get-started scrollto">Start a DoleUpp</a>
            </div>
        </section><!-- End Portfolio Section -->
    </main><!-- End #main -->

    @include('public.footer')
@endsection

@section('script')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        /*$(document).on("click", ".wishlist-create", function () {
            $('.wishlist-create').prop('disabled', true);
            const donation_request_id = $(this).data('id');//"{{ $reel->id }}";
            // console.log(donation_request_id);
            @if(auth()->user())
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
            @else
            window.location.href = "{{ route('login') }}";
            @endif
        });*/
    </script>
@endsection
