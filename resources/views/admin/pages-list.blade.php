@extends('admin.layouts.main')
@section('breadcrumb')
    Pages
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                {{-- @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                    </div>
                @endif --}}
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table class="table table-hover zero-config" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->title }}</td>
                                        <td class="font_color">{{ Str::limit($item->content, 200) }}</td>
                                        <td>
                                            <a href="{{ url('/admin/pages-edit', $item->id) }}"><span
                                                    class="badge outline-badge-primary"> Edit </span></a>
                                            <a href="{{ url('/admin/pages-view', $item->id) }}"><span
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
