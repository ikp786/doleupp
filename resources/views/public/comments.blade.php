@foreach ($reel->comment as $comments)
    <div class="bg-white p-2 mt-4">
        <div class="d-flex flex-row user-info">
            <img class="rounded-circle" src="{{ $comments->user->image ?? 'https://i.imgur.com/RpzrMR2.jpg' }}" width="45" height="45" style="margin: 0;">
            <div class="d-flex flex-column justify-content-start m-2 ml-3">
                <span class="d-block font-weight-bold name">
                    <a href="{{route('donors', ['username' => $comments->user->id])}}">{{ $comments->user->name ?? '' }}</a>
                </span>
                <span class="date text-black-50">Shared publicly - {{ date('m/d/Y h:i A', strtotime($comments->created_at)) }}</span>
            </div>
        </div>
        <div class="mt-2">
            <p class="comment-text mb-0">
                @if ($comments->comment_type == 'image')
                    <img src="{{ $comments->comment ?? '' }}" width="100" alt=""/>
                @else
                    {{ $comments->comment ?? '' }}
                @endif
            </p>
        </div>
        <div class="bg-white">
            <div class="d-flex flex-row fs-12">
                {{-- <div class="like p-2 cursor"><i class="fa fa-thumbs-o-up"></i><span
                        class="ml-1">Like</span></div> --}}
                <div class="like p-2 cursor" data-toggle="collapse" data-target="#comment{{ $comments->id }}" role="button" aria-expanded="false" aria-controls="comment{{ $comments->id }}"><i class="fa fa-commenting-o"></i><span
                        class="ml-1">Reply</span></div>
                {{-- <div class="like p-2 cursor"><i class="fa fa-share"></i><span
                        class="ml-1">Share</span></div> --}}
            </div>
            <div class="comment-box p-3 collapse" id="comment{{ $comments->id }}">
                <form action="{{ route('comments.store') }}" method="POST" class="commentCreate">
                    @csrf
                    <div class="d-flex flex-row align-items-start">
                        <img class="rounded-circle" src="{{ auth()->user()->image ?? 'https://i.imgur.com/RpzrMR2.jpg' }}"
                             width="50" height="50">
                        <input type="hidden" name="donation_request_id" value="{{ $reel->id }}" />
                        <input type="hidden" name="comment_type" value="text" />
                        <input type="hidden" name="parent_id" value="{{ $comments->id }}" />
                        {{-- <input type="hidden" name="tag_id" value="{{ $comments->user_id }}" /> --}}
                        <textarea class="form-control ml-1 shadow-none textarea" name="comment"></textarea>
                    </div>
                    <div class="text-end cmnt-btns">
                        <button class="btn btn-primary btn-sm shadow-none" type="submit">Post
                            Comment</button>
                        <button class="btn btn-outline-primary btn-sm ml-1 shadow-none"
                                type="reset">Cancel</button>
                        <button type="button" class="popup-gif" data-parent_id="{{ $comments->id }}" data-tag_id="" data-bs-toggle="modal" data-bs-target="#gifModal">Gif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($comments->replies as $replies)
        <div class="bg-white p-2 mt-4" style="margin-left: 50px !important;">
            <div class="d-flex flex-row user-info"><img class="rounded-circle"
                                                        src="{{ $replies->user->image ?? 'https://i.imgur.com/RpzrMR2.jpg' }}" width="45" height="45" style="margin: 0;">
                <div class="d-flex flex-column justify-content-start m-2 ml-3"><span
                        class="d-block font-weight-bold name"><a href="{{route('donors', ['username' => $replies->user->id])}}">{{ $replies->user->name ?? '' }}</a>  {!! $replies->tag->name ? '<a href="'.route('donors', ['username' => $replies->tag->id]).'">@'.$replies->tag->name.'</a>' : '' !!}</a></span><span
                        class="date text-black-50">Shared publicly - {{ date('m/d/Y h:i A', strtotime($replies->created_at)) }}</span></div>
            </div>
            <div class="mt-2">
                <p class="comment-text mb-0">
                    @if ($replies->comment_type == 'image')
                        <img src="{{ $replies->comment ?? '' }}" width="100" alt=""/>
                    @else
                        {{ $replies->comment ?? '' }}
                    @endif
                </p>
            </div>
            <div class="bg-white">
                <div class="d-flex flex-row fs-12">
                    {{-- <div class="like p-2 cursor"><i class="fa fa-thumbs-o-up"></i><span
                            class="ml-1">Like</span></div> --}}
                    <div class="like p-2 cursor" data-toggle="collapse" data-target="#comment{{ $replies->id }}" role="button" aria-expanded="false" aria-controls="comment{{ $replies->id }}"><i class="fa fa-commenting-o"></i><span
                            class="ml-1">Reply</span></div>
                    {{-- <div class="like p-2 cursor"><i class="fa fa-share"></i><span
                            class="ml-1">Share</span></div> --}}
                </div>
            </div>
            <div class="comment-box p-3 collapse" id="comment{{ $replies->id }}">
                <form action="{{ route('comments.store') }}" method="POST" class="commentCreate">
                    @csrf
                    <div class="d-flex flex-row align-items-start">
                        <img class="rounded-circle" src="{{ auth()->user()->image ?? 'https://i.imgur.com/RpzrMR2.jpg' }}"
                             width="50" height="50">
                        <input type="hidden" name="donation_request_id" value="{{ $reel->id }}" />
                        <input type="hidden" name="comment_type" value="text" />
                        <input type="hidden" name="parent_id" value="{{ $comments->id }}" />
                        <input type="hidden" name="tag_id" value="{{ $replies->user_id }}" />
                        <textarea class="form-control ml-1 shadow-none textarea" name="comment"></textarea>
                    </div>
                    <div class="text-end cmnt-btns">
                        <button class="btn btn-primary btn-sm shadow-none" type="submit">Post
                            Comment</button>
                        <button class="btn btn-outline-primary btn-sm ml-1 shadow-none"
                                type="reset">Cancel</button>
                        <button type="button" class="popup-gif" data-parent_id="{{ $comments->id }}" data-tag_id="{{ $replies->user_id }}" data-bs-toggle="modal" data-bs-target="#gifModal">Gif</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endforeach

