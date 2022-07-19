@extends('admin.layouts.main')
@section('breadcrumb')
    DoleUpp / DoleUpp Detail
@endsection
@section('style')
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('admin/plugins/select2/select2.min.css') }}">--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-result-repository{
            padding-top:4px;
            padding-bottom:3px
        }
        .select2-result-repository__avatar{
            float:left;
            width:60px;
            margin-right:10px
        }
        .select2-result-repository__avatar img{
            width:100%;
            height:auto;
            border-radius:2px
        }
        .select2-result-repository__meta{
            margin-left:70px
        }
        .select2-result-repository__title{
            color:black;
            font-weight:700;
            word-wrap:break-word;
            line-height:1.1;
            margin-bottom:4px
        }
        .select2-result-repository__forks,.select2-result-repository__stargazers{
            margin-right:1em
        }
        .select2-result-repository__forks,.select2-result-repository__stargazers,.select2-result-repository__watchers{
            display:inline-block;
            color:#000;
            font-size:11px
        }
        .select2-result-repository__description{
            font-size:13px;
            color:#000;
            margin-top:4px
        }
        .select2-results__option--highlighted .select2-result-repository__title{
            color:#000
        }
        .select2-results__option--highlighted .select2-result-repository__forks,.select2-results__option--highlighted .select2-result-repository__stargazers,.select2-results__option--highlighted .select2-result-repository__description,.select2-results__option--highlighted .select2-result-repository__watchers{
            color:#000
        }
    </style>
@endsection
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Corporate DoleUpp</h4>
                            </div>
                        </div>
                    </div>
                    <form class="mt-0" action="{{ route('cd.donation', ['id'=>$lazor_donations->id]) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <input type="hidden" value="">
                                <div class="col-md-6">
                                    <p>Individual/Corporate Name</p>
                                    <div class="input-group mb-4">
                                        <input readonly type="text" class="form-control" value="{{ $lazor_donations->description ?? ''}}"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Amount</p>
                                    <div class="input-group">
                                        <input required class="form-control" name="amount" type="number" min="1" max="{{ $lazor_donations->amount_for_donate ?? 0 }}"/>
                                    </div>
                                    <span class="mb-4">Note:- You have {{ $lazor_donations->amount_for_donate ?? 0 }}$ to donate.</span>
                                </div>
                                {{--<div class="col-md-6">
                                    <p>Donation Categories</p>
                                    <select class="form-control" name="category" id="category">
                                        <option value="">Select category</option>
                                        @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <span>Note:- You have recommended to donate for @foreach($seleted_categories as $sc) {{ $sc->name }}, @endforeach.</span>
                                </div>--}}
                                <div class="col-md-12">
                                    <p>Donation Reels</p>
                                    <select class="js-reels-data form-control" name="reel" required></select>
                                    {{--<select class="form-control" name="reel" id="reel">
                                        <option value="">Select category first</option>
                                    </select>--}}
                                    <span>Note:- You have recommended to donate for @foreach($seleted_categories as $sc) {{ $sc->name }}, @endforeach.</span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">DoleUpp Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{--<script src="{{ asset('admin/plugins/select2/select2.min.js')}}"></script>
    <script src="{{ asset('admin/plugins/select2/custom-select2.js')}}"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(".js-reels-data").select2({
            ajax: {
                url: "{{ route('reelSearch') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search for a category',
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo (repo) {
            if (repo.loading) {
                return repo.text;
            }

            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'><img src='" + repo.thumbnail + "' /></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
                "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
                "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.caption);
            $container.find(".select2-result-repository__description").text('Category : '+repo.category.name+' and Posted By : '+repo.user.name);
            $container.find(".select2-result-repository__forks").append('$'+(repo.donation_amount-repo.donation_received) + " Required Amount");
            $container.find(".select2-result-repository__stargazers").append(repo.rating_count + " Ratings");
            $container.find(".select2-result-repository__watchers").append(repo.views_count + " Views");

            return $container;
        }

        function formatRepoSelection (repo) {
            return repo.caption;
        }
    </script>
@endsection
