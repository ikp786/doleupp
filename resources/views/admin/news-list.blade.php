@extends('admin.layouts.main')
@section('breadcrumb')
    News
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
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                                        data-target="#addnews">Add News
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mb-4 mt-4">
                        <table id="" class="table table-hover zero-config" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>News Type</th>
                                <th>Image/Video</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($news as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="font_color">{{ $item->category->name }}</td>
                                    <td class="font_color">{{ ucfirst($item->type) }}</td>
                                    <td>
                                        <?php if ($item->type == "image"): ?>
                                        <img src="{{ $item->imgae }}" width="50" height="50">
                                        <?php elseif ($item->type == "video"): ?>
                                        <a href="{{ $item->video }}" class="ply-btn"><img height="50"
                                                                                          src="{{ $item->thumbnail }}"></a>
                                        <?php endif ?>
                                    </td>
                                    <td class="font_color">{{ $item->title }}</td>
                                    <!-- <td class="font_color">{!! substr(strip_tags($item->description), 0, 150) !!}</td> -->
                                    <td class="font_color">{{ Str::limit($item->description, 150) }}</td>
                                    <td>
                                    <!-- <span class="badge outline-badge-primary" data-toggle="modal" data-target="#editnews{{ $item->id }}"> Edit </span> -->
                                        <a href="{{ url('/admin/news-edit', $item->id) }}"><span
                                                class="badge outline-badge-primary"> Edit</span></a>
                                        <a href="{{ url('/admin/news-delete', $item->id) }}"
                                           onclick="return confirm('Are you sure you want to delete this?');"><span
                                                class="badge outline-badge-danger"> Delete </span></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade " id="addnews" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="loginModalLabel">
                            <h4 class="modal-title">Add News</h4>
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
                            <form class="mt-0" action="{{ route('news-add') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <p>News Category</p>
                                    <select class="form-control" name="news_category_id" required>
                                        <option value="">Select News Category</option>
                                        @foreach ($newscategory as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <p>News Type</p>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="">Select News Type</option>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                    </select>
                                </div>
                                <div class="form-group" id="image">
                                    <p>Image</p>
                                    <input type="file" class="form-control" name="imgae" placeholder="Image"
                                           accept="image/jpg,image/jpeg,image/png">
                                </div>
                                <div class="form-group" id="video" style="display: none;">
                                    <p>Video</p>
                                    <input type="file" class="form-control" name="video" placeholder="Video"
                                           accept="video/mp4">
                                </div>
                                <div class="form-group">
                                    <p>Title</p>
                                    <input type="text" class="form-control" name="title" placeholder="Title"
                                           required="">
                                </div>
                                <div class="form-group">
                                    <p>Description</p>
                                    <textarea class="form-control" name="description" placeholder="Description" rows="5"
                                              cols="5" required=""></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-block">Add</button>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <div class="forgot login-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($news as $item)
                <div class="modal fade " id="editnews{{ $item->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="loginModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="loginModalLabel">
                                <h4 class="modal-title">Edit News</h4>
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
                                <form class="mt-0" action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <div class="form-group">
                                        <p>News Category</p>
                                        <select class="form-control" name="news_category_id">
                                            @foreach ($newscategory as $item1)
                                                <option
                                                    value="{{ $item1->id }}" <?php if ($item1->id == $item->news_category_id) {
                                                    echo 'selected';
                                                } ?>>
                                                    {{ $item1->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <p>News Type</p>
                                        <select class="form-control" name="type" id="type">
                                            <option value="image" <?php if ($item->type == 'image') {
                                                echo 'selected';
                                            } ?>>Image
                                            </option>
                                            <option value="video" <?php if ($item->type == 'video') {
                                                echo 'selected';
                                            } ?>>Video
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="image">
                                        <p>Image</p>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" value="{{ $item->imgae }}"
                                                       name="imgae" placeholder="Image">
                                            </div>
                                            <div class="col-md-2">
                                                <img src="{{ $item->imgae }}" width="50" height="50">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="video" style="display:none;">
                                        <p>Video</p>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" value="{{ $item->video }}"
                                                       name="video" placeholder="Video">
                                            </div>
                                            <div class="col-md-2">
                                                <img src="{{ $item->thumbnail }}" width="50" height="50">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <p>Title</p>
                                        <input type="text" class="form-control" name="title" value="{{ $item->title }}"
                                               placeholder="Title" required="">
                                    </div>
                                    <div class="form-group">
                                        <p>Description</p>
                                        <textarea class="form-control" name="description" placeholder="Description"
                                                  rows="5" cols="5" required=""
                                                  value="{{ $item->description }}">{{ $item->description }}</textarea>
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
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#type").change(function () {
                var type = $("#type").val();
                if (type == "image") {
                    $("#image").show();
                    $("#video").hide();
                } else {
                    $("#video").show();
                    $("#image").hide();
                }
            })
        })
    </script>
@endsection
