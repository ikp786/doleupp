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
        <h4>Donate to DoleUpp</h4>
        @if(auth()->user()->paypal_agreement_status == 1)
            <h5>
                Your Plan Details
            </h5>
            <table width="80%">
                <tr>
                    <td>Plan (Monthly)</td>
                    <td>Basic Plan</td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td>$50</td>
                </tr>
                <tr>
                    <td>Date of Activation</td>
                    <td>{{ auth()->user()->paypal_agreement_date ?? '' }}</td>
                </tr>
                <tr>
                    <td>Paypal Agreement ID</td>
                    <td>{{ auth()->user()->paypal_agreement_id ?? '' }}</td>
                </tr>
            </table>
            <form class="animate" action="{{ route('paypal.cancel') }}" method="post" enctype="multipart/form-data">
                @csrf
                {{--<input type="hidden" name="paypal_agreement_id" value="{{ auth()->user()->paypal_agreement_id }}">--}}
                <div class="container">
                    <button type="submit">Cancel Subscription</button>
                </div>
            </form>
        @else
            <form class="animate" action="{{ route('donation.lazor') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="container">
                    {{-- @if($errors->has()) --}}
                        {{-- @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach --}}
                    {{-- @endif --}}
                    {{-- <div class="section-title">
                        <h3>DoleUpp Request</h3>
                    </div> --}}

                    <input type="checkbox" id="selectall">&nbsp Select All
                    <select class="form-select js-category-multiple" aria-label="Default select example" placeholder="Description about Dontation" name="categories[]" multiple="multiple" required="required">
                        {{-- <option value="" selected disabled>Select Category</option> --}}
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{in_array($category->id, old("categories") ?: []) ? "selected": ""}}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categories')
                        <br><span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <textarea placeholder="Description about Dontation" rows="3" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <br><span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="funkyradio">
                        <div class="funkyradio-primary">
                            <input type="radio" name="subscription_type" id="radio2" value="monthly" @if(old('subscription_type') != 'onetime') checked @endif/>
                            <label for="radio2">Monthly Subscription</label>
                        </div>
                        <div class="funkyradio-success">
                            <input type="radio" name="subscription_type" id="radio3" value="onetime" @if(old('subscription_type') == 'onetime') checked @endif/>
                            <label for="radio3">One Time DoleUpp</label>
                        </div>
                    </div>

                    <div id="sy_monthly" class="desc" @if(old('subscription_type') == 'onetime') style="display: none;" @endif>
                        <select class="mt-0 form-select" placeholder="Select DoleUpp Plan" name="donation_plan" value="{{ old('donation_plan') ?? 100 }}" required>
                            <option value="1">Basic Plan (50$)</option>
                            <option value="2">Premium Plan (100$)</option>
                            <option value="3">Gold Plan (200$)</option>
                        </select>
                        @error('donation_plan')
                        <br><span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div id="sy_onetime" class="desc" @if(old('subscription_type') != 'onetime') style="display: none;" @endif>
                        <input class="mt-0" type="text" placeholder="DoleUpp Amount (Minimum $50)" name="donation_amount" value="{{ old('donation_amount') ?? 100 }}" required min="50">
                        @error('donation_amount')
                        <br><span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <button type="submit">Pay Now</button>
                </div>

            </form>
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
