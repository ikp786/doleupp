@extends('public.myaccount')

@section('my-title', 'My Corporate DoleUpp Detail')

@section('my-content')
<div class="card-header" role="tab" id="heading-A">
    <h5 class="mb-0">
        <a class="collapsed" data-toggle="collapse" href="#collapse-A"
            aria-expanded="false" aria-controls="collapse-A">
            My Corporate DoleUpp Detail
        </a>
    </h5>
</div>
<div id="collapse-A" class="collapse" data-parent="#content" role="tabpanel"
    aria-labelledby="heading-A">
    <div>
        <a href="{{ route('lazor-corporate') }}" class="btn-get-started scrollto"><i class="bi bi-arrow-left-short"></i> Back</a>
    </div>
    <div class="card-body">
        <div class="donrs" style="border: none;">
            <div class="row">
                <div class="col-md-2">
                    <img src="{{ asset('assets/img/footer-logo.svg') }}" width="100" height="100" class="rounded">
                </div>
                <div class="col-md-6 text-left">
                    <h6 class="mb-0">
                        <ol>
                            @foreach(App\Models\Category::whereIn('id', explode(', ', $lazor_donation->categories))->get() as $category)
                                <li>{{ $category->name }}</li>
                            @endforeach
                        </ol>
                    </h6>
                    <span>Payment ID:- {{ $lazor_donation->payment_id ?? '' }} <br> {{ date('m/d/Y h:i A', strtotime($lazor_donation->created_at)) ?? '' }}</span>
                    <p>{{ $lazor_donation->description ?? '' }}</p>
                </div>
                <div class="col-md-3 text-left">
                    <p class="ttl-prc mt-3">Amount : ${{ number_format($lazor_donation->amount ?? 0, 2) }}</p>
                    <p>Payment Status :
                        @if($lazor_donation->status == 'success')
                        <span class="text-success">Success</span>
                        @else
                        <span class="text-danger">{{ ucwords($lazor_donation->status) ?? '' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <hr>
        <div class="text-center mt-5">
            <h4 style="color: #93c01f;">DoleUpp by your behalf</h4>
        </div>

        @if(count($donations) > 0)
        @foreach($donations as $donation)
        <div class="donrs">
            <div class="row">
                <div class="col-md-2">
                    <a href="{{ $donation->donation_request->video ?? '' }}" class="ply-video"><img src="{{ $donation->donation_request->thumbnail }}" width="100" height="100" class="rounded"></a>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('reels.show', ['slug' => $donation->donation_request->id]) }}"><h4 class="mb-0">{{ $donation->donation_request->caption ?? '' }}</h4></a>
                    <span>Payment ID:- {{ $donation->payment_id ?? '' }} <br> <a href="{{ route('donors', ['username' => $donation->donation_to_user->id]) }}">{{ $donation->donation_to_user->name ?? '' }}</a> &nbsp; | &nbsp; {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
                </div>
                <div class="col-md-3 text-left">
                    <p class="ttl-prc mt-3">Amount : ${{ number_format($donation->amount ?? 0, 2) }}</p>
                    {{-- <p>Payment Status :
                        @if($donation->status == 'earned' || $donation->status == 'redeemed')
                        <span class="text-success">Success</span>
                        @else
                        <span class="text-danger">{{ ucwords($donation->status) ?? '' }}
                        @endif
                    </p> --}}
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
@endsection

@section('my-script')

@endsection
