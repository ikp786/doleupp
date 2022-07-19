@extends('public.myaccount')

@section('my-title', 'My Account')

@section('my-style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-selection__choice {
        background: #d1d8be !important;
    }
    .select2-selection__choice .select2-selection__choice__remove {
        width: 20px;
        padding: 0px;
        margin: 0px;
        border-radius: 0px;
        color: #ffffff !important;
    }
    .select2-search .select2-search__field {
        font-family: 'Navigo' !important;
        font-size: 14px !important;
        margin-left: 15px !important;
    }
</style>
@endsection

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
{{--        <h4>Donate to DoleUpp</h4>--}}
        @if(auth()->user()->subscription_ends_at == NULL)
            <div class="section-title">
                <h3>Accept Recipient Subscription</h3>
            </div>
            <div class="subscription">
                <span>Yearly Subscription</span>
                <h2>$60.00</h2>
                <p>per year</p>
                <a class="bg-grn" href="{{ route('subscription.payment') }}"
                   onclick="event.preventDefault();
                            document.getElementById('subscription-payment').submit();">
                    {{ __('Accept Subscription') }}
                </a>

                <form id="subscription-payment" action="{{ route('subscription.payment') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        @else
            @php
            $diff = 0;
            @endphp
            <div class="section-title">
                <h3 style="color: #FF0000;">
                    @if(auth()->user()->subscription_ends_at < \Carbon\Carbon::now())
                        Your Subscription Expired at {{ auth()->user()->subscription_ends_at }}
                    @else
                        @php
                            $date = \Carbon\Carbon::parse(auth()->user()->subscription_ends_at);
                            $now = \Carbon\Carbon::now();
                            $diff = $date->diffInDays($now);
                        @endphp
                        Your Subscription Expiring in {{ $diff }} Days
                    @endif
                </h3>
            </div>
            <div class="subscription">
                <span>Yearly Subscription</span>
                <h2>$60.00</h2>

                @if($diff <= 7)
                <p>per year</p>
                <a class="bg-grn" href="{{ route('subscription.payment') }}"
                   onclick="event.preventDefault();
                            document.getElementById('subscription-payment').submit();">
                    {{ __('Renew Subscription') }}
                </a>

                @endif
                <form id="subscription-payment" action="{{ route('subscription.payment') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@section('my-script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-category-multiple').select2({
        placeholder: 'Select at least one category',
        miniimumSelectionLength: 1
    });
});
$("#selectall").click(function(){
    if($("#selectall").is(':checked') ){
        $(".js-category-multiple > option").prop("selected","selected");
        $(".js-category-multiple").trigger("change");
    }else{
        $(".js-category-multiple > option").removeAttr("selected");
        $(".js-category-multiple").trigger("change");
    }
});
$("input[name$='subscription_type']").click(function() {
    var test = $(this).val();
    $("div.desc").hide();
    $("#sy_" + test).show();
});
</script>
@endsection
