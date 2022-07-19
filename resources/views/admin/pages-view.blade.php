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
                                <h4>Page View</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <p>Title</p>
                                    <div class="input-group mb-4">
                                    <input type="text" class="form-control" placeholder="Title" value="{{ $pages->title }}" name="title" aria-label="Title" disabled="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>Content</p>
                                    <div class="input-group mb-4">
                                    <textarea class="form-control" rows="10" cols="5" value="{{ $pages->content }}" name="content">{{ $pages->content }}</textarea>
                                    </div>
                                </div> --}}
                                <div id="mediaObjectAlignment" class="col-lg-12 layout-spacing">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-header">
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                    <h4>{{ $pages->title }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content widget-content-area">
                                            <div class="media">
                                                <!-- <img class="rounded" src="assets/img/profile-4.jpg" alt="pic1"> -->
                                                <div class="media-body">
                                                    <!-- <h4 class="media-heading">{{ $pages->title }}</h4> -->
                                                    <p class="media-text">{!! $pages->content !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
