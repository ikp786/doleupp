@extends('admin.layouts.main')
@section('breadcrumb')
    DoleUpp Reports
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="datatable" class="table" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Thumbnail</th>
                                <th style="min-width: 200px !important;">Title</th>
                                <th style="min-width: 300px !important;">Description</th>
                                <th>Report Reason</th>
                                <th>Report Description</th>
                                <th>Reported Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var datatable = $('#datatable').DataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                },
                "stripeClasses": [],
                "select": true,
                "paging": true,
                "pageLength": "10",
                "lengthMenu": [
                    [5, 10, 25, 50, 100, 500, 1000, 5000, -1],
                    [5, 10, 25, 50, 100, 500, 1000, 5000, 'ALL']
                ],
                "processing": true,
                "serverSide": true,
                "searching": true,
                // "responsive": true,
                // "autoWidth": false,
                "ajax": {
                    url: '{{ route('admin.dr-reports') }}',
                },
                "columns": [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'user',
                        name: 'user',
                        defaultContent: ''
                    },
                    {
                        data: 'category',
                        name: 'category',
                        defaultContent: ''
                    },
                    {
                        data: 'video',
                        name: 'video',
                        defaultContent: '',
                    },
                    {
                        data: 'donation_request.caption',
                        name: 'donation_request.caption',
                        defaultContent: ''
                    },
                    {
                        data: 'donation_request.Description',
                        name: 'donation_request.Description',
                        defaultContent: ''
                    },
                    {
                        data: 'reasons.name',
                        name: 'reasons.name',
                        defaultContent: ''
                    },
                    {
                        data: 'reason',
                        name: 'reason',
                        defaultContent: ''
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        defaultContent: ''
                    },
                ]
            });
        });
    </script>
@endsection
