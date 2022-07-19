<!-- ======= Header ======= -->
<header id="header" class="d-flex align-items-center">
    <div id="myOverlay" class="d-flex overlay d-none">
        <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
        <div class="overlay-content">
            {{--<input type="search" class="form-control" name="reel" placeholder="Search..." required/>
            --}}<select class="js-reels-data form-control p-0" multiple="multiple" name="reel" placeholder="Search..." required></select>
        </div>
    </div>
    <div id="myOverlay2" class="d-flex overlay d-none">
        <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
        <div class="overlay-content">
            {{--<input type="search" class="form-control" name="reel" placeholder="Search..." required/>
            --}}<select class="js-reels-data2 form-control p-0" multiple="multiple" name="reel" placeholder="Search..." required></select>
        </div>
    </div>
    <div id="myHeader" class="container-fluid d-flex align-items-center justify-content-between">

        <h1 class="logo"><a href="{{ route('index') }}"><img src="{{ asset('assets/img/brand.svg') }}" alt=""></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        {{-- <a href="index" class="logo"><img src="{{ asset('assets/img/logo.png') }}" alt=""></a> --}}

        <nav id="navbar" class="navbar">
            <ul>
                {{-- <li class="nav-item d-flex">
                    <div class="collapse fade" id="searchForm">
                        <input id="collapseExample" type="search" class="form-control border-0"
                            placeholder="search" />
                    </div>
                    <a class="nav-link ml-auto" data-toggle="collapse" href="#collapseExample" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <img src="{{ asset('assets/img/search.svg') }}" class="" alt="">&nbsp; Search
                    </a>
                </li> --}}
                <li><a class="nav-link scrollto active" href="{{ route('index') }}">Home</a></li>
                <li class="dropdown"><a href="javascript:"><span>How it works</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="{{ route('how-it-works') }}">How DoleUpp works</a></li>
                        <li><a href="{{ route('subscription') }}">Pricing</a></li>
                        {{--<li><a href="contact">Contact Us</a></li>--}}
                    </ul>
                </li>
                <li><a class="nav-link scrollto" href="{{ route('news') }}">DoleUpp News</a></li>
                {{--<li><a class="nav-link scrollto" href="{{ route('fundraisers') }}">DoleUpp Reels</a></li>--}}
                <li class="dropdown"><a href="javascript:"><span>Users DoleUpp Request</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        @foreach(App\Models\Category::limit(4)->get() as $category)
                            <li>
                                <a href="{{ route('fundraisers.show', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('fundraisers') }}">View All</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="javascript:"><span>Resources</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="{{ route('doleupp-tips') }}">DoleUpp tips</a></li>
                        <li><a href="{{ route('help-center') }}">Help center</a></li>
                        <li><a href="{{ route('community') }}">DoleUpp Community</a></li>
                        <li><a href="{{ route('faq') }}">FAQ's</a></li>
                    </ul>
                </li>
                <li>
                    <a class="nav-link scrollto sign-in" style="background-color: #FFFFFF;color: #99C81F;padding: 0px 10px 0px 0px;" href="{{ route('corporate.categories') }}"><img src="{{ asset('assets/img/corporate.png') }}"/>{{ __('Corporate DoleUpp') }}</a>
                    {{--<a class="nav-link scrollto sign-in d-md-none d-lg-block" style="background-color: #FFFFFF;color: #99C81F;padding: 0px 10px 0px 0px;" href="{{ route('corporate.categories') }}"><img src="{{ asset('assets/img/corporate.png') }}"/>{{ __('Corporate DoleUpp') }}</a>
                    <a class="nav-link scrollto d-xl-none d-sm-block d-md-block d-lg-none" href="{{ route('corporate.categories') }}"><img width="150px;" src="{{ asset('assets/img/corporate-full.png') }}"/></a>--}}
                </li>
                @if(auth()->user())
                    {{--<button onclick="openSearch()" style="margin-top: -3px;"><i class="fa fa-search"></i> Search</button>--}}
                    <li class="dropdown"><a href="javascript:"><i class="fa fa-search" style="margin-right: 5px !important;"></i> Search <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="javascript:" onclick="openSearch()">Search Users</a></li>
                            <li><a href="javascript:" onclick="openSearch2()">Search Categories</a></li>
                        </ul>
                    </li>
                @else
                <li>
                    <a href="{{ route('login') }}"><i class="fa fa-search" style="margin-right: 5px !important;"></i> Search</a>
                </li>
                @endif
                @guest
                    @if (Route::has('login'))
                        <li><a class="nav-link scrollto sign-in" href="{{ route('login') }}">{{ __('Sign in') }}</a></li>
                    @endif

                    @if (Route::has('register'))
                    @endif
                @else
                    @if(auth()->user()->screen == 1)
                        <li>
                            <a  class="nav-link scrollto sign-in" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Sign Out') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        {{--@if (Route::has('login'))
                            <li><a class="nav-link scrollto sign-in" href="{{ route('login') }}">{{ __('Sign in') }}</a></li>
                        @endif--}}
                    @else
                    <li class="dropdown" style="min-width: 180px;"><a href="javascript:" class="sign-in"><span>{{ Auth::user()->name ?? '' }}</span><i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="{{ route('home') }}">My Account</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    {{ __('Sign Out') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endguest
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->
