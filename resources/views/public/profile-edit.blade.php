@extends('layouts.public')

@section('title', 'Profile - Edit')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
    --}}
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
<style>
    .ui-datepicker-trigger,
    .cal-icn {
        float: right;
        margin-right: 12px;
        margin-top: -43px;
        cursor: pointer;
        position: relative;
        z-index: 2;
    }
</style>
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="privacy-policy profile-edt">

        <!-- ======= Services Section ======= -->
        <section id="services" class="services" style="background: none;">
            <div class="container" data-aos="fade-up">

                <div class="icon-box">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-hdr">
                                Profile - Edit
                            </div>
                        </div>
                        <div class="col-md-1"></div>

                        <div class="col-md-10 p-5">
                            <form action="{{ route('profile-edit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="text-center">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" name="image" />
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview"
                                                style="background-image: url({{ asset(auth()->user()->image ?? 'assets/img/profile-pic.png') }});">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="sign-up-tabs">
                                    <ul class="nav nav-tabs d-block text-center mb-4" id="myTab" role="tablist">
                                        <li class="nav-item d-inline-block" role="presentation">
                                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                                aria-selected="true">Personal Details</button>
                                        </li>
                                        <li class="nav-item d-inline-block" role="presentation">
                                            <a href="{{ route('bank-detail-edit') }}">
                                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                                    data-bs-target="#profile" type="button" role="tab"
                                                    aria-controls="profile" aria-selected="false">Banking
                                                    Information</button>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                                            aria-labelledby="home-tab">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="mb-0" placeholder="Rick Jones" name="name"
                                                        value="{{ old('name') ?? (auth()->user()->name ?? '') }}">
                                                    @error('name')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    {{--<input type="text" class="mb-0" placeholder="rick_jones" name="username"
                                                        value="{{ auth()->user()->username ?? '' }}" readonly>
                                                    <input type="text" class="mb-0" placeholder="Atlanta University"
                                                        name="university"
                                                        value="{{ old('university') ?? (auth()->user()->university ?? '') }}">
                                                    @error('university')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <input type="text" class="mb-0" placeholder="Business" name="occupation"
                                                        value="{{ old('occupation') ?? (auth()->user()->occupation ?? '') }}">
                                                    @error('occupation')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror--}}
                                                    {{--<input type="text" class="mb-0" placeholder="Atlanta, GA" name="address"
                                                        value="{{ old('address') ?? (auth()->user()->address ?? '') }}">
                                                    @error('address')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror--}}

                                                    <input type="text" class="mb-0" placeholder="State" name="state" value="{{ old('state') ?? auth()->user()->state }}">
                                                    @error('state')
                                                    <br><span class="text-danger" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                    <input type="text" class="mb-0" placeholder="Country" name="country" value="{{ old('country') ?? auth()->user()->country }}">
                                                    @error('country')
                                                    <br><span class="text-danger" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="mb-0" placeholder="rick_jones@gmail.com"
                                                        name="email"
                                                        value="{{ old('email') ?? (auth()->user()->email ?? '') }}"
                                                        readonly>

                                                    <div class="cal-main">
                                                        <input type="text" id="dp1" class="datepicker" readonly="readonly" placeholder="DOB" name="dob" value="{{ old('dob') ?? (auth()->user()->dob ? Carbon\Carbon::parse(auth()->user()->dob)->format('m/d/Y') : '') }}">
                                                        {{--<label for="dp1" class="cal-icn">
                                                            <img src="assets/img/calendar.svg" alt="">
                                                        </label>--}}
                                                        @error('dob')
                                                            <br><span class="text-danger" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    {{-- <input type="text" placeholder="15/11/1990" name="dob"
                                                        value="{{ old('dob') ?? (auth()->user()->dob ?? '') }}">
                                                    @error('dob')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror --}}
                                                    <textarea class="mb-0" placeholder="Tell us about yourself " rows="3"
                                                        name="about">{{ old('about') ?? (auth()->user()->about ?? '') }}</textarea>
                                                    @error('about')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 text-center mt-4">
                                                    <button type="submit" class="d-inline-block">Save</button>
                                                </div>
                                            </div>

                                        </div>

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
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                changeMonth: true,
                changeYear: true,
                maxDate: '-4y',
                showOn: "button",
                buttonImage: "{{asset('assets/img/calendar.svg')}}",
                buttonImageOnly: true
                // startDate: '0d'
            });
        });
    </script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
    </script>
    <script type="text/javascript">
        $('#searchForm').on('shown.bs.collapse', function() {
            // focus input on collapse
            $("#search").focus()
        })
    </script>
@endsection
