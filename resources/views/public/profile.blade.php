@extends('layouts.public')

@section('title', 'Profile - '.auth()->user()->name ?? '')

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

        .badge-table {
            border-collapse: separate;
            border-spacing: 0 1.5em;
        }
    </style>
    <style>
        .circle-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
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
                            <div class="sec-hdr">
                                Profile
                                <a href="{{ route('profile-edit') }}" class="edit-btn"><img src="{{ asset('assets/img/edit.svg') }}" alt=""> Edit</a>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-10 p-5">
                            <div class="text-center"><img src="{{ asset(Auth::user()->image ?? 'assets/img/profile.png') }}" alt="" width="120"></div>
                            <h3 class="text-center">{{ Auth::user()->name ?? '' }}</h3>
                            <p class="mt-2 text-center">{{ Auth::user()->about ?? '' }}</p>
                            <div class="cat-p">
                                <p class="grn-col"><img src="{{ asset('assets/img/location.svg') }}" alt="">&nbsp; {{ auth()->user()->state ?? '' }} {{ auth()->user()->country ?? '' }}</p>
                                <a href="javascript:" data-bs-toggle="modal" data-bs-target="#exampleModal"><p class="grn-col"><img src="{{ asset('assets/img/share.svg') }}" alt="">&nbsp; Share Profile</p></a>
                                <a href="javascript:" data-bs-toggle="modal" data-bs-target="#exampleModal3"><p class="grn-col"><img src="{{ asset('assets/img/sharee.svg') }}" alt="">&nbsp; Invite Friends</p></a>
                                <p class="grn-col">DoleUpp Sent (${{ $data['donation_send'] ?? 0 }})</p>
                                <p class="grn-col">DoleUpp Received (${{ $data['donation_received'] ?? 0 }})</p>
                                <p><a href="javascript:" data-bs-toggle="modal" data-bs-target="#exampleModal2">{!! $data['badge'] !!}</a></p>
                            </div>

                            <div class="sign-up-tabs">
                                <ul class="nav nav-tabs text-center" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">DoleUpp Sent ({{ count($donation_to) ?? 0 }})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                            aria-selected="false">DoleUpp Received ({{ count($donation_from) ?? 0 }})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="video-tab" data-bs-toggle="tab"
                                            data-bs-target="#video" type="button" role="tab" aria-controls="video"
                                            aria-selected="false">Videos ({{ count($reels) ?? 0 }})</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        @if(count($donation_to) > 0)
                                        @foreach ($donation_to as $to)
                                        <div class="donrs">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img src="{{ $to->donation_request->thumbnail }}" alt="" class="circle-image">
                                                </div>
                                                <div class="col-md-7 text-left">
                                                    <h4><a href="{{ route('reels.show', ['slug' => $to->donation_request->id]) }}">{{ $to->donation_request->caption ?? '' }}</a></h4>
                                                    <span>Category: {{ $to->donation_request->category->name ?? '' }}  | User: <a href="{{ route('donors', ['username' => $to->donation_to_user->id]) }}">{{ $to->donation_to_user->name ?? '' }}</a> | {{ date('d/m/Y h:i A', strtotime($to->created_at)) ?? '' }}</span>
                                                </div>
                                                <div class="col-md-3 text-left">
                                                    <p>Amount : ${{ $to->amount ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        @endforeach
                                        @else
                                        <div class="donrs">
                                            <div class="row">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-7 text-left">
                                                    <h4>{{ __('DoleUpp To - No Data') }}</h4>
                                                </div>
                                                <div class="col-md-3 text-left">
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                    </div>

                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        @if(count($donation_from) > 0)
                                        @foreach ($donation_from as $by)
                                        {{-- {{ $by->donation_request->category }}{{ die }} --}}
                                        <div class="donrs">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img src="{{ asset($by->donation_by_user->image ?? 'assets/img/profile.png') }}" class="circle-image">
                                                </div>
                                                <div class="col-md-7 text-left">
                                                    <h4><a href="{{ route('donors', ['username' => $by->donation_by_user->id]) }}">{{ $by->donation_by_user->name ?? '' }}</a></h4>
                                                    @if(@$by->donation_request->category->name != null)
                                                        <span>Category: {{ $by->donation_request->category->name ?? '' }} | Video Title: <a href="{{ route('reels.show', ['slug' => $by->donation_request->id]) }}">{{ $by->donation_request->caption ?? '' }}</a> | {{ date('d/m/Y h:i A', strtotime($by->created_at)) ?? '' }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 text-left">
                                                    <p>Amount : ${{ $by->amount ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        @endforeach
                                        @else
                                        <div class="donrs">
                                            <div class="row">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-7 text-left">
                                                    <h4>{{ __('DoleUpp From - No Data') }}</h4>
                                                </div>
                                                <div class="col-md-3 text-left">
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                                        @if(count($reels) > 0)
                                            @foreach($reels as $donation)
                                                <div class="donrs">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a href="{{ $donation->video ?? '' }}" class="ply-video"><img src="{{ $donation->thumbnail }}" width="100" height="100" class="rounded"></a>
                                                            <div class="rating-icon1">
                                                                <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                                                                {{ number_format($donation->rating_count, 1) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <a href="{{ route('reels.show', ['slug' => $donation->id]) }}"><h5 class="mb-0">{{ $donation->caption ?? '' }}</h5><h4>{{ $donation->Description ?? '' }}</h4></a>
                                                            <span><a href="#">{{ $donation->category->name ?? '' }} @if($donation->is_prime == 'Yes') (Prime) @endif</a> &nbsp; | {{ date('d/m/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ round(100/$donation->donation_amount*$donation->donation_received) }}%" aria-valuenow="{{ round(100/$donation->donation_amount*$donation->donation_received) }}" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                            <p><b>${{ $donation->donation_received ?? 0 }} raised</b> of ${{ $donation->donation_amount ?? 0 }}</p>
                                                        </div>
                                                        <div class="col-md-3 text-left">
                                                            <p class="ttl-prc mt-3">Amount : ${{ number_format($donation->donation_amount ?? 0, 2) }}
                                                            <p>
                                                                {{-- Reel Status :
                                                                @if($donation->status == 'Pending')
                                                                    <span class="text-warning">{{ $donation->status ?? '' }}</span>
                                                                @elseif($donation->status == 'Approved')
                                                                    <span class="text-success">{{ $donation->status ?? '' }}</span>
                                                                @else
                                                                    <span class="text-danger">{{ $donation->status ?? '' }}</span>
                                                                @endif --}}
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
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

    <div class="modal fade don-req-sbt" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-0">

                    <h3>Share profile by</h3>
                    <div id="main-icon-div">
                        <div id="social-links2">
                            <ul>
                                <li>
                                    <a href="sms:?&body={{ auth()->user()->name.' '.route('donors', ['username' => auth()->user()->id]) }}" class="social-button "><span class="fas fa-sms"></span></a>
                                </li>
                                <li>
                                    <a href="mailto:?subject=Share Profile - {{ auth()->user()->name ?? '' }}&body={{ auth()->user()->name.' '.route('donors', ['username' => auth()->user()->id]) }}">
                                        <span class="fas fa-envelope"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {!! $shareProfile !!}
                    </div>
                    {{--<span>and</span>
                    <p>will be posted on app within 2 days once approved by admin</p>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade don-req-sbt" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel3"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-0">

                    <h3>Invite friends by</h3>
                    <div id="main-icon-div">
                        <div id="social-links2">
                            <ul>
                                <li>
                                    <a href="sms:?&body={{ $invitationText.' '.route('register', ['referral_code' => auth()->user()->referral_code]) }}" class="social-button "><span class="fas fa-sms"></span></a>
                                </li>
                                <li>
                                    <a href="mailto:?subject=Invitation by - {{ auth()->user()->name ?? '' }}&body={{ $invitationText.' '.route('register', ['referral_code' => auth()->user()->referral_code]) }}">
                                        <span class="fas fa-envelope"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {!! $shareInvitation !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade don-req-sbt" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-0">
                    <div class="text-center">
                        <h3>Badge Details</h3>
                    </div>
                    <hr/>
                    <div id="">
                        <div id="">
                            <table class="badge-table" width="100%">
                                <tr>
                                    <td><img width="50" src="{{ asset('assets/img/badge/bronze.png') }}"/></td>
                                    <td><h4>Bronze</h4>$500 - $4,999</td>
                                </tr>
                                <tr>
                                    <td><img width="50" src="{{ asset('assets/img/badge/silver.png') }}"/></td>
                                    <td><h4>Silver</h4>$5,000 - $49,999</td>
                                </tr>
                                <tr>
                                    <td><img width="50" src="{{ asset('assets/img/badge/gold.png') }}"/></td>
                                    <td><h4>Gold</h4>$50,000 - $99,999</td>
                                </tr>
                                <tr>
                                    <td><img width="50" src="{{ asset('assets/img/badge/platinum.png') }}"/></td>
                                    <td><h4>Platinum</h4>$100,000 - $999,999</td>
                                </tr>
                                <tr>
                                    <td><img width="50" src="{{ asset('assets/img/badge/diamond.png') }}"/></td>
                                    <td><h4>Diamond</h4>$1,000,000+</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{--<span>and</span>
                    <p>will be posted on app within 2 days once approved by admin</p>--}}
                </div>
            </div>
        </div>
    </div>

    @include('public.footer')
@endsection

@section('script')

@endsection
