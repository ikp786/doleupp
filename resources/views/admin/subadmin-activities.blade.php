@extends('admin.layouts.main')

@section('breadcrumb')

    Sub Admin Activities

@endsection

@section('content')

    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

                <div class="widget-content widget-content-area br-6">

                    <div class="table-responsive mb-4 mt-4">

                        <table id="" class="table" style="width:100%">

                            <thead>

                            <tr>

                                <th>#</th>

                                <th>User Details</th>

                                <th>Date</th>

                                <th>Table</th>

                                <th>Activity</th>

                                <th>Action</th>

                            </tr>

                            </thead>

                            <tbody>

                            @foreach ($activities as $key => $item)

                                @if($item->log_type == 'edit')

                                    @php $id = null; @endphp

                                    @foreach(json_decode($item->data) as $d => $data)

                                        @if($d == 'id')

                                            @php $id = $data; @endphp

                                        @endif

                                    @endforeach

                                    @php

                                        $current = \App\Helpers\ApiHelper::dataByTableAndId($item->table_name, $id);

                                    @endphp

                                @endif

                                <tr>

                                    <td class="font_color">{{ $item->id }}</td>

                                    <td class="font_color">{{ $item->user->name ?? '' }}

                                        <br>{{ $item->user->email ?? '' }}</td>

                                    <td class="font_color">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->log_date)->format('m-d-Y H:i A') ?? '' }} ({{ $item->dateHumanize ?? '' }})

                                    </td>

                                    <td class="font_color">{{ $item->table_name ?? '' }}</td>

                                    <td class="font_color">{{ $item->log_type ?? '' }}</td>

                                    <td class="font_color">

                                        <button type="button" class="btn btn-primary" data-toggle="modal"

                                                data-target=".mymodel{{$key}}">View Details

                                        </button>



                                        <div class="modal fade bd-example-modal-xl mymodel{{$key}}" tabindex="-1"

                                             role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

                                            <div class="modal-dialog modal-xl">

                                                <div class="modal-content">

                                                    <div class="modal-header">

                                                        <h4 class="modal-title"

                                                            id="myLargeModalLabel">{{ $item->log_type ?? '' }}</h4>

                                                        <button type="button" class="close" data-dismiss="modal"

                                                                aria-label="Close">

                                                            <span aria-hidden="true">×</span>

                                                        </button>

                                                    </div>

                                                    <div class="modal-body">

                                                        <div class="container-fluid">

                                                            <div class="row">

                                                                <div class="col-md-12" style="word-wrap: break-word;">

                                                                    <table style="width: 100%;">

                                                                        <tr>

                                                                            <th>Field Name</th>

                                                                            @if($item->log_type == 'edit')

                                                                                <th>Old Data</th>

                                                                                <th>Current Data</th>

                                                                            @else

                                                                                <th> Data</th>

                                                                            @endif

                                                                        </tr>

                                                                        @foreach(json_decode($item->data) as $d => $data)

                                                                            @if($d != 'password')

                                                                                <tr>

                                                                                    {{--@if($d == 'created_at' || $d == 'updated_at')

                                                                                        <td>{{ $d ?? '' }}</td>

                                                                                        @if($item->log_type == 'edit')

                                                                                            <td style="word-break: break-all;"><font @if($data != $current->$d) color="red" @endif>{{ $data ?? '' }}</font></td>

                                                                                            <td style="word-break: break-all;">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $current->$d)->format('m/d/Y h:i A') ?? '' }}</td>

                                                                                        @else

                                                                                            <td style="word-break: break-all;">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('m/d/Y h:i A') ?? '' }}</td>

                                                                                        @endif

                                                                                    @else--}}

                                                                                        <td>{{ $d ?? '' }}</td>

                                                                                        @if($item->log_type == 'edit')

                                                                                            <td style="word-break: break-all;"><font @if($data != $current->$d) color="red" @endif>{{ $data ?? '' }}</font></td>

                                                                                            <td style="word-break: break-all;">{{ $current->$d ?? '' }}</td>

                                                                                        @else

                                                                                            <td style="word-break: break-all;">{{ $data ?? '' }}</td>

                                                                                        @endif

                                                                                    {{--@endif--}}

                                                                                </tr>

                                                                            @endif

                                                                        @endforeach

                                                                    </table>

                                                                </div>



                                                            </div>

                                                        </div>



                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        {{--{{ print_r(json_decode($item->data)) ?? '' }}

                                        {{ print_r($item->json_data) ?? '' }}--}}

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>



                    </div>



                    <div class="row">

                        <div class="col-md-5 col-sm-12  layout-spacing"></div>

                        <div class="col-md-7 col-sm-12  layout-spacing">

                            {{ $activities->render('vendor.pagination.custom') }}

                        </div>

                    </div>



                </div>

            </div>

        </div>

    </div>

@endsection

