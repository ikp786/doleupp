@extends('admin.layouts.main')
@section('breadcrumb')
    FAQs
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <!--  @if ($message = Session::get('success'))
             <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                     <strong>{{ $message }}</strong>
             </div>
             @endif -->
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                                    data-target="#addfaq">Add FAQ</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mb-4 mt-4">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faq as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="font_color">{{ $item->question }}</td>
                                        <td class="font_color">{{ $item->answer }}</td>
                                        <td>
                                            <span class="badge outline-badge-primary" data-toggle="modal"
                                                data-target="#editfaq{{ $item->id }}"> Edit </span>
                                            <a href="{{ url('/admin/faqs-delete', $item->id) }}"
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
            <div class="modal fade " id="addfaq" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="loginModalLabel">
                            <h4 class="modal-title">Add FAQ</h4>
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
                            <form class="mt-0" action="{{ route('faqs-add') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="question" placeholder="Question"
                                        required="">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="answer" placeholder="Answer" rows="5" cols="5"
                                        required=""></textarea>
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
            @foreach ($faq as $item)
                <div class="modal fade " id="editfaq{{ $item->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="loginModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="loginModalLabel">
                                <h4 class="modal-title">Edit FAQ</h4>
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
                                <form class="mt-0" action="{{ route('faqs-edit') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $item->question }}"
                                            name="question" placeholder="Question" required="">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="answer" value="{{ $item->answer }}"
                                            placeholder="Answer" rows="5" cols="5"
                                            required="">{{ $item->answer }}</textarea>
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
