@extends('public.myaccount')

@section('my-title', 'My Corporate DoleUpp')

@section('my-content')
<div class="card-header" role="tab" id="heading-A">
    <h5 class="mb-0">
        <a class="collapsed" data-toggle="collapse" href="#collapse-A"
            aria-expanded="false" aria-controls="collapse-A">
            My Corporate DoleUpp
        </a>
    </h5>
</div>
<div id="collapse-A" class="collapse" data-parent="#content" role="tabpanel"
    aria-labelledby="heading-A">
    <div class="card-body">
        <h4>My Corporate DoleUpp ({{ count($lazor_donations) ?? 0 }})</h4>

        <div class="sign-up-tabs">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @if(count($lazor_donations) > 0)
                    @foreach($lazor_donations as $donation)
                    <a href="{{ route('lazor-donations', ['id' => $donation->id]) }}">
                        <div class="donrs">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ asset('assets/img/footer-logo.svg') }}" width="100" height="100" class="rounded">
                                </div>
                                <div class="col-md-6 text-left">
                                    <h6 class="mb-0">
                                        <ol>
                                            @foreach(App\Models\Category::whereIn('id', explode(', ', $donation->categories))->get() as $category)
                                                <li>{{ $category->name }}</li>
                                            @endforeach
                                        </ol>
                                    </h6>
                                    <span>Payment ID:- {{ $donation->payment_id ?? '' }} <br> {{ date('m/d/Y h:i A', strtotime($donation->created_at)) ?? '' }}</span>
                                    <p>{{ $donation->description ?? '' }}</p>
                                </div>
                                <div class="col-md-3 text-left">
                                    <p class="ttl-prc mt-3">Amount : ${{ number_format($donation->amount ?? 0, 2) }}</p>
                                    <p>Payment Status :
                                        @if($donation->status == 'success')
                                        <span class="text-success">Success</span>
                                        @else
                                        <span class="text-danger">{{ ucwords($donation->status) ?? '' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                    @else
                        <div class="donrs">
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-6 text-left">
                                    <h4 class="mb-0">No Data</h4>
                                </div>
                                <div class="col-md-3 text-left">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('my-script')

@endsection
