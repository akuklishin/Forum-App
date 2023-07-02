@extends('layouts.base')

@section('content')

<h3 class="mt-4 text-muted">
    All posts by
</h3>

<h1><small class="text-muted opacity-50 me-2">/user/</small>{{ $userName }} <small class="text-muted fw-lighter">[{{ $userRating }}]</small></h1>

<div class="mt-5 mb-4">

    {{-- post sorting --}}
    <small class="text-muted">Sort by

        <span class="px-2"><i class="bi bi-fire"></i> <a href="{{ route('user.top', $userName) }}">Top</a></span> /

        <span class="px-2"><i class="bi bi-clock"></i> <a href="{{ route('user.new', $userName) }}">New</a></span>

    </small>

</div>

<div class="container">

    @foreach ($posts as $post)

    @include('partials.post-card')

    @endforeach

</div>

<div>
    {{ $posts->links('pagination::bootstrap-5') }}
</div>

@endsection