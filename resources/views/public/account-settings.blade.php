@extends('public.myaccount')

@section('my-title', 'Account Settings')

@section('my-content')
<div class="card-header" role="tab" id="heading-D">
    <h5 class="mb-0">
        <a class="collapsed" data-toggle="collapse" href="#collapse-D"
            aria-expanded="false" aria-controls="collapse-D">
            Account Settings
        </a>
    </h5>
</div>
<div id="collapse-D" class="collapse" role="tabpanel" data-parent="#content"
    aria-labelledby="heading-D">
    <div class="card-body">
        <h4>Account Settings</h4>
        <div class="row mob-cls border rounded">
            <div class="col-md-6">
                <h4 class="mt-3`">Notification</h4>
            </div>
            <div class="col-md-6 text-end">
                <label class="switch mt-3">
                    <input type="checkbox" class="notification" @if(auth()->user()->notification == 'Yes') checked @endif>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
</div>
@endsection

@section('my-script')
<script>
    $('.notification').on('click', function() {
        var notification = $(this).prop('checked') == true ? 'Yes' : 'No';
        $.ajax({
            type: "GET",
            url: '{{ route('notification.status') }}',
            data: {
                'notification': notification
            },
            dataType: "json",
            success: function(data) {
                //console.log(data);
                if (data.success === true) {
                    toastr.success(data.message)
                } else {
                    toastr.error(data.message)
                }
            }
        });
    });
</script>
@endsection
