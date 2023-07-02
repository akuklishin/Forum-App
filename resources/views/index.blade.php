@extends('layouts.base')

@section('content')

{{-- flash message --}}
@if (session()->has('success'))
    <div class="p-3 text-success bg-success-subtle border border-success-subtle rounded-4">
        {{ session()->get('success') }}
    </div>
@endif

<div class="mt-5 mb-4">

    {{-- post sorting --}}
    <small class="text-muted">Sort by

        <span class="px-2"><i class="bi bi-fire"></i> <a href="{{ route('posts.top') }}">Top</a></span> /

        <span class="px-2"><i class="bi bi-clock"></i> <a href="{{ route('posts.new') }}">New</a></span>

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
