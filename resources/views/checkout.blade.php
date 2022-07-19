@extends('layouts.public')

@section('title', 'Checkout')

@section('meta')
<meta content="" name="description">
<meta content="" name="keywords">
@endsection

@section('style')
<style type="text/css">
    .readable_allowed {
        cursor: pointer;
        background: #fff;
        box-shadow: 0 0px 15px rgb(0 0 0 / 10%);
        border-radius: 14px;
        padding: 15px;
        margin-bottom: 25px;
    }

    .readable_allowed h5 {
        display: initial;
        margin-left: 15px;
    }

    ul.list-part li.list-group-item {
        border: none;
    }

    ul.list-part li.list-group-item span.badge {
        color: black;
        float: right;
    }

    ul.list-part {
        padding-left: 0;
    }

    .readable_allowed img {
        width: 55px;
    }
</style>@endsection

@section('content')
@include('public.header')



<main id="main" class="privacy-policy profile">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services" style="background: none;">
        <div class="container" data-aos="fade-up">

            <div class="icon-box">
                <div class="row">
                    <div class="col-md-12 p-5">
                        <h4>Checkout</h4>
                        <div class="readable_allowed" onclick="setpaymenttype('paypal')">
                            <img src="https://stageofproject.com/lazor/assets/img/paypal.png">
                            <h5>Paypal</h5>
                        </div>

                        <div class="readable_allowed" onclick="setpaymenttype('stripe')">
                            <img src="https://stageofproject.com/lazor/assets/img/4140097991578289015.svg">
                            <h5>Debit/Credit Card</h5>
                        </div>

                        <div class="readable_allowed" onclick="setpaymenttype('gpay')">
                            <!-- <img src="https://stageofproject.com/lazor/assets/img/online-payment.png"> -->
                            @include('gpaytemplate')
                        </div>

                    </div>
                    <div class="col-md-8">

                    </div>
                    {{--<div class="col-lg-4 col-md-12 p-5">
                        <ul class="list-part">
                            <!--         <li class="list-group-item">
                <label>Sub Total</label>
                <span class="badge">{{$amount}}</span>
            </li> -->
                            <!--
   <li class="list-group-item">
        <label>Processing Fee</label>
          <span class="badge">+ $60.00</span>
      </li>

      <li class="list-group-item">
        <label>Subscription Fee</label>
          <span class="badge">+ $60.00</span>
      </li> -->
                            <hr>
                            <!--   <li class="list-group-item">
        <label>Total</label>
        <span class="badge">{{$amount}}</span>
      </li> -->
                        </ul>
                        <input type="text" id="paymenttype" name="paymenttype">
                        <!-- <a href="#" class="btn-get-started scrollto" style="width:100%;border-radius: 10px;text-align: center;">Continue to Pay</a> -->

                    </div>--}}
                </div>
            </div>


        </div>
    </section><!-- End Services Section -->



</main><!-- End #main -->
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.0.1/jquery.payment.min.js"></script>
<script>
    function setpaymenttype(type) {
        let paymenttype = "<?php echo $type; ?>";
        $("#paymenttype").val(type)
        if (type == "stripe") {
            if (paymenttype == "donation" || paymenttype == "corporation") {
                window.location.href = "{{url('api/stripedonation?id=')}}{{$orderId}}&paymentId={{$paymentId}}&type="+paymenttype;
            } else{
                window.location.href = "{{url('api/stripe?id=')}}{{$orderId}}&paymentId={{$paymentId}}&type="+paymenttype;
            }
        }
        if (type == "paypal") {
            window.location.href = "{{session()->get('paypalLink')}}";
        }
    }
</script>


{{-- <script src="../lib/jquery.payment.js"></script> --}}


{{-- <script>
    jQuery(function($) {
        $('[data-numeric]').payment('restrictNumeric');
        $('#card_number').payment('formatCardNumber');
        $('#expiry_date').payment('formatCardExpiry');
        $('#cvv').payment('formatCardCVC');

        $.fn.toggleInputError = function(erred) {
            this.parent('.form-group').toggleClass('has-error', erred);
            return this;
        };

        $('form').submit(function(e) {
            e.preventDefault();

            var cardType = $.payment.cardType($('#card_number').val());
            $('#card_number').toggleInputError(!$.payment.validateCardNumber($('#card_number').val()));
            $('#expiry_date').toggleInputError(!$.payment.validateCardExpiry($('#expiry_date').payment('cardExpiryVal')));
            $('#cvv').toggleInputError(!$.payment.validateCardCVC($('#cvv').val(), cardType));
            $('.cc-brand').text(cardType);

            $('.validation').removeClass('text-danger text-success');
            $('.validation').addClass($('.has-error').length ? 'text-danger' : 'text-success');
        });
    });
  </script> --}}
@endsection
