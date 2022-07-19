@extends('admin.layouts.main')
@section('breadcrumb')
    Users / User Details
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>User Details</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area animated-underline-content">
                        <ul class="nav nav-tabs  mb-3" id="animateLine" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab"
                                    aria-controls="personal" aria-selected="true">
                                    Personal Details
                                </a>
                            </li>
                            @if($user_details->id > 2)
                            <li class="nav-item">
                                <a class="nav-link" id="bank-detail-tab" data-toggle="tab" href="#bank-detail"
                                    role="tab" aria-controls="bank-detail" aria-selected="true">
                                    Bank Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="card-details-tab" data-toggle="tab" href="#card-details"
                                    role="tab" aria-controls="card-details" aria-selected="false">
                                    Card Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="security-questions-tab" data-toggle="tab"
                                    href="#security-questions" role="tab" aria-controls="security-questions"
                                    aria-selected="false">
                                    Security Questions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="videos-tab" data-toggle="tab" href="#videos" role="tab"
                                    aria-controls="videos" aria-selected="false">
                                    Videos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="donation-from-tab" data-toggle="tab" href="#donation-from"
                                    role="tab" aria-controls="donation-from" aria-selected="false">
                                    DoleUpp From
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="donation-to-tab" data-toggle="tab" href="#donation-to"
                                    role="tab" aria-controls="donation-to" aria-selected="false">
                                    DoleUpp To
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="wishlist-tab" data-toggle="tab" href="#wishlist" role="tab"
                                    aria-controls="wishlist" aria-selected="false">
                                    DoleUpp Cart
                                </a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="animateLineContent-4">
                            <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                aria-labelledby="personal-tab">
                                <div class="widget-content widget-content-area">
                                    <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group mb-4">
                                                    <img src="{{ $user_details->image }}" height="50">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <p>Username</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->username }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Name</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->name }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Email</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->email }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Phone</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->phone }}
                                                </div>
                                            </div>
                                            @if($user_details->id > 2)
                                            <div class="col-md-6">
                                                <p>Date of Birth</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->dob ? Carbon\Carbon::parse($user_details->dob)->format('d M, Y') : '' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>University</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->university }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Occupation</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->occupation }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>About</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->about }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Address</p>
                                                <div class="input-group mb-4">
                                                    {{ $user_details->state }} {{ $user_details->country }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Subscription</p>
                                                <div class="input-group mb-4">
                                                    @if($user_details->subscription_ends_at == NULL)
                                                        Don't Have Subscription
                                                    @else
                                                        @if($user_details->subscription_ends_at < \Carbon\Carbon::now())
                                                            Subscription Expired at {{ auth()->user()->subscription_ends_at }}
                                                        @else
                                                            @php
                                                                $date = \Carbon\Carbon::parse(auth()->user()->subscription_ends_at);
                                                                $now = \Carbon\Carbon::now();
                                                                $diff = $date->diffInDays($now);
                                                            @endphp
                                                            Subscription Expiring in {{ $diff }} Days
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Amount for Donate</p>
                                                <div class="input-group mb-4">
                                                    ${{ $amount_for_donate ?? 0 }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Amount for Redeem</p>
                                                <div class="input-group mb-4">
                                                    ${{ $amount_for_redeem ?? 0 }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($user_details->id > 2)
                            <div class="tab-pane fade " id="bank-detail" role="tabpanel" aria-labelledby="bank-detail-tab">
                                <div class="widget-content widget-content-area">
                                    <ul class="list-group list-group-icons-meta">
                                        @if (!empty($user_details->bank_details))
                                            <li class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <div class="d-flex mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-home">
                                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="tx-inverse">
                                                            {{ $user_details->bank_details->bank_name }}</h6>
                                                        <p class="mg-b-0">Account No:
                                                            {{ $user_details->bank_details->account_number }}</p>
                                                        <p class="mg-b-0">Routing No:
                                                            {{ $user_details->bank_details->routing_number }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <li class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <div class="media-body">
                                                        No Data Available
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="card-details" role="tabpanel"
                                aria-labelledby="card-details-tab">
                                <div class="widget-content widget-content-area">
                                    <ul class="list-group list-group-icons-meta">
                                        @if (!empty($user_details->card_details))
                                            <li class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <div class="d-flex mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-credit-card">
                                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                            <line x1="1" y1="10" x2="23" y2="10"></line>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="tx-inverse">
                                                            {{ $user_details->card_details->card_number }}</h6>
                                                        <p class="mg-b-0">Expiry Date :
                                                            {{ $user_details->card_details->expiry_date }}</p>
                                                        <p class="mg-b-0">CVV :
                                                            {{ substr_replace($user_details->card_details->cvv, '***', 0, 3) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <li class="list-group-item list-group-item-action">
                                                <div class="media">
                                                    <div class="media-body">
                                                        No Data Available
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="security-questions" role="tabpanel"
                                aria-labelledby="security-questions-tab">
                                <div class="table-responsive mb-4 mt-4">
                                    <table id="" class="table table-hover zero-config" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Question</th>
                                                <th>Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($user_details->security_questions))
                                                @foreach ($user_details->security_questions as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td class="font_color">{{ $item->question->question }}</td>
                                                        <td class="font_color">{{ $item->answer }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="videos-tab">
                                <div class="table-responsive mb-4 mt-4">
                                    <table id="" class="table table-hover zero-config" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>Prime</th>
                                                <th>Thumbnail</th>
                                                <th>Title</th>
                                                <th>Amount</th>
                                                <th>DoleUpp Received</th>
                                                <th>Views</th>
                                                <th>Shares</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($donations as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td class="font_color">{{ $item->category->name }}</td>
                                                    <td class="font_color">
                                                        <?php if ($item->is_prime == "Yes"): ?>
                                                        <img src="{{ asset('assets/img/footer-logo.svg') }}" width="30"
                                                            height="30" class="navbar-logo" alt="logo">
                                                        <?php else: ?>
                                                        {{ $item->is_prime }}
                                                        <?php endif ?>
                                                    </td>
                                                    <td class="font_color">
                                                        <a href="{{ $item->video }}" class="ply-btn"><img
                                                                height="80" src="{{ $item->thumbnail }}"></a>
                                                    </td>
                                                    <td class="font_color">{{ $item->caption }}</td>
                                                    <td class="font_color">
                                                        {{ number_format($item->donation_amount, 2) }}</td>
                                                    <td class="font_color">
                                                        {{ number_format($item->donation_received, 2) }}</td>
                                                    <td class="font_color">{{ $item->total_views }}</td>
                                                    <td class="font_color">{{ $item->total_share }}</td>
                                                    <td class="font_color">{{ $item->status }}</td>
                                                    <td>
                                                        <a href="{{ url('/admin/donors', $item->id) }}"><span
                                                                class="badge outline-badge-primary"> View </span></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="donation-from" role="tabpanel"
                                aria-labelledby="donation-from-tab">
                                <div class="table-responsive mb-4 mt-4">
                                    <table id="" class="table table-hover zero-config" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($donation_from) > 0)
                                                @foreach ($donation_from as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td class="font_color">
                                                            {{ @$item->donation_request->category->name }}</td>
                                                        <td class="font_color">{{ $item->donation_by_user->name }}
                                                        </td>
                                                        <td class="font_color">{{ number_format($item->amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="donation-to" role="tabpanel" aria-labelledby="donation-to-tab">
                                <div class="table-responsive mb-4 mt-4">
                                    <table id="" class="table table-hover zero-config" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($donation_to) > 0)
                                                @foreach ($donation_to as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td class="font_color">
                                                            {{ $item->donation_request->category->name }}</td>
                                                        <td class="font_color">{{ $item->donation_to_user->name }}
                                                        </td>
                                                        <td class="font_color">{{ number_format($item->amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                                <div class="table-responsive mb-4 mt-4">
                                    <table id="" class="table table-hover zero-config" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>DoleUpp</th>
                                                <th>Item</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($wishlist[0]))
                                                @foreach ($wishlist as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td class="font_color">{{ $item->donation->category->name }}
                                                        </td>
                                                        <td class="font_color">
                                                            <a href="{{ $item->donation->video }}"
                                                                class="ply-btn"><img height="50"
                                                                    src="{{ $item->donation->thumbnail }}"></a>
                                                        </td>
                                                        <td class="font_color">{{ $item->donation->caption }}</td>
                                                        <td class="font_color">
                                                            {{ number_format($item->donation->donation_amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
