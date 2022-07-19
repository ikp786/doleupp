@extends('admin.layouts.main')
@section('breadcrumb')
    Contacts
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
                                    <th>Name</th>
                                    <th>Company Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Reason</th>
                                    <th>Message</th>
                                    <th>Reply Status</th>
                                    <th>Reply</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contact as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->name }}</td>
                                        <td class="font_color">{{ $item->company_name }}</td>
                                        <td class="font_color">{{ $item->phone }}</td>
                                        <td class="font_color">{{ $item->email }}</td>
                                        <td class="font_color">{{ $item->reasons->name }}</td>
                                        <td class="font_color">{{ Str::limit($item->message, 100) }}</td>
                                        <td class="font_color">{{ ucfirst($item->reply_status) }}</td>
                                        <td class="font_color">
                                            <?php if ($item->reply_status == "replied"): ?>
                                            {{ Str::limit($item->reply, 100) }}
                                            <?php else: ?>
                                            Not Replied Yet!
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <a href="{{ url('/admin/contacts-reply', $item->id) }}"><span
                                                    class="badge outline-badge-success"> Reply </span></a>
                                            <a href="{{ url('/admin/contacts-reply-view', $item->id) }}"><span
                                                    class="badge outline-badge-primary"> View </span></a>
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
