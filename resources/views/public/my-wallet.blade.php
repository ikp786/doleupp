@extends('public.myaccount')

@section('my-title', 'My Wallet')

@section('my-content')
<div class="card-header" role="tab" id="heading-B">
    <h5 class="mb-0">
        <!-- Note: `data-parent` removed from here -->
        <a data-toggle="collapse" href="#collapse-B" aria-expanded="true"
            aria-controls="collapse-B">
            My Wallet
        </a>
    </h5>
</div>

<!-- Note: New place of `data-parent` -->
<div id="collapse-B" class="collapse show" data-parent="#content" role="tabpanel"
    aria-labelledby="heading-B">
    <div class="card-body">
        <h4>My Wallet</h4>
        <div class="row mt-4" id="calculation">
            <div class="col-md-12 text-end">
                <table class="float-right" width="100%">
                    <tr>
                        <td><p class="ttl-prc">Total Cashout :</p></td>
                        <td width="15%"><p class="ttl-prc">${{$data->cashout_total ?? 0}}</p></td>
                    </tr>
                    <tr>
                        <td><p class="ttl-prc">Administration Commission :</p></td>
                        <td><p class="ttl-prc">(-) ${{$data->cashout_commission ?? 0}}</p></td>
                    </tr>
                    <tr>
                        <td><p class="ttl-prc">Processing Fee :</p></td>
                        <td><p class="ttl-prc">(-) ${{$data->cashout_fee ?? 0}}</p></td>
                    </tr>
                    <tr class="border-top border-bottom">
                        <td><p class="ttl-prc">Cashout Received :</p></td>
                        <td><p class="ttl-prc">${{$data->cashout_received ?? 0}}</p></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12 text-end">
                <div class="mt-3">
                    <p>Note: <span style="color: #7B7B7B;">Your cashout fee is ${{ $data->amount_for_donate ?? 0 }}, do you want to use for DoleUpp.</span></p>
                </div>
            </div>
        </div>
        <form action="{{ route('donation.make-payment') }}" method="POST">
            @csrf
        @if(count($cashouts) > 0)
        @foreach($cashouts as $key=>$cashout)
        @php
        $wishlist=$cashout->donation_request;
        @endphp
        <div class="donrs dono-{{$wishlist->id}}">
            <div class="row">
                <div class="col-md-2">
                    <a href="{{ $wishlist->video ?? '' }}" class="ply-video"><img src="{{ $wishlist->thumbnail }}" width="100" height="100" class="rounded"></a>
                    <div class="rating-icon1">
                        <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                        {{ number_format($wishlist->rating_count, 1) }}
                    </div>
                </div>
                <div class="col-md-6 text-left">
                    <h4 class="mb-0"><a href="{{ route('reels.show', ['slug' => $wishlist->id]) }}">{{ $wishlist->caption ?? '' }}</a></h4>
                    <p>{{ $wishlist->category->name ?? '' }} &nbsp; | &nbsp; {{ $wishlist->user->name ?? '' }} &nbsp; | &nbsp; <img src="{{ asset('assets/img/eyeb.svg') }}">&nbsp; {{ $wishlist->views_count ?? 0 }}</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ round(100/$wishlist->donation_amount*$wishlist->donation_received) }}%" aria-valuenow="{{ round(100/$wishlist->donation_amount*$wishlist->donation_received) }}" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                    <p><b>${{ $wishlist->donation_received ?? 0 }} raised</b> of ${{ $wishlist->donation_amount ?? 0 }}</p>

                </div>
                {{--<div class="col-md-4 text-left">
                    <p>Total Cashout &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp; <b>${{ number_format($cashout->cash_out_commission+$cashout->fee_amount+$cashout->redeemed_amount ?? 0, 2) }}</b></p>
                    <p>Cashout Commission : (-) <b>${{ number_format($cashout->cash_out_commission ?? 0, 2) }}</b></p>
                    <p>Cashout Fee &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : (-) <b>${{ number_format($cashout->fee_amount ?? 0, 2) }}</b></p>
                    <p class="border-top">Cashout Received &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp; <b>${{ number_format($cashout->redeemed_amount ?? 0, 2) }}</b></p>
                    <p>Already Redeemed : <b>${{ number_format($wishlist->donation_redeemed ?? 0, 2) }}</b></p>
                    <p>Not Redeemed : <b style="margin-left: 18px;">${{ number_format($wishlist->donation_earned ?? 0, 2) }}</b></p>
                </div>--}}
                <div class="col-md-4 text-left">
                    <p>Total Cashout &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp; <b>{{ $cashout->amount ?? 0 }}</b></p>
                    <p>Cashout Commission : (-) <b>{{ $cashout->cash_out_commission ?? 0 }}</b></p>
                    <p>Cashout Fee &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : (-) <b>{{ $cashout->fee_amount ?? 0 }}</b></p>
                    <p class="border-top">Cashout Received &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp; <b>{{ $cashout->redeemed_amount ?? 0 }}</b></p>
                    {{--<p>Already Redeemed : <b>${{ number_format($wishlist->donation_redeemed ?? 0, 2) }}</b></p>
                    <p>Not Redeemed : <b style="margin-left: 18px;">${{ number_format($wishlist->donation_earned ?? 0, 2) }}</b></p>--}}
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
        </form>
    </div>
</div>
@endsection

@section('my-script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@endsection
