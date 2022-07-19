@extends('admin.layouts.main')
@section('breadcrumb')
    Pages
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Page Edit</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('pages-update', $pages->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Title</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Title"
                                            value="{{ $pages->title }}" name="title" aria-label="Title" disabled="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Content</p>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control" id="demo1" rows="5" cols="5"
                                            value="{{ $pages->content }}" name="content">{{ $pages->content }}</textarea>
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
        // new SimpleMDE({
        //     element: document.getElementById("demo1"),
        //     spellChecker: false,
        // });
    </script>
@endsection
