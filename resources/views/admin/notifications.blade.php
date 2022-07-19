@extends('admin.layouts.main')

@section('breadcrumb')
    Notifications
@endsection

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title">Notifications List</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-dark notification" data-toggle="modal"
                                data-target="#modal-create">
                            Send Notification
                        </button>
                    </div>
                </div>
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="" class="table" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Notification Type</th>
                                <th>Notification Title</th>
                                <th>Notification</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody
                            @foreach ($notifications as $key => $item)
                                <tr>
                                    <td class="font_color">{{ $item->id }}</td>
                                    <td class="font_color">
                                        {{ $item->user->name ?? '' }}
                                        <br/>{{ $item->user->email ?? '' }}
                                    </td>
                                    <td class="font_color">{{ $item->notification_type ?? '' }}</td>
                                    <td class="font_color">
                                        {{ $item->notification['title'] ?? '' }}
                                    </td>
                                    <td class="font_color">
                                        {{ $item->notification['body'] ?? '' }}
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
                            {{ $notifications->render('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="add_form" action="{{ route('notifications') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id"/>
                    <div class="modal-header">
                        <h4 id="form_title" class="modal-title">Send Notification</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Notification Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" required>
                            <span id="title" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="notification">Notification <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="notification" id="notification" placeholder="Enter Notification">

                            </textarea>
                            <span id="notification_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="submit" id="add_button" class="btn btn-dark">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

