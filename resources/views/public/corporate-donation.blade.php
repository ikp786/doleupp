@extends('layouts.public')

@section('title', 'Corporate DoleUpp')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="{{ route('corporate.donation') }}" method="post">
{{--        <form class="modal-content animate" action="{{ route('corporate.success') }}" method="get">--}}
            @csrf
            <div class="container">
                <div class="section-title pb-0">
                    <h4>Corporate DoleUpp</h4>
                    <p class="w-100"></p>
                </div>
                <div class="funkyradio d-none">
                    @foreach ($categories as $category)
                        <div class="funkyradio-primary">
                            <input type="checkbox" name="categories[]" id="categories{{ $category }}" value="{{ $category }}" checked/>
                            <label for="categories{{ $category }}">{{ $category }}</label>
                        </div>
                    @endforeach
                </div>
                <input type="text" placeholder="Individual/Company Name" name="name" required value="{{ old('name') ?? '' }}">
                @error('name')
                <br><span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <input type="text" placeholder="Donation Amount" name="donation_amount" required value="{{ old('donation_amount') ?? '' }}">
                @error('donation_amount')
                <br><span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="terms" value="{{ old('terms') }}" id="flexCheckDefault" required>
                    <label class="form-check-label" for="flexCheckDefault" style="font-size: 12px;">Accept
                        <a href="{{ route('terms-and-conditions') }}">Terms & Conditions, </a> <a href="{{ route('privacy-policy') }}">Privacy Policy</a></label>
                </div>
                <button type="submit">Continue to Pay</button>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('my-script')
    <script>
        $('#select-all').on('click',function(event) {
            alert('1');
            if(this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
        $('#select-all').on('change', function() {
            alert('2');
            if($("#selectall").is(':checked') ){
                $(".funkyradio input").prop("checked","checked");
                //$(".funkyradio").trigger("change");
            }else{
                $(".funkyradio input").removeAttr("checked");
                //$(".funkyradio").trigger("change");
            }
        });
    </script>
@endsection
