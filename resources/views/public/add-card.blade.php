@extends('layouts.public')

@section('title', 'Add Card')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <style>
        .card {
            border: none;
            max-width: 450px;
            border-radius: 15px;
            margin: 150px 0 150px;
            padding: 35px;
            padding-bottom: 20px !important
        }

        .heading {
            color: #C1C1C1;
            font-size: 14px;
            font-weight: 500
        }

        .text-warning {
            font-size: 10px;
            font-weight: 400;
            color: #A9A9A9 !important;
            margin: 10px 0px 0 15px;
        }

        #cno {
            transform: translateY(-10px)
        }

        input {
            font-weight: bold;
            border-radius: 0;
            border: 0
        }

        .form-group {
            border: 1px solid #DCDCDC;
            border-radius: 10px;
            margin-bottom: 15px;
            height: 55px;
        }

        .form-group input {
            border: none;
            padding: 13px 15px 10px;
            margin: 0;
            background: transparent;
            color: #000;
            height: auto;
        }

        .form-group input:focus {
            border: 0;
            outline: 0
        }

    </style>
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="{{ route('add-card') }}" method="post">
            @csrf
            <div class="container">
                <div class="section-title pb-0 mb-5">
                    <h3>Add Debit Card</h3>
                </div>

                <div class="form-group">
                    <p class="text-warning mb-0">Card Number</p>
                    <input type="text" name="card_number" placeholder="1234 5678 9012 3457" size="17" id="card_number" minlength="19" maxlength="19" value="{{ old('card_number') ?? '' }}">
                </div>
                @error('card_number')
                    <br><span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                {{-- <div class="form-group">
                    <p class="text-warning mb-0">Cardholder's Name</p> <input id="cno" type="text" name="name"
                        placeholder="Name" size="17">
                </div> --}}

                <div class="row d-flex">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <p class="text-warning mb-0">Expiry Date</p>
                            <input id="expiry_date" type="text" name="expiry_date" placeholder="MM/YYYY" size="7" minlength="9" maxlength="9" value="{{ old('expiry_date') ?? '' }}">
                        </div>
                        @error('expiry_date')
                            <br><span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <p class="text-warning mb-0">CVV</p>
                            <input id="cvv" type="password" name="cvv" placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" value="{{ old('cvv') ?? '' }}">
                        </div>
                        @error('cvv')
                            <br><span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <button type="submit">Save</button>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.0.1/jquery.payment.min.js"></script>
    <script>
        $('#card_number').formatCardNumber();
        $('#expiry_date').formatCardExpiry();
        $('#cvv').formatCardCVC();
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
