@extends('layouts.public')

@section('title', 'Contect Us')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="privacy-policy profile">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">

                <div class="icon-box">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-hdr d-block">
                                Contact Us
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-10 p-5">
                            <form action="{{ route('contact-us') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" placeholder="Name" name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="text" placeholder="Company Name" name="company_name" value="{{ old('company_name') }}">
                                        @error('company_name')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="text" placeholder="Email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="text" placeholder="Phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" aria-label="Reason" name="reason_id">
                                            <option value="" selected disabled>Select Reason</option>
                                            @foreach ($reasons as $reason)
                                                <option value="{{ $reason->id ?? '' }}" @if($reason->id == old('reason_id')) selected @endif>{{ $reason->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('reason_id')
                                        <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <textarea placeholder="Message" rows="8" name="message">{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <button type="submit" class="d-inline-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

    @include('public.footer')
@endsection

@section('script')

@endsection
