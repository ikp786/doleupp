@extends('layouts.public')

@section('title', 'My Account')

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

    <main id="main" class="privacy-policy my-account">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">
                <div class="icon-box">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-hdr">
                                My Account
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 p-4">
                                {{--@include('public.sidebar')--}}
                                <ul id="tabs" class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a id="tab-G" href="#pane-G" class="nav-link @if(isset($_GET['action'])) @if($_GET['action'] != 'my-holding') active @endif @else active @endif" data-toggle="tab" role="tab">DoleUpp Reels</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-A" href="#pane-A" class="nav-link" data-toggle="tab" role="tab">My DoleUpp</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-B" href="#pane-B" class="nav-link @if(isset($_GET['action']) && $_GET['action'] == 'my-holding') active @endif" data-toggle="tab" role="tab">DoleUpp Cart</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-C" href="#pane-C" class="nav-link" data-toggle="tab" role="tab">Cash Out</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-D" href="#pane-D" class="nav-link" data-toggle="tab" role="tab">Account Settings</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-E" href="#pane-E" class="nav-link" data-toggle="tab" role="tab">Help Center</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            {{ __('Sign Out') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>

                            </div>
                            <div class="col-md-9">
                                <div id="content" class="tab-content" role="tablist">
                                    <div id="pane-G" class="card tab-pane fade @if(isset($_GET['action'])) @if($_GET['action'] != 'my-holding') show active @endif @else show active @endif" role="tabpanel" aria-labelledby="tab-G">
                                        <div class="card-header" role="tab" id="heading-G">
                                            <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" href="#collapse-G"
                                                   aria-expanded="false" aria-controls="collapse-G">
                                                    DoleUpp Reels
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-G" class="collapse" data-parent="#content" role="tabpanel"
                                             aria-labelledby="heading-G">
                                            <div class="card-body">
                                                <h4>DoleUpp Reels
                                                    @if(auth()->user()->subscription_ends_at == NULL)
                                                    <a href="{{ route('subscription') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Subscribe Now</a>
                                                    @elseif(auth()->user()->subscription_ends_at < \Carbon\Carbon::now())
                                                    <a href="{{ route('subscription-renew') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Renew Subscription</a>
                                                    @else
                                                        <a href="{{ route('donation-request') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Add New</a>
                                                    @endif
                                                </h4>
                                                {{--{{ $reels }}--}}
                                                @if(count($reels) > 0)
                                                    @foreach($reels as $donation)
                                                        <div class="donrs">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <a href="{{ $donation->video ?? '' }}" class="ply-video"><img src="{{ $donation->thumbnail }}" width="100" height="100" class="rounded"></a>
                                                                </div>
                                                                <div class="col-md-6 text-left">
                                                                    <a href="{{ route('reels.show', ['slug' => $donation->id]) }}"><h4 class="mb-0">{{ $donation->caption ?? '' }}</h4></a>
                                                                    <span><a href="#">{{ $donation->category->name ?? '' }} @if($donation->is_prime == 'Yes') (Prime) @endif</a> &nbsp; | &nbsp; {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                            style="width: {{ round(100/$donation->donation_amount*$donation->donation_received) }}%" aria-valuenow="{{ round(100/$donation->donation_amount*$donation->donation_received) }}" aria-valuemin="0"
                                                                            aria-valuemax="100"></div>
                                                                    </div>
                                                                    <p><b>${{ $donation->donation_received ?? 0 }} raised</b> of ${{ $donation->donation_amount ?? 0 }}</p>
                                                                </div>
                                                                <div class="col-md-3 text-left">
                                                                    <p class="ttl-prc mt-3">Amount : ${{ number_format($donation->donation_amount ?? 0, 2) }}
                                                                    <p>Reel Status :
                                                                        @if($donation->status == 'Pending')
                                                                            <span class="text-warning">{{ $donation->status ?? '' }}</span>
                                                                        @elseif($donation->status == 'Approved')
                                                                            <span class="text-success">{{ $donation->status ?? '' }}</span>
                                                                        @else
                                                                            <span class="text-danger">{{ $donation->status ?? '' }}</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="donrs">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <h4 class="mb-0">No Data</h4>
                                                            </div>
                                                            <div class="col-md-3 text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-A" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-A">
                                        <div class="card-header" role="tab" id="heading-A">
                                            <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" href="#collapse-A"
                                                    aria-expanded="false" aria-controls="collapse-A">
                                                    My DoleUpp
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-A" class="collapse" data-parent="#content" role="tabpanel"
                                            aria-labelledby="heading-A">
                                            <div class="card-body">
                                                <h4>My DoleUpp</h4>
                                                @if(count($donations) > 0)
                                                @foreach($donations as $donation)
                                                <div class="donrs">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a href="{{ $donation->donation_request->video ?? '' }}" class="ply-video"><img src="{{ $donation->donation_request->thumbnail }}" width="100" height="100" class="rounded"></a>
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <a href="{{ route('reels.show', ['slug' => $donation->donation_request->id]) }}"><h4 class="mb-0">{{ $donation->donation_request->caption ?? '' }}</h4></a>
                                                            <span><a href="#">{{ $donation->donation_to_user->name ?? '' }}</a> &nbsp; | &nbsp; {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
                                                        </div>
                                                        <div class="col-md-3 text-left">
                                                            <p class="ttl-prc mt-3">Amount : ${{ number_format($donation->amount ?? 0, 2) }}</p>
                                                            <p>Payment Status :
                                                                @if($donation->status == 'earned' || $donation->status == 'redeemed')
                                                                <span class="text-success">Success</span>
                                                                @else
                                                                <span class="text-danger">{{ ucwords($donation->status) ?? '' }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @else
                                                    <div class="donrs">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <h4 class="mb-0">No Data</h4>
                                                            </div>
                                                            <div class="col-md-3 text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-B" class="card tab-pane fade @if(isset($_GET['action']) && $_GET['action'] == 'my-holding') show active @endif" role="tabpanel"
                                        aria-labelledby="tab-B">
                                        <div class="card-header" role="tab" id="heading-B">
                                            <h5 class="mb-0">
                                                <!-- Note: `data-parent` removed from here -->
                                                <a data-toggle="collapse" href="#collapse-B" aria-expanded="true"
                                                    aria-controls="collapse-B">
                                                    DoleUpp Cart
                                                </a>
                                            </h5>
                                        </div>

                                        <!-- Note: New place of `data-parent` -->
                                        <div id="collapse-B" class="collapse show" data-parent="#content" role="tabpanel"
                                            aria-labelledby="heading-B">
                                            <div class="card-body">
                                                <form action="{{ route('donation.make-payment') }}" method="POST">
                                                @csrf
                                                <h4>DoleUpp Cart</h4>
                                                @if(count($wishlists) > 0)
                                                @foreach($wishlists as $key=>$wishlist)
                                                <div class="donrs">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a href="{{ $wishlist->video ?? '' }}" class="ply-video"><img src="{{ $wishlist->thumbnail }}" width="100" height="100" class="rounded"></a>
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="mb-0">{{ $wishlist->caption ?? '' }}</h4>
                                                            <div class="reel-vie">
                                                                <img src="{{ asset('assets/img/eyeb.svg') }}">&nbsp; {{ $wishlist->views_count ?? 0 }}
                                                            </div>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ round(100/$wishlist->donation_amount*$wishlist->donation_received) }}%" aria-valuenow="{{ round(100/$wishlist->donation_amount*$wishlist->donation_received) }}" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                            <p><b>${{ $wishlist->donation_received ?? 0 }} raised</b> of ${{ $wishlist->donation_amount ?? 0 }}</p>

                                                        </div>
                                                        <div class="col-md-3 text-left">
                                                            @php
                                                            $donation_amt = 100;
                                                            if(($wishlist->donation_amount - $wishlist->donation_received) < 100) {
                                                                $donation_amt = $wishlist->donation_amount - $wishlist->donation_received;
                                                            }
                                                            @endphp
                                                            <input type="hidden" name="donations[{{ $key }}][donation_request_id]" value="{{ $wishlist->id ?? '' }}"/>
                                                            <input type="number" name="donations[{{ $key }}][amount]" class="form-select mb-0 mt-4 donation_amount" list="amounts" value="{{ $donation_amt ?? 0 }}" min="0" max="{{ ($wishlist->donation_amount - $wishlist->donation_received) ?? 0 }}"/>
                                                            <datalist id="amounts">
                                                                <option>50</option>
                                                                <option>100</option>
                                                                <option>500</option>
                                                                <option>1000</option>
                                                                <option>1500</option>
                                                            </datalist>
                                                            @error('donations.'.$key.'.amount')
                                                            <br><span class="text-danger" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                            {{--<select class="form-select mb-0 mt-4"
                                                                aria-label="Default select example">
                                                                <option selected="">Select Amount</option>
                                                                <option value="50">$50</option>
                                                                <option value="100">$100</option>
                                                                <option value="500">$500</option>
                                                                <option value="1000">$1000</option>
                                                                <option value="1500">$1500</option>
                                                            </select>--}}
                                                        </div>
                                                        {{-- <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="donations[0][cashout]" value="1" id="flexCheckDefault">
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                @endforeach

                                                <div class="row mt-4">
                                                    <div class="col-md-6">
                                                        <p class="ttl-prc">DoleUpp Amount : $<span id="donation_amount"></span></p>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        @if($amount_for_donate > 0)
                                                        <div class="mt-3">
                                                            <input class="" type="checkbox" name="use_donation_amount" value="Yes" id="use_donation_amount">
                                                            <label for="use_donation_amount"><p>Note:
                                                                <span style="font-size: 11px; color: #7B7B7B;">Your cashout fee is ${{ number_format($amount_for_donate, 2) ?? 0 }}, do you want to use for DoleUpp.</span></p></label>
                                                        </div>
                                                        @endif
                                                        {{--<a href="" class="btn-get-started">DoleUpp Now</a>--}}
                                                        <input type="submit" value="DoleUpp Now" class="btn-get-started"/>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="donrs">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="mb-0">No Data</h4>
                                                        </div>
                                                        <div class="col-md-3 text-left">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-C" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-C">
                                        <div class="card-header" role="tab" id="heading-C">
                                            <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" href="#collapse-C"
                                                    aria-expanded="false" aria-controls="collapse-C">
                                                    Cash Out
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-C" class="collapse" role="tabpanel" data-parent="#content"
                                            aria-labelledby="heading-C">
                                            <div class="card-body">
                                                <form action="{{ route('donation.cashout') }}" method="POST">
                                                    @csrf
                                                <h4 class="d-flex justify-content-between">Cash Out <a href="">All</a>
                                                </h4>
                                                @if(count($cashouts) > 0)
                                                @php $cashout_total=0; @endphp
                                                @foreach($cashouts as $key => $cashout)
                                                {{-- {{ $cashout }} --}}
                                                <label class="donrs d-block">
                                                    <div class="row">
                                                        <input type="hidden" name="donations[{{$key}}][id]" value="{{ $cashout->id }}"/>
                                                        <div class="col-md-2">
                                                            <a href="{{ $cashout->video ?? '' }}" class="ply-video"><img src="{{ $cashout->thumbnail ?? '' }}" class="rounded" width="100" height="100"></a>
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="mb-0 mt-3">{{ $cashout->caption ?? '' }}</h4>
                                                            {{-- <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ round((100/$cashout->donation_amount)*$cashout->donation_earned) }}%" aria-valuenow="{{ round((100/$cashout->donation_amount)*$cashout->donation_earned) }}" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div> --}}
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ round(100/$cashout->donation_amount*$cashout->donation_received) }}%" aria-valuenow="{{ round(100/$cashout->donation_amount*$cashout->donation_received) }}" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                            <p><b>${{ number_format($cashout->donation_received ?? 0, 2) }} raised</b> of ${{ number_format($cashout->donation_amount ?? 0, 2) }}</p>
                                                        </div>
                                                        <div class="col-md-4 text-left">
                                                            <p>Already Redeemed : <b>${{ number_format($cashout->donation_redeemed ?? 0, 2) }}</b></p>
                                                            <p>Not Redeemed : <b style="margin-left: 18px;">${{ number_format($cashout->donation_earned ?? 0, 2) }}</b></p>
                                                            @php
                                                                $cashout_total += $cashout->donation_earned;
                                                            @endphp
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="donations[{{$key}}][cashout]" value="1"
                                                                id="flexCheckDefault" />
                                                        </div>
                                                    </div>
                                                </label>
                                                @endforeach

                                                <div class="row mt-4">
                                                    <div class="col-md-7"></div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between">
                                                            <p>Amount Withdrawn</p>
                                                            <p><b>${{ number_format($cashout_total ?? 0, 2) }}</b></p>
                                                        </div>
                                                        @php
                                                            $cashout_fee = ($cashout_total/100)*$settings->cash_out_fee;
                                                        @endphp
                                                        <div class="d-flex justify-content-between border-bottom">
                                                            <p>Fees ({{ $settings->cash_out_fee ?? 0 }}%)</p>
                                                            <p><b>- ${{ number_format($cashout_fee ?? 0, 2) }}</b></p>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <p>Balance</p>
                                                            <p><b>$ {{ number_format($cashout_total-$cashout_fee, 2) }}</b></p>
                                                        </div>
                                                        <div class="mt-3">
                                                            <p>Note:
                                                                <span style="font-size: 11px; color: #7B7B7B;">{{ $settings->cash_out_note ?? '' }}</span></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-7"></div>
                                                    <div class="col-md-5 text-end">
                                                        <button type="submit" class="btn-get-started d-block text-center">Continue</button>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="donrs">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="mb-0">No Data</h4>
                                                        </div>
                                                        <div class="col-md-3 text-left">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-D" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-D">
                                        <div class="card-header" role="tab" id="heading-D">
                                            <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" href="#collapse-D"
                                                    aria-expanded="false" aria-controls="collapse-D">
                                                    Account Settings
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-D" class="collapse" role="tabpanel" data-parent="#content"
                                            aria-labelledby="heading-D">
                                            <div class="card-body">
                                                <h4>Account Settings</h4>
                                                <div class="row mob-cls border rounded">
                                                    <div class="col-md-6">
                                                        <h4 class="mt-3`">Notification</h4>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <label class="switch mt-3">
                                                            <input type="checkbox" class="notification" @if(auth()->user()->notification == 'Yes') checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-E" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-E">
                                        <div class="card-header" role="tab" id="heading-E">
                                            <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" href="#collapse-E"
                                                    aria-expanded="false" aria-controls="collapse-E">
                                                    Help Center
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-E" class="collapse" role="tabpanel" data-parent="#content"
                                            aria-labelledby="heading-E">
                                            <div class="card-body">
                                                <h4>Help Center</h4>

                                                {{--<div class="border rounded p-5 text-center mb-3">
                                                    <h2>Can't Find What You're Looking For?</h2>
                                                    <a href="" class="btn-get-started text-center">Ask Us A Question</a>
                                                </div>

                                                <h4 class="mt-4">FAQ</h4>--}}
                                                <div class="accordion">
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

                                    <div id="pane-F" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-F">
                                        <div class="card-header" role="tab" id="heading-F">
                                            <h5 class="mb-0">
                                                <a class="collapsed nav-link" data-toggle="collapse" href="#collapse-F"
                                                    aria-expanded="false" aria-controls="collapse-F">
                                                    Logout
                                                </a>
                                            </h5>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

    <script type="text/javascript">
        function donation_amount() {
            var sum = 0;
            $('.donation_amount').each(function(){
                sum += parseFloat(this.value);
                // console.log(sum);
                if(sum>0)
                    $('#donation_amount').html(sum);
                else
                    $('#donation_amount').html(0);
            });
        }
        donation_amount()
        $('.donation_amount').change(function (){
            donation_amount()
        });
        $('.donation_amount').keyup(function (){
            donation_amount()
        });
        $('.accordion').each(function() {
            var $accordian = $(this);
            $accordian.find('.accordion-head').on('click', function() {
                $(this).removeClass('open').addClass('close');
                $accordian.find('.accordion-body').slideUp();
                if (!$(this).next().is(':visible')) {
                    $(this).removeClass('close').addClass('open');
                    $(this).next().slideDown();
                }
            });
        });

        $('.notification').on('click', function() {
            var notification = $(this).prop('checked') == true ? 'Yes' : 'No';
            $.ajax({
                type: "GET",
                url: '{{ route('notification.status') }}',
                data: {
                    'notification': notification
                },
                dataType: "json",
                success: function(data) {
                    //console.log(data);
                    if (data.success === true) {
                        toastr.success(data.message)
                    } else {
                        toastr.error(data.message)
                    }
                }
            });
        });
    </script>
@endsection
