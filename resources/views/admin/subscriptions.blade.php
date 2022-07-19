@extends('admin.layouts.main')

@section('breadcrumb')
    Subscriptions
@endsection

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="" class="table" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                            </tr>
                            </thead>
                            <tbody
                            @foreach ($subscriptions as $key => $item)
                                <tr>
                                    <td class="font_color">{{ $item->id }}</td>
                                    <td class="font_color">
                                        {{ $item->user->name ?? '' }}
                                        <br>{{ $item->user->email ?? '' }}
                                    </td>
                                    <td class="font_color">${{ $item->price ?? 0 }}</td>
                                    <td class="font_color">
                                        {{ \Carbon\Carbon::parse($item->starts_from)->format('d M, Y') }} -
                                        {{ \Carbon\Carbon::parse($item->ends_at)->subDay()->format('d M, Y') }}
                                    </td>
                                    <td class="font_color">
                                        @if($item->status == 'Success')
                                            <font color="green">{{ $item->status }}</font>
                                        @else
                                            <font color="red">{{ __('Failed') }}</font>
                                        @endif
                                    </td>
                                    <td class="font_color">
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('m/d/Y h:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-5 col-sm-12  layout-spacing"></div>
                        <div class="col-md-7 col-sm-12  layout-spacing">
                            {{ $subscriptions->render('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

