@extends('public.myaccount')

@section('my-title', 'My DoleUpp')

@section('my-content')
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
        {{-- <h4>My DoleUpp</h4> --}}

        <div class="sign-up-tabs">
            <ul class="nav nav-tabs text-center" id="myTab" role="tablist" style="display: flex;">
                <li class="nav-item d-none" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                        data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                        aria-selected="false">DoleUpp to Administrator ({{ count($lazor_donations) ?? 0 }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                        data-bs-target="#home" type="button" role="tab" aria-controls="home"
                        aria-selected="true">My DoleUpp ({{ count($donations) ?? 0 }})</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel"
                    aria-labelledby="home-tab">
                    @if(count($donations) > 0)
                    @foreach($donations as $donation)
                    <div class="donrs">
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{ $donation->donation_request->video ?? '' }}" class="ply-video"><img src="{{ $donation->donation_request->thumbnail }}" width="100" height="100" class="rounded"></a>
                                <div class="rating-icon1">
                                    <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                                    {{ number_format($donation->donation_request->rating_count, 1) }}
                                </div>
                            </div>
                            <div class="col-md-6 text-left">
                                <a href="{{ route('reels.show', ['slug' => $donation->donation_request->id]) }}"><h4 class="mb-0">{{ $donation->donation_request->caption ?? '' }}</h4></a>
                                <span>Payment ID:- {{ $donation->payment_id ?? '' }} <br> <a href="{{ route('donors', ['username' => $donation->donation_to_user->id]) }}">{{ $donation->donation_to_user->name ?? '' }}</a> &nbsp; | &nbsp; {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
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
                                <p>
                                    @if ($donation->paid_by == 'admin')
                                        By DoleUpp App
                                    @else
                                        By You
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

                <div class="tab-pane fade d-none" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    {{-- {{$lazor_donations}} --}}
                    @if(count($lazor_donations) > 0)
                    @foreach($lazor_donations as $donation)
                    <a href="{{ route('lazor-donations', ['id' => $donation->id]) }}">
                        <div class="donrs">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ asset('assets/img/footer-logo.svg') }}" width="100" height="100" class="rounded">
                                </div>
                                <div class="col-md-6 text-left">
                                    <h6 class="mb-0">
                                        <ol>
                                            @foreach(App\Models\Category::whereIn('id', explode(', ', $donation->categories))->get() as $category)
                                                <li>{{ $category->name }}</li>
                                            @endforeach
                                        </ol>
                                    </h6>
                                    <span>Payment ID:- {{ $donation->payment_id ?? '' }} <br> {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
                                    <p>{{ $donation->description ?? '' }}</p>
                                </div>
                                <div class="col-md-3 text-left">
                                    <p class="ttl-prc mt-3">Amount : ${{ number_format($donation->amount ?? 0, 2) }}</p>
                                    <p>Payment Status :
                                        @if($donation->status == 'success')
                                        <span class="text-success">Success</span>
                                        @else
                                        <span class="text-danger">{{ ucwords($donation->status) ?? '' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
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
</div>
@endsection

@section('my-script')

@endsection
