<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')

    <!-- Webpage Title -->
    <title>{{ config('app.name', 'DoleUpp') }} :: @yield('title')</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fonts.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .overlay {
            margin-left: 5%;
            width: 88%;
            text-align: center;
        }
        .overlay .overlay-content {
            /*position: relative;*/
            top: 46%;
            width: 100%;
            text-align: center;
            margin-top: 30px;
            margin: auto;
        }
        .overlay .closebtn {
            position: absolute;
            top: -5px;
            right: 45px;
            font-size: 60px;
            cursor: pointer;
            color: #FFFFFF;
        }
        .overlay .closebtn:hover {
            color: #ccc;
        }
        .overlay select {
            padding: 8px;
            font-size: 17px;
            border: none;
            float: left;
            width: 100%;
            background: #FFFFFF;
        }
        .overlay select:hover {
            background: #FFFFFF;
        }
    </style>
    <style>
        .select2-container {
            width: 100% !important;
            margin-top: 10px;
        }
        .select2-selection.select2-selection--single {
            height: 35px;
        }
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
        select2-selection select2-selection--multiple{
            border: #FFFFFF;
            border-right: 5px;
        }
        /*.select2-container.select2-container--default.select2-container--open{
            margin-top: -25px !important;
        }*/
        .select2-selection__arrow,
        .select2-results__option.select2-results__message{
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <style>
        div#social-links {
            margin: 0 auto;
            margin-left: -30px;
            max-width: 500px;
        }
        div#social-links ul li {
            display: inline-block;
        }
        div#social-links ul li a {
            padding: 10px;
            /*border: 1px solid #ccc;*/
            margin: 1px;
            font-size: 30px;
            /*color: #222;*/
            /*background-color: #ccc;*/
        }
        div#social-links2 {
            margin: 0 auto;
            margin-left: -60px;
            max-width: 500px;
        }
        div#social-links2 ul li {
            display: inline-block;
        }
        div#social-links2 ul li a {
            padding: 10px;
            /*border: 1px solid #ccc;*/
            margin: 1px;
            font-size: 30px;
            /*color: #222;*/
            /*background-color: #ccc;*/
        }

        .don-req-sbt span {
            color: #93C01F;
            font-size: 27px;
            margin: 5px 10px;
            display: block;
        }
        .don-req-sbt div#social-links ul li a {
            padding: 0;
        }

        #main-icon-div,
        #reel_share_html {
            display: -webkit-inline-box;
        }

        .badge-table {
            border-collapse: separate;
            border-spacing: 0 1.5em;
        }
    </style>
    <style>
        .circle-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
    {{--@laravelPWA--}}
    @yield('style')
</head>

<body>
    @yield('content')
    <div class="modal fade" id="donateNowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" {{--style="padding-bottom:24px"--}}>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">DoleUpp Now</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    {{--<a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>--}}
                </div>
                <form action="{{ route('donation.make-payment') }}" method="POST">
                    <div class="modal-body2">
                        <div id="d_donation_html"></div>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input required type="hidden" id="d_request_id" name="donations[0][donation_request_id]" value="" class="form-control">
                        <input required type="number" id="d_donation_amount" name="donations[0][amount]" list="amounts" value="0" class="form-control mt-4" min="1" max="">
                        <datalist id="amounts">
                            <option>50</option>
                            <option>100</option>
                            <option>500</option>
                            <option>1000</option>
                            <option>1200</option>
                            <option>1500</option>
                            <option>2000</option>
                            <option>5000</option>
                            <option>10000</option>
                        </datalist>
                    </div>
                    <div class="modal-footer2">
                        <button type="submit" class="btn btn-primary">DoleUpp Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade don-req-sbt" id="shareModal2" tabindex="-1" aria-labelledby="shareModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-0">
                    <h3 id="reel_share_caption"></h3>
                    <h3>Share reel by</h3>
                    <input type="hidden" id="share_reel_id">
                    <div id="main-icon-div">
                        <div id="reel_share_html">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="preloader"></div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(".js-reels-data").select2({
            ajax: {
                url: "{{ route('userSearch') }}",//"https://api.github.com/search/repositories",
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
            placeholder: 'Search users...',
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        }).on('change', function (e) {
            let username = $(this).val();
            let user_url = "{{ route('donors', ':username') }}";
            user_url = user_url.replace(':username', username);
            $(this).val('');
            window.location.href=user_url;
        });

        function formatRepo (repo) {
            if (repo.loading) {
                return repo.name;
            }
            /*var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'><img src='" + repo.thumbnail + "' /></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
                "<div class='select2-result-repository__stargazers'><i class='fa fa-share'></i> </div>" +
                "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );*/
            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'><img src='" + repo.image + "' /></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                "<div class='select2-result-repository__forks'> </div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").html(repo.name);
            $container.find(".select2-result-repository__description").html(repo.address);
            $container.find(".select2-result-repository__forks").append(repo.about);
            return $container;
        }

        function formatRepoSelection (repo) {
            return repo.caption;
        }

        $(".js-reels-data2").select2({
            ajax: {
                url: "{{ route('categorySearch') }}",//"https://api.github.com/search/repositories",
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
            placeholder: 'Search DoleUpp categories...',
            minimumInputLength: 1,
            templateResult: formatRepo2,
            templateSelection: formatRepoSelection2
        }).on('change', function (e) {
            let reel_id = $(this).val();
            let reels_url = "{{ route('fundraisers.show', ':slug') }}";
            reels_url = reels_url.replace(':slug', reel_id);
            $(this).val('');
            window.location.href=reels_url;
        });

        function formatRepo2 (repo) {
            if (repo.loading) {
                return repo.text;
            }

            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'><img src='" + repo.icon + "' /></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").html(repo.name);
            return $container;
        }

        function formatRepoSelection2 (repo) {
            return repo.name;
        }
    </script>
    <script>
        function openSearch() {
            $('#myOverlay').removeClass('d-none');
            $('#myOverlay2').addClass('d-none');
            $('#myHeader').addClass('d-none');
        }

        function openSearch2() {
            $('#myOverlay2').removeClass('d-none');
            $('#myOverlay').addClass('d-none');
            $('#myHeader').addClass('d-none');
        }

        function closeSearch() {
            $('#myHeader').removeClass('d-none');
            $('#myOverlay').addClass('d-none');
            $('#myOverlay2').addClass('d-none');
        }
    </script>
    <script type="text/javascript">
        $(document).on('click','.full-image',function(e){
            e.preventDefault();
            $(this).magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-img-mobile',
                image: {
                    verticalFit: true
                }
            }).magnificPopup('open');
        });
        $('.ply-video').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
        $(document).on('click','.ply-btn, .ply-video',function(e){
            e.preventDefault();
            $(this).magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            }).magnificPopup('open');
        });
        @if(Session()->has('success'))
            toastr.options = { "progressBar" : true }
            toastr.success('{{ Session('success') }}')
        @endif
        @if(Session()->has('info'))
            toastr.options = { "progressBar" : true }
            toastr.info('{{ Session('info') }}')
        @endif
        @if(Session()->has('error'))
            toastr.options = { "progressBar" : true }
            toastr.error('{{ Session('error') }}')
        @endif
        @if(Session()->has('warning'))
            toastr.options = { "progressBar" : true }
            toastr.warning('{{ Session('warning') }}')
        @endif
        // $('#searchForm').on('shown.bs.collapse', function() {
        //     // focus input on collapse
        //     $("#search").focus()
        // })

        $('.ply-btn').on('click', function() {
            var donation_request_id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: '{{ route('reels.views') }}',
                data: {
                    'donation_request_id': donation_request_id
                },
                dataType: "json",
                success: function(data) {
                    //console.log(data);
                    if (data.success === true) {
                        //toastr.success(data.message)
                    } else {
                        //toastr.error(data.message)
                    }
                }
            });
        });
        $(document).on("click", ".donation-now", function () {
            //$('.donation-now').prop('disabled', true);
            @if(auth()->user())
                const donation_request_id = $(this).data('id');
                const amount = $(this).data('amount');
                $('#d_donation_html').html('Remaining Amount:- '+amount);
                $("#d_request_id").val(donation_request_id);
                $("#d_donation_amount").attr('max',amount);
                $("#donateNowModal").modal("toggle")
            @else
                window.location.href = "{{ route('login') }}";
            @endif
        });

        $(document).on("click", ".share_reel", function () {
            @if(auth()->user())
                const id = $(this).data('id');
                const caption = $(this).data('caption');
                $.ajax({
                    url: "{{ route('reel.share') }}?id=" + id+"&caption="+caption,
                    type: "get",
                    beforeSend: function() {
                        $('.ajax-load').show();
                    }
                })
                .done(function(data){
                    if(data.html == " " || data.html == ""){
                        $('.ajax-load').html("No more records found");
                        return;
                    }
                    $('.ajax-load').hide();
                    //$('#reel_share_caption').html('Caption:- '+caption);
                    $('#share_reel_id').val(id);
                    $('#reel_share_html').html(data.html);
                    $("#shareModal2").modal("toggle")
                })
                .fail(function(jqXHR, ajaxOptions, thrownError){
                    toastr.options = { "progressBar" : true }
                    toastr.warning('server not responding...')
                });
            @else
                window.location.href = "{{ route('login') }}";
            @endif
        });

        $(document).on('click', '#main-icon-div a', function() {
            var donation_request_id = $('#share_reel_id').val();
            $.ajax({
                type: "GET",
                url: '{{ route('reels.shares') }}',
                data: {
                    'donation_request_id': donation_request_id
                },
                dataType: "json",
                success: function(data) {
                    //console.log(data);
                    if (data.success === true) {
                        //toastr.success(data.message)
                    } else {
                        //toastr.error(data.message)
                    }
                }
            });
        });

        $(document).on("click", ".wishlistCreate", function () {
            @if(auth()->user())
            const donation_request_id = $(this).data('id');
            var that = this;
            $.ajax({
                url: "{{ route('wishlist.create') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();
                }
            })
            .done(function(data){
                if(data.success == true) {
                    $(that).attr('src', '{{ asset('assets/img/add-holi.svg') }}');
                    $(that).addClass('wishlistRemove');
                    $(that).removeClass('wishlistCreate');
                    toastr.options = { "progressBar" : true }
                    toastr.success(data.message)
                } else {
                    toastr.options = { "progressBar" : true }
                    toastr.error(data.message)
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                toastr.options = { "progressBar" : true }
                toastr.warning('server not responding...')
            });
            @else
                window.location.href = "{{ route('login') }}";
            @endif
        });
        $(document).on("click", ".wishlistRemove", function () {
            @if(auth()->user())
            const donation_request_id = $(this).data('id');
            var that = this;
            $.ajax({
                url: "{{ route('wishlist.remove') }}?donation_request_id=" + donation_request_id,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();
                }
            })
            .done(function(data){
                if(data.success == true) {
                    $(that).attr('src', '{{ asset('assets/img/add-holi-2.svg') }}');
                    $(that).addClass('wishlistCreate');
                    $(that).removeClass('wishlistRemove');
                    toastr.options = { "progressBar" : true }
                    toastr.success(data.message)
                } else {
                    toastr.options = { "progressBar" : true }
                    toastr.error(data.message)
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                toastr.options = { "progressBar" : true }
                toastr.warning('server not responding...')
            });
            @else
                window.location.href = "{{ route('login') }}";
            @endif
        });
        function showPosition(){
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('make-online')}}",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'latitude' : position.coords.latitude,
                            'longitude' : position.coords.longitude
                        },
                        async: false,
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            setTimeout(showPosition, 30000);
                        }
                    });
                });
            } else {
                console.log("Sorry, your browser does not support HTML5 geolocation.");
            }
        }
        @if(auth()->user())
        showPosition();
        @endif
    </script>
    @yield('script')
</body>

</html>
