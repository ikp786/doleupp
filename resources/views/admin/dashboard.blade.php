@extends('admin.layouts.main')
@section('breadcrumb')
    Dashboard
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="infobox1" class="col-xl-12 col-lg-12 layout-spacing">
                <div class="row">
                    <div class="col-md-3">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('users-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $total_users }}</h5>
                                        <p class="info-text text-center">Total Users</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('users-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-user-check">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="8.5" cy="7" r="4"></circle>
                                                <polyline points="17 11 19 13 23 9"></polyline>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $active_users }}</h5>
                                        <p class="info-text text-center">Active Users</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('users-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-user-x">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="8.5" cy="7" r="4"></circle>
                                                <line x1="18" y1="8" x2="23" y2="13"></line>
                                                <line x1="23" y1="8" x2="18" y2="13"></line>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $block_users }}</h5>
                                        <p class="info-text text-center">Blocked Users</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('donations-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-video">
                                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $total_donations }}</h5>
                                        <p class="info-text text-center">Total DoleUpp Videos</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mt-2">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('donations-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-video">
                                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $prime_donations }}</h5>
                                        <p class="info-text text-center">Prime DoleUpp Videos</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->is_admin == 1)
                    <div class="col-md-3 mt-2">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('contact-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-phone-incoming">
                                                <polyline points="16 2 16 8 22 8"></polyline>
                                                <line x1="23" y1="1" x2="16" y2="8"></line>
                                                <path
                                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $contact }}</h5>
                                        <p class="info-text text-center">Total Enquiry</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <a href="{{ route('news-list') }}">
                                    <div class="infobox-1" style="width: inherit;">
                                        <div class="info-icon text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-trello">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <rect x="7" y="7" width="3" height="9"></rect>
                                                <rect x="14" y="7" width="3" height="5"></rect>
                                            </svg>
                                        </div>
                                        <h5 class="info-heading text-center">{{ $news }}</h5>
                                        <p class="info-text text-center">Total News</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection
