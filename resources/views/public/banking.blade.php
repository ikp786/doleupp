@extends('layouts.public')

@section('title', 'Banking')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="#" method="post">
            <div class="container">
                <div class="section-title pb-0 mb-5">
                    <h3>Banking Information</h3>
                </div>

                <div class="form-group">
                    <input type="text" name="bank_name" placeholder="Bank Name" id="bank_name">
                </div>
                <div class="form-group">
                    <input type="text" name="routing_number" placeholder="Routing Number" id="routing_number" size="9">
                </div>
                <div class="form-group">
                    <input type="text" name="account_number" placeholder="Account Number" id="account_number" minlength="9" maxlength="16">
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="" id="terms_and_conditions" required>
                    <label class="form-check-label" for="terms_and_conditions">Accept Terms & Conditions | User Agreement</label>
                </div>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" value="" id="privacy_policy" required>
                    <label class="form-check-label" for="privacy_policy">Accept Privacy Policy</label>
                </div>

                <button type="submit">Submit</button>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')

@endsection
