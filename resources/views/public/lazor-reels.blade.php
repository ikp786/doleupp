@extends('public.myaccount')

@section('my-title', 'DoleUpp Reels')

@section('my-content')
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
            {{--@if(auth()->user()->subscription_ends_at == NULL)
            <a href="{{ route('subscription') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Subscribe Now</a>
            @elseif(auth()->user()->subscription_ends_at < \Carbon\Carbon::now())
            <a href="{{ route('subscription-renew') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Renew Subscription</a>
            @else
                <a href="{{ route('donation-request') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Add New</a>
            @endif--}}
            <a href="{{ route('donation-request') }}" class="btn-get-started scrollto" style="color: white;margin-left: 50px;">Add New</a>
        </h4>
        {{--{{ $reels }}--}}
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
                            <a href="{{ route('reels.show', ['slug' => $donation->id]) }}"><h5 class="mb-0">{{ $donation->caption ?? '' }}</h5><p>{{ $donation->Description ?? '' }}</p></a>
                            <span><a href="{{ route('fundraisers.show', ['slug' => $donation->category->slug]) }}">{{ $donation->category->name ?? '' }} @if($donation->is_prime == 'Yes') (Prime) @endif</a> &nbsp; | &nbsp; {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
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
                        <a href="{{route('donation-request-edit', ['id' => $donation->id])}}">
                            <div class="form-check3" @if($donation->donation_received > 0) style="right: 0;" @endif>
                                <span class="form-check-input d-none"></span>
                            </div>
                        </a>
                        @if($donation->donation_received == 0)
                        <a href="{{route('donation-request-delete', ['id' => $donation->id])}}">
                            <div class="form-check2">
                                <span class="form-check-input d-none"></span>
                            </div>
                        </a>
                        @endif
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
@endsection

@section('my-script')
@endsection
