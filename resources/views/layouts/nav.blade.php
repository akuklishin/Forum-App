<nav class="navbar d-flex px-5 py-2 shadow-sm">

    <div class="me-auto">
        <a class="navbar-brand fs-2 text-primary fw-bold" href="/">FORUM</a>
    </div>

    <div>

        @if (Auth::user())
        <span class="fw-bold"><a href="/user/{{ $loggedUser }}">{{ $loggedUser }}</a></span>
        <span class="fw-light text-muted">[{{ $userRating }}]</span>

        {{-- <div class="ms-5"> --}}
            {{-- <a href="route('logout')">logout</a> --}}
        {{-- </div> --}}

        <form class="d-inline" method="POST" action="{{ route('logout') }}">
            @csrf

            <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('logout') }}
            </x-dropdown-link>
        </form>

        @else
        <a href="/login">login</a>
        <a class="ms-4" href="/register">register</a>
        @endif

    </div>



</nav>