@extends('admin.layouts.main')
@section('breadcrumb')
    DoleUpp / Corporate DoleUpp
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
                                    <th>Donor</th>
                                    <th>Individual/Company Name</th>
                                    <th>Categories</th>
                                    <th>Donation Amount</th>
                                    <th>Remaining Amount for Donate</th>
                                    {{--<th>Subscription Type</th>--}}
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donations as $key => $item)
                                    <tr>
                                        <td class="font_color">{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->user->name ?? '' }}</td>
                                        <td class="font_color">{{ $item->description ?? '' }}</td>
                                        <td class="font_color">
                                            @php
                                                $categories = \App\Models\Category::whereIn('id', explode(',',$item->categories))->get();
                                            @endphp
                                            @foreach($categories as $c)
                                                <span class="badge badge-primary">{{$c->name ?? ''}}</span>
                                            @endforeach
                                        </td>
                                        <td class="font_color">${{ number_format($item->amount, 2) }}</td>
                                        <td class="font_color">
                                            @if($item->status == 'success')
                                                ${{ number_format($item->amount_for_donate, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        {{--<td class="font-color">{{ $item->subscription_type ?? '' }}</td>--}}
                                        <td class="font_color">
                                            <span class="badge @if($item->status == 'success') badge-success @else badge-danger @endif">{{ $item->status ?? '' }}</span>
                                        </td>
                                        <td>
                                            @if($item->status == 'success')
                                                @if(number_format($item->amount_for_donate, 2) > 0)
                                                <a href="{{ route('cd.donation', ['id' => $item->id]) }}" class="btn btn-success">Donate</a>
                                                @endif
                                                <a href="{{ route('cd.detail', ['id' => $item->id]) }}" class="btn btn-primary">View</a>
                                            @endif
                                        </td>
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
