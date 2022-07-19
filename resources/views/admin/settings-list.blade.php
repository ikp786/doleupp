@extends('admin.layouts.main')
@section('breadcrumb')
    General Settings
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>General Settings</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('settings-amount-edit') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $setting->id }}" name="id">
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Admin Commission</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Admin Commission"
                                            value="{{ $setting->admin_commission }}" name="admin_commission"
                                            aria-label="Admin Commission" required="">
                                    </div>
                                    <p>Cash Out Fee</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Cash Out Fee"
                                            value="{{ $setting->cash_out_fee }}" name="cash_out_fee"
                                            aria-label="Cash Out Fee" required="">
                                    </div>
                                    <p>Cash Out Day</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Cash Out Day"
                                            value="{{ $setting->cash_out_day }}" name="cash_out_day"
                                            aria-label="Cash Out Day" required="">
                                    </div>
                                    <p>Cash Out Note</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Cash Out Note"
                                            value="{{ $setting->cash_out_note }}" name="cash_out_note"
                                            aria-label="Cash Out Note" required="">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary ">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
