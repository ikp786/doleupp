@foreach ($news as $key => $n)
{{--{{ $n }}{{ die() }}--}}
<div class="icon-box">
    <div class="row">
        <div class="col-md-4">
            @if($n->type == 'image')
                <a href="{{ $n->imgae }}" class="full-image" style="position: relative; left: 0%; top: 0%;">
                    <img src="{{ $n->imgae }}" class="reel-img" alt="">
                </a>
            @else
            <a href="{{ $n->video }}" class="ply-btn" style="position: relative; left: 0%; top: 0%;">
                <img src="{{ $n->thumbnail }}" class="reel-img" alt="">
            </a>
            @endif
        </div>
        <div class="col-md-8">
            <h4>{{ $n->title ?? '' }}</h4>
            <p>{{ Str::limit($n->description, 550) ?? '' }}
                <a href="{{ route('community.show', ['slug' => $n->slug]) }}">&nbsp; Read More</a>
            </p>
            <div class="cat-p">
                <p class="grn-col">Category: {{ $n->category->name ?? '' }}</p>
                <p>{{ date('M d, Y', strtotime($n->created_at)) }}</p>
            </div>
        </div>
    </div>
</div>
@endforeach
