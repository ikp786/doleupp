@extends('layouts.public')

@section('title', 'Personal Information')

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
        .passwordeyespan {
            float: right;
            margin-right: 12px;
            margin-top: -33px;
            cursor: pointer;
            position: relative;
            z-index: 2;
        }
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
    <main id="main" class="login-pg">
        <form class="modal-content animate px-5" action="{{ route('personal-information') }}" method="post" enctype="multipart/form-data" autocomplete="off" autofocus="off">
            @csrf
            <div class="container">
                <div class="section-title">
                    <h3>Sign Up</h3>
                </div>
                <div class="sign-up-tabs">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">Personal Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('security-questions') }}">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">Security
                                Questions</button>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('banking-information') }}">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false">Banking
                                Information</button>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url({{ asset(auth()->user()->image ?? 'assets/img/profile-pic.png') }});">
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <input type="text" class="mb-0" placeholder="Referral Code" name="referral_code" value="{{ old('referral_code') ?? session()->get('referral_code') }}">
                            @error('referral_code')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <input type="text" class="mb-0" placeholder="Name" name="name" value="{{ old('name') ?? auth()->user()->name }}">
                            <span>Note: “This information must match the banking information”</span>
                            @error('name')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                         <!--    <input type="text" class="mb-0" placeholder="Username" name="username" value="{{ old('username') ?? auth()->user()->username }}" autocomplete=off>
                            <span>Note: “This can be any name you choose under 16 characters, no spaces”</span>
                            @error('username')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->

                            <input id="password" type="password" class="mb-0" placeholder="Password" name="password" value="{{ old('password') ?? '' }}" autocomplete=off>
                            <i class="far fa-eye-slash passwordeyespan" id="togglePassword"></i>
                            @error('password')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        <!--     <input type="text" class="mb-0" placeholder="University" name="university" value="{{ old('university') ?? auth()->user()->university }}">
                            <span>Note: Optional</span>
                            @error('university')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <input type="text" placeholder="Occupation" name="occupation" value="{{ old('occupation') ?? auth()->user()->occupation }}">
                            <span>Note: Optional</span>
                            @error('occupation')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->

                            {{--<input type="text" class="mb-0" placeholder="City & State Location" name="address" value="{{ old('address') ?? auth()->user()->address }}">
                            <span>Note: Example- Atlanta, GA</span>
                            @error('address')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror--}}

                            <input type="text" class="mb-0" placeholder="State" name="state" value="{{ old('state') ?? auth()->user()->state }}">
                            <span>Note: Example- Atlanta</span>
                            @error('state')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <input type="text" class="mb-0" placeholder="Country" name="country" value="{{ old('country') ?? auth()->user()->country }}">
                            <span>Note: Example- U.S.</span>
                            @error('country')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <input type="text" placeholder="Email" name="email" value="{{ old('email') ?? auth()->user()->email }}">
                            @error('email')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            {{-- <input type="date" placeholder="DOB" name="dob"> --}}
                            <div class="cal-main">
                                <input type="text" id="dp1" class="datepicker" readonly="readonly" placeholder="DOB" name="dob" value="{{ old('dob') }}">
                                {{--<label for="dp1" href="#" class="cal-icn">
                                    <img src="assets/img/calendar.svg" alt="">
                                </label>--}}
                                <span>Note: The age must have 4+</span>
                                @error('dob')
                                    <br><span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <textarea class="mb-0" placeholder="Tell us about yourself " rows="5" name="about">{{ old('about') ?? auth()->user()->about }}</textarea>
                            <span>Note: Up to 1000 characters. No special characters</span>
                            @error('about')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <button type="submit">Continue</button>
                            {{-- <div class="alrdy-act">Already have an account? <a href="signin">Sign In</a></div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main><!-- End #main -->
    @include('public.footer')
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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
                //buttonImage: "https://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
                buttonImage: "{{asset('assets/img/calendar.svg')}}",
                buttonImageOnly: true
                // startDate: '0d'
            });

            $('.cell').click(function() {
                $('.cell').removeClass('select');
                $(this).addClass('select');
            });
        });
    </script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            //this.classList.toggle('fa-eye');
            if(type == 'password') {
                this.classList.add('fa-eye-slash');
                this.classList.remove('fa-eye');
            } else {
                this.classList.add('fa-eye');
                this.classList.remove('fa-eye-slash');
            }
        });
    </script>
@endsection
