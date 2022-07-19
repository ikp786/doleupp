@extends('admin.layouts.main')

@section('breadcrumb')
    Feedback Videos
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
                                <th>User Details</th>
                                <th>Reel</th>
                                <th>Feedback Video</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody
                            @foreach ($feedbacks as $key => $item)
                                <tr>
                                    <td class="font_color">{{ $item->id }}</td>
                                    <td class="font_color">{{ $item->user->name ?? '' }}
                                        <br>{{ $item->user->email ?? '' }}</td>
                                    <td class="font_color">
                                        <a href="{{ $item->donation->video ?? '' }}" class="ply-btn"><img height="80" src="{{ $item->donation->thumbnail ?? '' }}"></a>
                                        <br/>{{ $item->donation->caption ?? '' }}
                                    </td>
                                    <td class="font_color"><a href="{{ $item->video ?? '' }}" class="ply-btn"><img height="80" src="{{ $item->thumbnail ?? '' }}"></a></td>
                                    <td class="font_color">
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('m/d/Y h:i A') ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-5 col-sm-12  layout-spacing"></div>
                        <div class="col-md-7 col-sm-12  layout-spacing">
                            {{ $feedbacks->render('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

