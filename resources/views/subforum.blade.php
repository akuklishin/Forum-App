@extends('layouts.base')

@section('content')

<h3 class="mt-4 text-muted">
    All posts in
</h3>

<h1><small class="text-muted opacity-50 me-2">/sub/</small>{{ $subforumNameDB }} <small class="text-muted fw-lighter">[{{ $scoreSum }}]</small></h1>

<div class="mt-5 mb-4">

    {{-- post sorting --}}
    <small class="text-muted">Sort by

        <span class="px-2"><i class="bi bi-fire"></i> <a href="{{ route('subforum.top', $subforumNameDB) }}">Top</a></span> /

        <span class="px-2"><i class="bi bi-clock"></i> <a href="{{ route('subforum.new', $subforumNameDB) }}">New</a></span>

    </small>

</div>

<div class="container">

    @foreach ($posts as $post)

        @include('partials.post-card')

    @endforeach

</div>

<div class="mx-auto pb-10 w-4/5">
    {{ $posts->links('pagination::bootstrap-5') }}
</div>

@endsection