@extends('layouts.public')

@section('title', 'I Am')

@section('meta')
    <meta content="" name="description">
    <meta content="" name="keywords">
@endsection

@section('style')

@endsection

@section('content')
    @include('public.header')

    <main id="main" class="login-pg">
        <form class="modal-content animate" action="{{ route('i-am') }}" method="post">
            @csrf
            <div class="container">
                <div class="section-title pb-0">
                    <h3>I Am</h3>
                    <p class="w-100">Please Select One</p>
                </div>
                <div class="funkyradio">
                    @foreach($roles as $key=>$role)
                    <div class="funkyradio-default">
                        <input type="radio" name="role" id="radio{{$key}}" value="{{$role->name}}"/>
                        <label for="radio{{$key}}">{{$role->title}}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>
    </main><!-- End #main -->

    {{-- @include('public.footer') --}}
@endsection

@section('script')
    <script>
        $('input[type=radio]').on('change', function() {
            $(this).closest("form").submit();
        });
    </script>
@endsection
