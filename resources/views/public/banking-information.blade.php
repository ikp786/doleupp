@extends('layouts.public')

@section('title', 'Banking Information')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate px-5" action="{{ route('banking-information') }}" method="post">
            @csrf
            <div class="container">
                <div class="section-title">
                    <h3>Sign Up</h3>
                </div>
                <div class="sign-up-tabs">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('personal-information') }}">
                                <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">Personal Details</button>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('security-questions') }}">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">Security
                                Questions</button>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false">Banking
                                Information</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <input class="mt-5" type="text" list="banks" placeholder="Bank Name" name="bank_name" required value="{{ old('bank_name') ?? '' }}">

                            <datalist id="banks">
                                @foreach($banks as $bank)
                                    <option>{{ $bank->name ?? '' }}</option>
                                @endforeach
                            </datalist>
                            @error('bank_name')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="text" placeholder="Routing Number" name="routing_number" required value="{{ old('routing_number') ?? '' }}">
                            @error('routing_number')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="text" placeholder="Account Number" name="account_number" required value="{{ old('account_number') ?? '' }}">
                            @error('account_number')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" value="{{ old('terms') }}" id="flexCheckDefault" required>
                                <label class="form-check-label" for="flexCheckDefault" style="font-size: 12px;">Accept <a href="{{ route('terms-and-conditions') }}">Terms
                                    & Conditions | User Agreement</a> </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="accept" value="{{ old('accept') }}" id="flexCheckDefault1" required>
                                <label class="form-check-label" for="flexCheckDefault1" style="font-size: 12px;">Accept
                                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a> </label>
                            </div>
                            <button type="submit">Submit</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                startDate: '0d'
            });

            $('.cell').click(function() {
                $('.cell').removeClass('select');
                $(this).addClass('select');
            });

        });
    </script>
@endsection
