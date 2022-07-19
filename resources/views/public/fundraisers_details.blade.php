@extends('layouts.public')

@section('title', $details->name.' DoleUpp Request')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
<style type="text/css">
    .ajax-load{
        background: #E1E1F7;
        padding: 10px 0px;
        width: 100%;
        margin-top: 40px;
    }
</style>
@endsection

@section('content')
    @include('public.header')
    <main id="main" class="fundraisers">
        <!-- ======= Hero Section ======= -->
        <section id="hero" class="d-flex  align-items-center">
            <div class="container text-center" data-aos="zoom-out" data-aos-delay="100">
                <h1>{{ $details->name ?? '' }} DoleUpp Request</h1>
                {{--<div class="">
                    <a href="{{ route('donation-request') }}" class="btn-get-started scrollto">Browse</a>
                </div>--}}
            </div>
        </section><!-- End Hero -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">
                <div class="row" id="post-data">
                    @include('public.reels')
                </div>
                <div class="ajax-load text-center" style="display:none">
                    <p>Loading... <img src="https://c.tenor.com/I6kN-6X7nhAAAAAj/loading-buffering.gif" width="30"></p>
                </div>
            </div>
        </section>
        <!-- End Services Section -->
    </main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="padding-bottom:24px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">DoleUpp Now</h5>
                {{--<a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>--}}
            </div>
            <form method="post" action="{{url('/donation/make-payment')}}">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="number" id="donationamount" name="donations[0][amount]" value="" class="form-control">
                    <input type="hidden" id="request_id" name="donations[0][donation_request_id]" value="" class="form-control">
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary">DoleUpp</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{--@include('public.footer')--}}
@endsection

@section('script')
    <script type="text/javascript">
	var page = 1;
	$(window).scroll(function() {
	    if($(window).scrollTop() + $(window).height() >= $(document).height()-50) {
	        page++;
	        loadMoreData(page);
	    }
	});

	function loadMoreData(page){
	    $.ajax({
            url: '?page=' + page,
            type: "get",
            beforeSend: function() {
                $('.ajax-load').show();
            }
        })
        .done(function(data){
            if(data.html == " " || data.html == ""){
                $('.ajax-load').html("No more reels found");
                return;
            }
            $('.ajax-load').hide();
            $("#post-data").append(data.html);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError){
            alert('server not responding...');
        });
	}
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        /*$(document).on("click", ".wishlist-create", function () {
            $('.wishlist-create').prop('disabled', true);
            const donation_request_id = $(this).data('id');
            // console.log(donation_request_id);
            $.ajax({
                url: "{{ route('wishlist.create') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();
                }
            })
            .done(function(data){
                if(data.success == true) {
                    toastr.options = { "progressBar" : true }
                    toastr.success(data.message)
                    setTimeout(function () {
                        window.location.href = "{{ route('holding-area') }}";
                    }, 5000);
                } else {
                    toastr.options = { "progressBar" : true }
                    toastr.error(data.message)
                    $('.wishlist-create').prop('disabled', false);
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                toastr.options = { "progressBar" : true }
                toastr.warning('server not responding...')
                $('.wishlist-create').prop('disabled', false);
            });
        });*/
    </script>
@endsection

