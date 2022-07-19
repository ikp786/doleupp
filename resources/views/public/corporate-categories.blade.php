@extends('layouts.public')

@section('title', 'Corporate DoleUpp - Categories')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="{{ route('corporate.donation') }}" method="get">
            @csrf
            <div class="container">
                <div class="section-title pb-0">
                    <h4>Please select the DoleUpp categories of your interest.</h4>
                    <p class="w-100"></p>
                </div>
                @error('categories')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="funkyradio">
                    @foreach ($categories as $category)
                        <div class="funkyradio-primary">
                            <input type="checkbox" name="categories[]" id="categories{{ $category->id }}" value="{{ $category->id }}" {{in_array($category->id, old("categories") ?: []) ? "checked": ""}}/>
                            <label for="categories{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                <input type="checkbox" id="select-all">&nbsp Please spend my donations to all the above categories.
                <button type="submit">Continue</button>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')
    <script>
        $('#select-all').on('click',function(event) {
            //alert('1');
            if(this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>
@endsection
