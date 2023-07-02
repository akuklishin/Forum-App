<aside class="mx-2">

    {{-- changed so it displays onlu to auth user --}}
    @if (Auth::user())
    <div class="my-5 text-center">
        <a href="{{ route('create') }}"><i class="bi bi-pencil-square me-2"></i> Create a new post</a>
    </div>
    @endif


    <form class="my-5" action="{{ url('/search') }}" method="get">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search..." name="search" pattern=".{3,}" oninvalid="this.setCustomValidity('Search term must be at least 3 characters')" oninput="this.setCustomValidity('')" required>
            <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
        </div>
    </form>


    <div class="mb-3 text-center text-muted">

        <i class="bi bi-trophy me-2"></i> Popular subforums

    </div>

    <ul id="popular-subforums" class="list-group list-group-flush text-start">

        @foreach ($subforums as $subforum)

        <li class="list-group-item">

            <a href="/sub/{{ $subforum->subforumName }}" >{{ $subforum->subforumName }}</a>
            <span class="fw-light text-muted">[{{ $subforum->totalScoreCount >= 1000 ? number_format($fakeScore / 1000, 1) . 'k' : number_format($subforum->totalScoreCount) }}]</span>

        </li>

        @endforeach

    </ul>

</aside>
