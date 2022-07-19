@extends('admin.layouts.main')
@section('breadcrumb')
    Users / User Edit
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="basic" class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>User Edit</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('user-update', $users->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group mb-4">
                                        <img src="{{ $users->image }}" height="50">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Profile Picture</p>
                                    <div class="input-group mb-4">
                                        <input type="file" class="form-control" placeholder="Profile"
                                            value="{{ $users->image }}" name="image" aria-label="Profile">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Username</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Username"
                                            value="{{ $users->username }}" name="username" aria-label="Username"
                                            disabled="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Name</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Name"
                                            value="{{ $users->name }}" name="name" aria-label="Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Email</p>
                                    <div class="input-group mb-4">
                                        <input type="email" class="form-control" placeholder="Email"
                                            value="{{ $users->email }}" name="email" aria-label="Email" disabled="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Phone</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Phone"
                                            value="{{ $users->phone }}" name="phone" aria-label="Phone" disabled="">
                                    </div>
                                </div>
                                @if($users->id > 2)
                                <div class="col-md-6">
                                    <p>Date of Birth</p>
                                    <div class="input-group mb-4">
                                        <input type="date" class="form-control" placeholder="Date of Birth"
                                            value="{{ $users->dob }}" name="dob" aria-label="Date of Birth">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>University</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="University"
                                            value="{{ $users->university }}" name="university" aria-label="University">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Occupation</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Occupation"
                                            value="{{ $users->occupation }}" name="occupation" aria-label="Occupation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>State</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="State"
                                            value="{{ $users->state }}" name="state" aria-label="State">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Country</p>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" placeholder="Country"
                                            value="{{ $users->country }}" name="country" aria-label="Country">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Status</p>
                                    <div class="input-group mb-4">
                                        <select class="form-control" name="status">
                                            <option value="1" @if ($users->status == 1) selected @endif>Active</option>
                                            <option value="0" @if ($users->status == 0) selected @endif>Block</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>User Role</p>
                                    <div class="input-group mb-4">
                                        <select class="form-control" name="role">
                                            <option value="both" @if ($users->role == 'both') selected @endif>Interested in being a donor and a
                                                recipient</option>
                                            <option value="donor" @if ($users->role == 'donor') selected @endif>I’m just here to donate</option>
                                            <option value="unsure" @if ($users->role == 'unsure') selected @endif>I’m unsure</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>About</p>
                                    <div class="input-group mb-4">
                                        <textarea class="form-control" rows="5" cols="5" value="{{ $users->about }}"
                                            name="about">{{ $users->about }}</textarea>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary ">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
