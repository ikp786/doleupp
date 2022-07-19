<ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a id="tab-G" href="#pane-G" class="nav-link active" data-toggle="tab" role="tab">DoleUpp Reels</a>
    </li>
    <li class="nav-item">
        <a id="tab-A" href="#pane-A" class="nav-link" data-toggle="tab" role="tab">My DoleUpp</a>
    </li>
    <li class="nav-item">
        <a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab">DoleUpp Cart</a>
    </li>
    <li class="nav-item">
        <a id="tab-C" href="#pane-C" class="nav-link" data-toggle="tab" role="tab">Cash Out</a>
    </li>
    <li class="nav-item">
        <a id="tab-D" href="#pane-D" class="nav-link" data-toggle="tab" role="tab">Account Settings</a>
    </li>
    <li class="nav-item">
        <a id="tab-E" href="#pane-E" class="nav-link" data-toggle="tab" role="tab">Help Center</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
            {{ __('Sign Out') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>
