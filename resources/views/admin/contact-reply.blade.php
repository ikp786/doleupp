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
                                <h4>Reply Page</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('contacts-reply-update', $contact_reply->id) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>Name</p>
                                    <div class="input-group mb-4">
                                        <input type="text" name="email" class="form-control"
                                            value="{{ $contact_reply->name }}" readonly="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p>Company Name</p>
                                    <div class="input-group mb-4">
                                        <input type="text" name="email" class="form-control"
                                            value="{{ $contact_reply->company_name }}" readonly="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p>Phone</p>
                                    <div class="input-group mb-4">
                                        <input type="text" name="email" class="form-control"
                                            value="{{ $contact_reply->phone }}" readonly="">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <p>Email</p>
                                    <div class="input-group mb-4">
                                        <input type="text" name="email" class="form-control"
                                            value="{{ $contact_reply->email }}" readonly="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Reason</p>
                                    <div class="input-group mb-4">
                                        <input type="text" name="reason_id" class="form-control"
                                            value="{{ $contact_reply->reasons->name }}" readonly="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Message</p>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control" rows="3" cols="5"
                                            value="{{ $contact_reply->message }}" name="content"
                                            readonly="">{{ $contact_reply->message }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Reply</p>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control" rows="10" cols="5"
                                            value="{{ $contact_reply->reply }}"
                                            name="reply">{{ $contact_reply->reply }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary ">Reply</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
@endsection
