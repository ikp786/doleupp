@extends('admin.layouts.main')
@section('breadcrumb')
    Pages
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4> View Details</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>Name</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->name }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p>Company Name</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->company_name }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p>Phone</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->phone }}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <p>Email</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->email }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Reason</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->reasons->name }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Message</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->message }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Reply</p>
                                    <div class="input-group mb-4">
                                        {{ $contact_reply->reply }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
@endsection
