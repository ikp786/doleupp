@extends('admin.layouts.main')
@section('breadcrumb')
    DoleUpp / Cashout
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
                                    <th>Batch ID</th>
                                    <th>Cashout Amount</th>
                                    <th>Your Commission</th>
                                    <th>Cashout Fees</th>
                                    <th>Total</th>
                                    <th>Cashout Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cashouts as $key => $item)
                                    <tr>
                                        <td class="font_color">{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->donation_request->caption }}</td>
                                        <td class="font_color">{{ $item->batch_id }}</td>
                                        <td class="font_color">${{ number_format($item->redeemed_amount, 2) }}</td>
                                        <td class="font_color">${{ number_format($item->cash_out_commission, 2) }}</td>
                                        <td class="font_color">${{ number_format($item->fee_amount, 2) }}</td>
                                        <td class="font_color">${{ number_format($item->cash_out_commission+$item->fee_amount+$item->redeemed_amount, 2) }}</td>
                                        <td class="font_color">{{ Carbon\Carbon::parse($item->created_at)->format('m/d/Y H:i A') }}</td>
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
