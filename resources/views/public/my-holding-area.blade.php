@extends('public.myaccount')

@section('my-title', 'DoleUpp Cart')

@section('my-content')
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
                    {{--<div class="reel-vie">
                        <img src="{{ asset('assets/img/eyeb.svg') }}">&nbsp; {{ $wishlist->views_count ?? 0 }}
                    </div>--}}
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ round(100/$wishlist->donation_amount*$wishlist->donation_received) }}%" aria-valuenow="{{ round(100/$wishlist->donation_amount*$wishlist->donation_received) }}" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                    <p><b>${{ $wishlist->donation_received ?? 0 }} raised</b> of ${{ $wishlist->donation_amount ?? 0 }}</p>

                </div>
                <div class="col-md-3 text-left">
                    @php
                    $donation_amt = 0;
                    if(($wishlist->donation_amount - $wishlist->donation_received) < 0) {
                        $donation_amt = $wishlist->donation_amount - $wishlist->donation_received;
                    }
                    @endphp
                    <input type="hidden" name="donations[{{ $key }}][donation_request_id]" value="{{ $wishlist->id ?? '' }}"/>
                    <input required type="number" name="donations[{{ $key }}][amount]" class="form-select mb-0 mt-4 donation_amount" list="amounts" value="{{ old('donations.'.$key.'.amount') ?? $donation_amt ?? 0 }}" min="0" max="{{ ($wishlist->donation_amount - $wishlist->donation_received) ?? 0 }}"/>
                    <datalist id="amounts">
                        <option>50</option>
                        <option>100</option>
                        <option>500</option>
                        <option>1000</option>
                        <option>1200</option>
                        <option>1500</option>
                        <option>2000</option>
                        <option>5000</option>
                        <option>10000</option>
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
                <a href="javascript:void(0);" class="wishlist-remove" data-class="dono-{{$wishlist->id}}" data-id="{{ $wishlist->id }}">
                    <div class="form-check2">
                        <span class="form-check-input d-none"></span>
                    </div>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-4" id="calculation">
            <div class="col-md-6">
                <p class="ttl-prc">DoleUpp Amount : $<span id="donation_amount"></span></p>
                <p class="ttl-prc">Processing Fee &nbsp;&nbsp;&nbsp; : $<span id="donation_amount_fee"></span></p>
                <p class="ttl-prc">Total Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $<span id="donation_amount_total"></span></p>
            </div>
            <div class="col-md-6 text-end">
                {{--<div class="login-pg">
                    <div class="custom-file-upload">
                        <label for="file"><small>If you like to share your experience with a video clip ?</small></label>
                        <input type="file" id="file" placeholder="Upload Video" name="video" accept="video/mp4"/>
                    </div>
                    <br>
                    @error('video')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>--}}
                @if($amount_for_donate > 0)
                    <div class="mt-3">
                        <input class="" type="checkbox" name="use_donation_amount" value="Yes" id="use_donation_amount">
                        <label for="use_donation_amount"><p>Note:
                                <span style="font-size: 11px; color: #7B7B7B;">Your cashout fee is ${{ number_format($amount_for_donate, 2) ?? 0 }}, do you want to use for DoleUpp.</span></p></label>
                    </div>
                @endif
                {{--<a href="" class="btn-get-started">DoleUpp Now</a>--}}
                <input type="submit" value="DoleUpp Now" class="btn-get-started" style="border: none;"/>
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
@endsection

@section('my-script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    function donation_amount() {
        let admin_commission = '{{$admin_commission}}';
        var sum = 0;
        var commission = 0;
        var numItems = $('.donation_amount').length;
        if(numItems > 0) {
            $('.donation_amount').each(function () {
                let value = 0;
                if (this.value > 0) {
                    value = this.value;
                }
                sum += parseFloat(value);
                commission = (sum / 100 * admin_commission);
                // console.log(sum);
                if (sum > 0) {
                    $('#donation_amount').html(sum);
                    $('#donation_amount_fee').html(commission);
                    $('#donation_amount_total').html(sum + commission);
                } else {
                    $('#donation_amount').html(0);
                    $('#donation_amount_fee').html(0);
                    $('#donation_amount_total').html(0);
                }
            });
        } else {
            $('#donation_amount').html(0);
            $('#donation_amount_fee').html(0);
            $('#donation_amount_total').html(0);
            $('#calculation').html('<div class="donrs"> ' +
                '<div class="row"> ' +
                '<div class="col-md-2"> ' +
                '</div> ' +
                '<div class="col-md-6 text-left"> ' +
                '<h4 class="mb-0">No Data</h4> ' +
                '</div> ' +
                '<div class="col-md-3 text-left"> ' +
                '</div> ' +
                '</div> ' +
                '</div>');
        }
    }
    donation_amount()
    $('.donation_amount').change(function (){
        donation_amount()
    });
    $('.donation_amount').keyup(function (){
        donation_amount()
    });
    $(document).ready(function (){
        $(document).on("click", ".wishlist-remove", function () {
            const donation_request_id = $(this).data('id');
            $.ajax({
                url: "{{ route('wishlist.remove') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function() {
                    $(this).attr('disabled',true);
                }
            })
            .done(function(data){
                if(data.success == true) {
                    $('.dono-'+donation_request_id).remove();
                    donation_amount();
                    toastr.options = { "progressBar" : true }
                    toastr.success(data.message)
                } else {
                    $(this).attr('disabled',false);
                    toastr.options = { "progressBar" : true }
                    toastr.error(data.message)
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                $(this).attr('disabled',false);
                toastr.options = { "progressBar" : true }
                toastr.warning('server not responding...')
            });
        });
    });
</script>
@endsection
