@extends('layouts.public')
@section('title', 'Security Questions')
@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
@endsection

@section('content')
    @include('public.header')
    <main id="main" class="login-pg">
        <form class="modal-content animate px-5" action="{{ route('security-questions') }}" method="post">
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
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">Security
                                Questions</button>
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
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            @error('security_questions')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <select class="form-select mb-0 mt-5 select1" aria-label="Default select example" name="security_questions[0][question_id]">
                                <option value="">Select</option>
                                @foreach ($questions as $key => $question)
                                <option value="{{ $question->id ?? '' }}" @if(old('security_questions.0.question_id') == $question->id) selected @endif>{{ $question->question ?? '' }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="hidden" name="security_questions[{{ $key }}][question_id]" value="{{ $question->id ?? '' }}"/
                            <input class="mb-0 mt-5 form-control" aria-label="Default select example" value="{{ $question->question ?? '' }}" disabled/> --}}
                            <textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3" name="security_questions[0][answer]">{{ old('security_questions.0.answer') }}</textarea>
                            @error('security_questions.0.answer')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <select class="form-select mb-0 mt-5 select2" aria-label="Default select example" name="security_questions[1][question_id]">
                                <option value="">Select</option>
                                @foreach ($questions as $key => $question)
                                <option value="{{ $question->id ?? '' }}" @if(old('security_questions.1.question_id') == $question->id) selected @endif>{{ $question->question ?? '' }}</option>
                                @endforeach
                            </select>
                            <textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3" name="security_questions[1][answer]">{{ old('security_questions.1.answer') }}</textarea>
                            @error('security_questions.1.answer')
                                <br><span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <select class="form-select mb-0 mt-5 select3" aria-label="Default select example" name="security_questions[2][question_id]">
                                <option value="">Select</option>
                                @foreach ($questions as $key => $question)
                                <option value="{{ $question->id ?? '' }}" @if(old('security_questions.2.question_id') == $question->id) selected @endif>{{ $question->question ?? '' }}</option>
                                @endforeach
                            </select>
                            <textarea class="mb-0" style="background: #F9F9F9;" placeholder="Answer" rows="3" name="security_questions[2][answer]">{{ old('security_questions.2.answer') }}</textarea>
                            @error('security_questions.2.answer')
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".select1").change(function(){
                var selectedval=$(this).val();
                $(".select2").html("");
                $(".select3").html("");
                $('.select1').find('option').clone().appendTo('.select2');
                $('.select1').find('option').clone().appendTo('.select3');
                $(".select2 option[value='"+selectedval+"']").remove();
                $(".select3 option[value='"+selectedval+"']").remove();
            })
            $(".select2").change(function(){
                var selectedval=$(this).val();
                var selectedval2=$('.select1').val();
                $(".select3").html("");
                $('.select1').find('option').clone().appendTo('.select3');
                $(".select3 option[value='"+selectedval+"']").remove();
                $(".select3 option[value='"+selectedval2+"']").remove();
            })
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

