@extends('layouts.base')

@section('content')

<h3 class="mt-4 text-muted">
    All matching results for
</h3>

<h1 id="search-text" class="mb-4">
    {{ $get }}
</h1>

@if (!$subforums->isEmpty())

    <h3 class="mt-5 mb-4">Subforums</h3>

    @foreach ($subforums as $subforum)

        <a href="{{ route('subforum', $subforum->subforumName) }}"><span class="search-result">{{ $subforum->subforumName }}</span></a>{{ $loop->last ? '' : ' / ' }}

    @endforeach

@else

    <h3 class="mt-5 mb-4">No subforum found</h3>

@endif

@if (!$postsTitles->isEmpty())

    <h3 class="mt-5 mb-4">Posts</h3>

    @foreach ($postsTitles as $postTitle)

        <h5 class="fw-light"><a href="{{ route('post.show', $postTitle->id) }}"><span class="search-result">{{ $postTitle->title }}</span></a></h5>
        <p class="fst-italic">{{ \Illuminate\Support\Str::limit($postTitle->content, $limit = 144, $end = '...') }}</p>

    @endforeach

@else

    <h3 class="mt-5 mb-4">No post found</h3>

@endif



<script>
    const searchResults = document.querySelectorAll('.search-result');
    const searchText = document.querySelector('#search-text').textContent.trim();

    searchResults.forEach(result => {
        const regex = new RegExp(searchText, 'gi');
        const resultText = result.textContent.trim();
        const highlightedText = resultText.replace(regex, match => `<span class="bg-info-subtle">${match}</span>`);
        result.innerHTML = result.innerHTML.replace(resultText, highlightedText);
    });
</script>

@endsection