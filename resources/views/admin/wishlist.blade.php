@extends('admin.layouts.main')
@section('breadcrumb')
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
                                    <th>Category</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>DoleUpp Received</th>
                                    <th>Views</th>
                                    <th>Shares</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wishlist as $item)
                                    <tr>
                                        <td>{{ $item->category->name }}</td>
                                        <td>
                                            <a href="{{ $item->video }}" class="ply-btn"><img height="80"
                                                    src="{{ $item->thumbnail }}"></a>
                                        </td>
                                        <td>{{ $item->caption }}</td>
                                        <td>${{ number_format($item->donation_amount, 2) }}</td>
                                        <td>${{ number_format($item->donation_received, 2) }}</td>
                                        <td>{{ $item->total_views }}</td>
                                        <td>{{ $item->total_share }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            <i class="fa fa-pencil" data-toggle="modal"
                                                data-target="#statusModal{{ $item->id }}"></i>
                                            <a href="{{ url('/admin/donors', $item->id) }}"><i
                                                    class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @foreach ($donations as $item)
                <div class="modal fade login-modal" id="statusModal{{ $item->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="loginModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="loginModalLabel">
                                <h4 class="modal-title">Update Status</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="mt-0" action="{{ route('donation-status-update') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <div class="form-group">
                                        <select class="form-control mb-2" name="status">
                                            <option value="Pending" @if ($item->status == 'Pending') selected @endif>Pending</option>
                                            <option value="Approved" @if ($item->status == 'Approved') selected @endif>Approved</option>
                                            <option value="Rejected" @if ($item->status == 'Rejected') selected @endif>Rejected</option>
                                            <option value="Expired" @if ($item->status == 'Expired') selected @endif>Expired</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-block">Update</button>
                                </form>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <div class="forgot login-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
