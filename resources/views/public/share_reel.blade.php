@php
$shareReel = \Share::page(
    route('reels.show', ['slug' => $id]),
    $caption,
)
->facebook()
->twitter()
->linkedin()
->telegram()
->whatsapp()
->reddit();
@endphp

<div id="social-links2">
    <ul>
        <li>
            <a href="sms:?&body={{ $caption.' '.route('reels.show', ['slug' => $id]) }}"
               class="social-button "><span class="fas fa-sms"></span></a>
        </li>
        <li>
            <a href="mailto:?subject=Share Reel - {{ $caption ?? '' }}&body={{ $caption.' '.route('reels.show', ['slug' => $id]) }}">
                <span class="fas fa-envelope"></span>
            </a>
        </li>
    </ul>
</div>
{!! $shareReel !!}
