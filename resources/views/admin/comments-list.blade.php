@extends('admin.layouts.main')
@section('breadcrumb')
    Comments
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table class="table table-hover zero-config" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>DoleUpp</th>
                                    <th>Parent</th>
                                    <th>Tag</th>
                                    <th class="text-center">Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comments as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->comment_user->name ?? '' }}</td>
                                        <td class="font_color">{{ $item->donation_request->caption ?? '' }}</td>
                                        <td class="font_color">{{ $item->replies_user->name ?? '' }}</td>
                                        <td class="font_color" style="width: 12% !important;">{{ $item->comment_tag->name ?? '' }}
                                            <?php if($item->tag_id == NULL): ?>
                                            -
                                            <?php endif ?>

                                        </td>
                                        <td class="text-center font_color" style="width: 25% !important;">
                                            <?php if($item->comment_type == "text"): ?>
                                            {{ $item->comment }}
                                            <?php elseif($item->comment_type == "image"): ?>
                                            <img src="{{ $item->comment }}" height="50" width="50">
                                            <?php endif ?>
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
