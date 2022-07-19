@extends('public.myaccount')

@section('my-title', 'Cashouts')

@section('style')
@endsection

@section('my-content')
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
        <h4 class="d-flex justify-content-between">Cash Out <a href="" class="d-none">All</a>
        </h4>
        <div class="">
        @if(count($cashouts) > 0)
        @php $cashout_total=0; @endphp
        @foreach($cashouts as $key => $cashout)
        {{-- {{ $cashout }} --}}
        <div class="">
        <input class="form-check-input" type="hidden" name="donations[{{$key}}][cashout]" value="1" id="flexCheckDefault{{$key}}" />
        <label for="flexCheckDefault{{$key}}" style="height: auto;" class="donrs d-block">
            <div class="row">
                <input type="hidden" name="donations[{{$key}}][id]" value="{{ $cashout->id }}"/>
                <div class="col-md-2">
                    <a href="{{ $cashout->video ?? '' }}" class="ply-video"><img src="{{ $cashout->thumbnail ?? '' }}" class="rounded" width="100" height="100"></a>
                    <div class="rating-icon1">
                        <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                        {{ number_format($cashout->rating_count, 1) }}
                    </div>
                </div>
                <div class="col-md-6 text-left">
                    <h4 class="mb-0 mt-3">
                        {{--<a href="{{ route('reels.show', ['slug' => $cashout->id]) }}">{{ $cashout->caption ?? '' }}</a>--}}
                        {{ $cashout->caption ?? '' }}
                    </h4>
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
                {{--<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="donations[{{$key}}][cashout]" value="1"
                        id="flexCheckDefault" />
                </div>--}}
            </div>
        </label>
        </div>
        @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <div class="d-flex justify-content-between">
                    <p>Amount Withdrawn</p>
                    <p><b>${{ number_format($cashout_total ?? 0, 2) }}</b></p>
                </div>
                @php
                    $cashout_fee = ($cashout_total/100)*$settings->cash_out_fee;
                    $cashout_commission = ($cashout_total/100)*$settings->cash_out_commission;
                    $balance = $cashout_total-($cashout_fee+$cashout_commission);
                @endphp
                <div class="d-flex justify-content-between">
                    <p>Subscription Fees</p>
                    <p><b>+ ${{ number_format(0, 2) }}</b></p>
                </div>
                <div class="d-flex justify-content-between">
                    <p>Administrative Fees ({{ $settings->cash_out_commission ?? 0 }}%)</p>
                    <p><b>- ${{ number_format($cashout_commission ?? 0, 2) }}</b></p>
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p>Donation Fees ({{ $settings->cash_out_fee ?? 0 }}%)</p>
                    <p><b>- ${{ number_format($cashout_fee ?? 0, 2) }}</b></p>
                </div>
                <div class="d-flex justify-content-between">
                    <p>Balance</p>
                    <p><b>$ {{ number_format($balance, 2) }}</b></p>
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
                <button type="submit" class="btn-get-started d-block text-center">Withdraw</button>
            </div>
        </div>
        @else
        <div class="donrs">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-6 text-left">
                    <h4 class="mb-0">You have successfully cash out ${{ $total_cashout ?? 0 }}</h4>
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

@endsection
