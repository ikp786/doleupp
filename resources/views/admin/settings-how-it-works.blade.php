@extends('admin.layouts.main')
@section('breadcrumb')
    How it Works Settings
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>How it Works Settings</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('settings-video-edit') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $setting->id }}" name="setting_id">
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>How it Works Text</label>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control ckeditor" id="ckeditor" name="onboarding_text"
                                                  placeholder="How it Works" >{!! $setting->onboarding_text ?? '' !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>How it Works Video</label>
                                    <div class="input-group mb-4">
                                        <input type="file" class="form-control" name="onboarding_video"
                                            placeholder="Onboarding Video" accept="video/mp4">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <a href="{{ $setting->onboarding_video }}" class="ply-btn"><img height="100"
                                                                                                        src="{{ $setting->thumbnail }}"></a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>How it Works Text</label>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control ckeditor" id="ckeditor2" name="onboarding_text_2"
                                                  placeholder="How it Works" >{!! $setting->onboarding_text_2 ?? '' !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group mb-4">
                                        <img height="100" src="{{ asset('assets/img/how-it-works.png') }}"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>How it Works Text</label>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control ckeditor" id="ckeditor3" name="onboarding_text_3"
                                                  placeholder="How it Works" >{!! $setting->onboarding_text_3 ?? '' !!}</textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#ckeditor'))
        .catch(error => {
            console.error( error );
        });
    ClassicEditor
        .create(document.querySelector('#ckeditor2'))
        .catch(error => {
            console.error( error );
        });
    ClassicEditor
        .create(document.querySelector('#ckeditor3'))
        .catch(error => {
            console.error( error );
        });
</script>
@endsection
