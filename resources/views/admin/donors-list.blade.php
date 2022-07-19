@extends('admin.layouts.main')
@section('breadcrumb')
    DoleUpp / Donors
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="" class="table table-hover zero-config" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DoleUpp</th>
                                    <th>DoleUpp By</th>
                                    <th>DoleUpp To</th>
                                    <th>Amount</th>
                                    <th>Your Commission</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donors as $key => $item)
                                    <tr>
                                        <td class="font_color">{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->donation_request->caption }}</td>
                                        <td class="font_color">{{ $item->donation_by_user->name }}</td>
                                        <td class="font_color">{{ $item->donation_to_user->name }}</td>
                                        <td class="font_color">${{ number_format($item->amount, 2) }}</td>
                                        <td class="font_color">${{ number_format($item->admin_commission, 2) }}</td>
                                        <td class="font_color">${{ number_format($item->amount+$item->admin_commission, 2) }}</td>
                                        <td class="font_color">{{ $item->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
