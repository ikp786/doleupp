@extends('layouts.public')

@section('title', 'Bank Details - Edit')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

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
                                Bank Details - Edit
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-10 p-5">
                            <div class="text-center">
                                <div class="avatar-upload">
                                    {{-- <div class="avatar-edit">
                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"></label>
                                    </div> --}}
                                    <div class="avatar-preview">
                                        <div id="imagePreview" style="background-image: url({{ asset(auth()->user()->image ?? 'assets/img/profile-pic.png') }});">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sign-up-tabs">
                                <ul class="nav nav-tabs d-block text-center mb-4" id="myTab" role="tablist">
                                    <li class="nav-item d-inline-block" role="presentation">
                                        <a href="{{ route('profile-edit') }}">
                                            <button class="nav-link" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">Personal Details</button>
                                        </a>
                                    </li>
                                    <li class="nav-item d-inline-block" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                            aria-selected="false">Banking Information</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <form action="{{ route('bank-detail-edit') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="mb-0" list="banks" placeholder="Bank of America " name="bank_name" value="{{ old('bank_name') ?? $bank_detail->bank_name ?? '' }}">

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
                                                    <input type="text" class="mb-0" placeholder="65983214578" name="account_number" value="{{ old('account_number') ?? $bank_detail->account_number ?? '' }}">
                                                    @error('account_number')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="mb-0" placeholder="HGH12346" name="routing_number" value="{{ old('routing_number') ?? $bank_detail->routing_number ?? '' }}">
                                                    @error('routing_number')
                                                        <br><span class="text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 text-center mt-4">
                                                    <button type="submit">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
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
    <script type="text/javascript">
        $('#searchForm').on('shown.bs.collapse', function() {
            // focus input on collapse
            $("#search").focus()
        })
    </script>
@endsection
