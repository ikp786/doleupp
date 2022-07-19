{{--@if(auth()->user()->subscription_ends_at == NULL)
    <script>window.location = "{{ route('subscription') }}";</script>
    {{ exit() }}
@elseif(auth()->user()->subscription_ends_at < \Carbon\Carbon::now())
    <script>window.location = "{{ route('subscription-renew') }}";</script>
    {{ exit() }}
@endif--}}

@extends('layouts.public')

@section('title', 'DoleUpp Request')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')
    <link href="{{ asset('admin/plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="{{ route('donation-request') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="section-title">
                    <h3>DoleUpp Request</h3>
                </div>
                <input type="hidden" value="" name="id"/>
                <div class="custom-file-upload">
                    <!--<label for="file">File: </label>-->
                    <input type="file" id="file" onclick="Swal.fire('Upload the video in portrait mode(selfie position) for receiving the chance of more donations.')" placeholder="Upload Video" name="video" accept="video/mp4"/>
                </div>
                @error('video')
                    <br><span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <select class="form-select" aria-label="Default select example" name="category_id">
                    <option value="" selected disabled>Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <br><span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <textarea placeholder="Caption to Video" rows="2" name="caption">{{ old('caption') }}</textarea>
                @error('caption')
                    <br><span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <textarea placeholder="Description to Video" rows="3" name="description">{{ old('description') }}</textarea>
                @error('description')
                <br><span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input class="mt-0" type="text" placeholder="DoleUpp Amount ( Up to ${{ ($donation_amount > 0) ? $donation_amount : 0 }} )" name="donation_amount" value="{{ old('donation_amount') }}" required>
                @error('donation_amount')
                <br><span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <br>
                <div class="form-check">
                    <input class="form-check-input" name="is_prime" type="checkbox" value="Yes" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">$5 pay for top rated DoleUpp request</label>
                </div>
                @if($donation_amount > 0)
                    <button type="submit">Submit</button>
                @else
                    <p class="mt-4 text-danger text-center">Your DoleUpp request amount limit is over,
                        <br/>Please try after completing for 1 year time period.
                        @if(@$starts_from)
                        <br/>[{{ Carbon\Carbon::parse(@$starts_from)->format('m/d/Y H:i A') }} - {{ @$ends_at ? Carbon\Carbon::parse(@$ends_at)->format('m/d/Y H:i A') : Carbon\Carbon::now()->addYear()->format('m/d/Y H:i A') }}]
                        @endif
                    </p>
                @endif
            </div>

        </form>

    </main><!-- End #main -->

    <div class="modal fade don-req-sbt" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-0">
                    <h3>Your DoleUpp
                        Request is Submitted</h3>
                    <span>and</span>
                    <p>will be posted on app once approved by admin</p>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('public.footer') --}}
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="{{ asset('admin/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
    <script type="text/javascript">
        @if(Session()->has('success') || Session()->has('warning'))
        $(window).on('load', function() {
            $('#exampleModal').modal('show');
        });
        @endif
        (function($) {
            var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined', isIE = /msie/i.test(navigator.userAgent);
            $.fn.customFile = function() {
                return this.each(function() {
                    var $file = $(this).addClass('custom-file-upload-hidden'),
                        $wrap = $('<div class="file-upload-wrapper">'),
                        $input = $('<input type="text" class="file-upload-input" />'),
                        $button = $('<button type="button" class="file-upload-button"></button>'),
                        // Hack for IE
                        $label = $('<label class="file-upload-button" for="' + $file[0].id + '">Select a File</label>');
                    $file.css({
                        position: 'absolute',
                        left: '-9999px'
                    });

                    $wrap.insertAfter($file).append($file, $input, (isIE ? $label : $button));

                    // Prevent focus
                    $file.attr('tabIndex', -1);
                    $button.attr('tabIndex', -1);

                    $button.click(function() {
                        $file.focus().click(); // Open dialog
                    });

                    $input.click(function() {
                        $file.focus().click(); // Open dialog
                    });

                    $file.change(function() {
                        var files = [], fileArr, filename;

                        // If multiple is supported then extract
                        // all filenames from the file array
                        if (multipleSupport) {
                            fileArr = $file[0].files;
                            for (var i = 0, len = fileArr.length; i < len; i++) {
                                files.push(fileArr[i].name);
                            }
                            filename = files.join(', ');

                            // If not supported then just take the value
                            // and remove the path to just show the filename
                        } else {
                            filename = $file.val().split('\\').pop();
                        }

                        $input.val(filename) // Set the value
                            .attr('title', filename) // Show filename in title tootlip
                            .focus(); // Regain focus

                    });

                    $input.on({
                        blur: function() {
                            $file.trigger('blur');
                        },
                        keydown: function(e) {
                            if (e.which === 13) { // Enter
                                if (!isIE) {
                                    $file.trigger('click');
                                }
                            } else if (e.which === 8 || e.which === 46) { // Backspace & Del
                                // On some browsers the value is read-only
                                // with this trick we remove the old input and add
                                // a clean clone with all the original events attached
                                $file.replaceWith($file = $file.clone(true));
                                $file.trigger('change');
                                $input.val('');
                            } else if (e.which === 9) { // TAB
                                return;
                            } else { // All other keys
                                return false;
                            }
                        }
                    });

                });

            };

            // Old browser fallback
            if (!multipleSupport) {
                $(document).on('change', 'input.customfile', function() {
                    var $this = $(this),
                        // Create a unique ID so we
                        // can attach the label to the input
                        uniqId = 'customfile_' + (new Date()).getTime(),
                        $wrap = $this.parent(),

                        // Filter empty input
                        $inputs = $wrap.siblings().find('.file-upload-input')
                        .filter(function() {
                            return !this.value
                        }),

                        $file = $('<input type="file" id="' + uniqId + '" name="' + $this.attr('name') + '"/>');
                    // 1ms timeout so it runs after all other events
                    // that modify the value have triggered
                    setTimeout(function() {
                        // Add a new input
                        if ($this.val()) {
                            // Check for empty fields to prevent
                            // creating new inputs when changing files
                            if (!$inputs.length) {
                                $wrap.after($file);
                                $file.customFile();
                            }
                            // Remove and reorganize inputs
                        } else {
                            $inputs.parent().remove();
                            // Move the input so it's always last on the list
                            $wrap.appendTo($wrap.parent());
                            $wrap.find('input').focus();
                        }
                    }, 1);

                });
            }
        }(jQuery));

        $('input[type=file]').customFile();
    </script>
@endsection
