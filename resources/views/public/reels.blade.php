@foreach ($reels as $key => $reel)
<?php $remainamount= $reel->donation_amount- $reel->donation_received; ?>
    <div class="col-lg-4 col-md-6 d-flex mt-5 align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
        <div class="icon-box">
            <div class="icon">
                <img src="{{ $reel->thumbnail }}" class="reel-img" alt="">
            </div>
            <a href="{{ $reel->video }}" class="ply-btn"><img src="{{ asset('assets/img/ply-btn.svg') }}" class="" alt=""></a>
            {{-- @if ($reel->is_prime == 'Yes') --}}
                <a href="#" class="prmem-tag"><img src="{{ asset('assets/img/prmem-tag.svg') }}" class="" alt=""></a>
            {{-- @endif --}}
            <a href="javascript:" class="prmem-tag-footer"><img class="{{ ($reel->wishlist_count >= 1) ? 'wishlistRemove' : 'wishlistCreate' }}" data-id="{{ $reel->id }}" src="{{ ($reel->wishlist_count >= 1) ? asset('assets/img/add-holi.svg') : asset('assets/img/add-holi-2.svg') }}" class="" alt=""></a>
            <a href="{{ route('reels.show', ['slug' => $reel->id]) }}" style="color: #FFFFFF !important;">
                <div class="reel-views">
                    <img src="{{ asset('assets/img/eye.svg') }}">&nbsp; {{ $reel->views_count ?? 0 }}
                </div>
                <div class="reel-comments">
                    <img width="15" src="{{ asset('assets/img/comment.svg') }}">&nbsp; {{ $reel->comments_count ?? 0 }}
                </div>
            </a>
            <div class="reel-shares share_reel" data-id="{{$reel->id}}" data-caption="{{$reel->caption}}">
                <img width="14" src="{{ asset('assets/img/share-white.svg') }}">&nbsp; {{ $reel->shares_count ?? 0 }}
            </div>
            <a href="{{ route('reels.show', ['slug' => $reel->id]) }}" style="color: #FFFFFF !important;">
                <div class="rating-icon2">
                    <img src="{{ asset('images/emojis/star-50x50.svg') }}" width="20">
                    {{ number_format($reel->rating_count, 1) }}
                </div>
            </a>
            <a class="donation-now wishlist-create"  data-id="{{$reel->id}}" data-amount="{{$remainamount}}" style="color: #FFFFFF !important;">
                <div class="donate-to-reel">
                    {{-- <img src="{{ asset('assets/img/donate-now.svg') }}" width="50"> --}}
                    DoleUpp Now
                </div>
            </a>
            <div class="reel-text">
                <span>Posted By : <a href="{{ route('donors', ['username' => $reel->user->id]) }}">{{ $reel->user->name ?? '' }}</a></span>
                {{--@if($reel->user->live_status == 'online') <i class="fas fa-circle text-success" style="font-size: 0.5em"></i> @else <i class="fas fa-circle text-danger" style="font-size: 0.5em"></i> @endif--}}
                <h6>
                    Title : {!! \Str::limit($reel->caption ?? '', 55, $end='... <a href="'.route("reels.show", ["slug" => $reel->id]).'">view more</a>') !!}
                    @if(\Str::length($reel->Description) > 0)
                        <br/><small>
                            Description : {!! \Str::limit($reel->Description ?? '', 65, $end='... <a href="'.route("reels.show", ["slug" => $reel->id]).'">view more</a>') !!}
                        </small>
                    @endif
                </h6>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ round(100/$reel->donation_amount*$reel->donation_received) }}%" aria-valuenow="{{ round(100/$reel->donation_amount*$reel->donation_received) }}"
                         aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p><b>${{ $reel->donation_received ?? 0 }} raised</b> of ${{ $reel->donation_amount ?? 0 }}</p>
            </div>
        </div>
    </div>
@endforeach
