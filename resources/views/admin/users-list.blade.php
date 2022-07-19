@extends('admin.layouts.main')
@section('breadcrumb')
    Users
@endsection
@section('style')
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
                                    <th>Profile Picture</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Badge</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <?php if($item->image != NULL): ?>
                                            <img width="80" height="80" src="{{ $item->image }}">
                                            <?php else: ?>
                                            No Image Available
                                            <?php endif ?>
                                        </td>
                                        <td class="font_color">
                                            <?php if($item->username != NULL): ?>
                                            {{ $item->username }}
                                            <?php else: ?>
                                            Not Available
                                            <?php endif ?>
                                        </td>
                                        <td class="font_color">{{ $item->name }}
                                            <?php if($item->name == NULL): ?>
                                            -
                                            <?php endif ?>
                                        </td>
                                        <td class="font_color">{{ $item->email }}
                                            <?php if($item->email == NULL): ?>
                                            -
                                            <?php endif ?>
                                        </td>

                                        <td class="font_color">{{ $item->phone }}
                                            <?php if($item->phone == NULL): ?>
                                            -
                                            <?php endif ?>
                                        </td>
                                        <td style="color: <?php echo $item->color_code; ?>"><b>{{ $item->badge }}</b></td>
                                        <td>
                                            @if($item->id > 2)
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-dark btn-sm">
                                                    <?php if($item->status == 1): ?>
                                                    Active
                                                    <?php else: ?>
                                                    Block
                                                    <?php endif ?>
                                                </button>
                                                <button type="button" title="View Status"
                                                    class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split"
                                                    id="dropdownMenuReference3" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-reference="parent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuReference3">
                                                    <?php if($item->status == 1): ?>
                                                    <a class="dropdown-item"
                                                        href="{{ url('/admin/user-status-update/block', $item->id) }}">Block</a>
                                                    <?php else: ?>
                                                    <a class="dropdown-item"
                                                        href="{{ url('/admin/user-status-update/active', $item->id) }}">Active</a>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ url('/admin/user-edit', $item->id) }}"><span
                                                    class="badge outline-badge-primary"> Edit </span></a>
                                            @if(auth()->user()->is_admin == 1)
                                            <a href="{{ url('/admin/user-delete', $item->id) }}"><span
                                                    class="badge outline-badge-danger"
                                                    onclick="return confirm('Are you sure you want to delete this?');">
                                                    Delete </span></a>
                                            @endif
                                            <a href="{{ url('/admin/user-details', $item->id) }}"><span
                                                    class="badge outline-badge-success"> View </span></a>
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
