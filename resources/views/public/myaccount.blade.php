@extends('layouts.public')



@section('title')

    @yield('my-title')

@endsection



@section('meta')

    <meta content="" name="description">

    <meta content="" name="keywords">

@endsection



@section('style')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="assets/js/accordion.js"></script>

    @yield('my-style')

@endsection



@section('content')

    @include('public.header')



    <main id="main" class="privacy-policy my-account">

        <!-- ======= Services Section ======= -->

        <section id="services" class="services" style="background: none;">

            <div class="container" data-aos="fade-up">

                <div class="icon-box">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="sec-hdr">

                                @yield('my-title')

                            </div>

                        </div>

                    </div>

                    <div class="container">

                        <div class="row">

                            <div class="col-md-3 p-4">

                                <ul class="nav nav-tabs" role="tablist">

                                    <li class="nav-item">

                                        <a href="{{ route('home') }}" class="nav-link {{ (request()->is('my-account')) ? 'active' : '' }}">My Account</a>

                                    </li>

                                    <li class="nav-item">

                                        <a href="{{ route('lazor-reels') }}" class="nav-link {{ (request()->is('lazor-reels')) ? 'active' : '' }}">My DoleUpp Reels</a>

                                    </li>

                                    <li class="nav-item">

                                        <a href="{{ route('my-donations') }}" class="nav-link {{ request()->is('my-donations') ? 'active' : '' }}">My DoleUpp Sent</a>

                                    </li>
                                    <li class="nav-item">

                                        <a href="{{ route('lazor-corporate') }}" class="nav-link {{ (request()->is('lazor-donations') || request()->is('lazor-donations/*')) ? 'active' : '' }}">My Corporate DoleUpp</a>

                                    </li>

                                    <li class="nav-item">

                                        <a href="{{ route('holding-area') }}" class="nav-link {{ (request()->is('my-holding-area')) ? 'active' : '' }}">DoleUpp Cart</a>

                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('my-wallet') }}" class="nav-link {{ (request()->is('my-wallet')) ? 'active' : '' }}">My Wallet</a>
                                    </li>

                                    <li class="nav-item">

                                        <a href="{{ route('account-settings') }}" class="nav-link {{ (request()->is('account-settings')) ? 'active' : '' }}">Account

                                            Settings</a>

                                    </li>

                                    <li class="nav-item">

                                    <!--     <a href="{{ route('help-center') }}" class="nav-link {{ (request()->is('help-center')) ? 'active' : '' }}">Help Center</a> -->
                                            <a href="{{ route('faq') }}" class="nav-link {{ (request()->is('faq')) ? 'active' : '' }}">FAQ's</a>

                                    </li>

                                    @auth()

                                    <li class="nav-item">

                                        <a class="nav-link" href="{{ route('logout') }}"

                                            onclick="event.preventDefault();

                                            document.getElementById('logout-form').submit();">

                                            {{ __('Sign Out') }}

                                        </a>



                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">

                                            @csrf

                                        </form>

                                    </li>

                                    @endauth

                                    <li class="nav-item">
                                        <a href="{{ route('donation.cashout') }}" class="{{ (request()->is('cashouts')) ? 'active' : '' }} btn-get-started d-block text-center">CASH OUT</a>
                                    </li>

                                </ul>
                            </div>

                            <div class="col-md-9">
                                <div id="content" class="tab-content" role="tablist">

                                    <div id="pane-G" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-G">

                                        @yield('my-content')

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section><!-- End Services Section -->

    </main><!-- End #main -->



    {{-- @include('public.footer') --}}

@endsection



@section('script')

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>



    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>



    @yield('my-script')

@endsection

