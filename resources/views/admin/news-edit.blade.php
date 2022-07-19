@extends('admin.layouts.main')
@section('breadcrumb')
    News
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>News Edit</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('news-update', $news->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Title</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Title"
                                            value="{{ $news->title }}" name="title" aria-label="Title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>News Category</p>
                                    <select class="form-control" name="news_category_id">
                                        @foreach ($newscategory as $item1)
                                            <option value="{{ $item1->id }}" @if($item1->id == $news->news_category_id) selected @endif>{{ $item1->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <p>News Type</p>
                                    <select class="form-control" name="type" id="type">
                                        <option value="image" @if($news->type == 'image') selected @endif>Image</option>
                                        <option value="video" @if($news->type == 'video') selected @endif>Video</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="image" <?php if($news->type == "video"): ?>style="display: none;"
                                    <?php endif ?>>
                                    <p>Image</p>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="file" class="form-control" value="{{ $news->imgae }}"
                                                name="imgae" placeholder="Image">
                                        </div>
                                        <div class="col-md-2">
                                            <img src="{{ $news->imgae }}" width="50" height="50">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="video" <?php if($news->type == "image"): ?>style="display: none;"
                                    <?php endif ?>>
                                    <p>Video</p>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="file" class="form-control" value="{{ $news->video }}"
                                                name="video" placeholder="Video">
                                        </div>
                                        <div class="col-md-2">
                                            <img src="{{ $news->thumbnail }}" width="50" height="50">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6" id="input_type" style="display:none;">
                                    <p>Image/Video</p>
                                    <div class="row">
                                        <div class="col-md-10" id="append_input">
                                            <input type="file" class="form-control" value="{{ $news->video }}" name="video" placeholder="Video">
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-12">
                                    <p>Description</p>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control" rows="5" cols="5" value="{{ $news->description }}"
                                            name="description">{{ $news->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary ">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#type").change(function() {
                var type = $("#type").val();
                if (type == "image") {
                    $("#image").show();
                    $("#video").hide();
                    // $("#input_type").show();
                    // $("#append_input").empty();
                    // $("#append_input").append('<input type="file" class="form-control" value="{{ $news->imgae }}" name="imgae" placeholder="Image">');
                } else {
                    $("#video").show();
                    $("#image").hide();
                    // $("#input_type").show();
                    // $("#append_input").empty();
                    // $("#append_input").append('<input type="file" class="form-control" value="{{ $news->video }}" name="video" placeholder="Video">');
                }
            })
        })
    </script>
@endsection
