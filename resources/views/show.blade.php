@extends('layouts.base')

@section('content')

<div class="container my-4 p-4 rounded-5 shadow">

    <div class="mx-4">

            <div class="row mt-3">

                {{-- image column --}}
                @if(!isset($post->imagePath) || empty($post->imagePath))

                    <div class="col-md-10">

                @else

                    <div class="col-md-3">
                        <img class="img-fluid rounded-4" src="{{ asset('/img/' . $post->imagePath) }}" alt="image thumbnail" />
                    </div>

                    <div class="col-md-7">

                @endif

                        <h2>
                            <span>{{ $post->title }}</span>
                            <small class="text-muted fs-6 fw-light">[<span class="score-text">{{ $post->scoreCount }}</span>]</small>

                            {{-- delete (admin and owner only) --}}
                            @if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->id === $post->userId))

                            <form class="d-inline" action="{{ route('post.delete', $post->postId) }}" method="POST">
                            @csrf
                            @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger p-1" type="submit" style="border:none;">
                                    <i class="bi bi-x-circle"></i>
                                </button>

                            </form>

                            @endif

                        </h2>
                        <p>
                            <small class="text-muted">posted in
                                <a href="/sub/{{ $post->subforumName }}">{{ $post->subforumName }}</a> by
                                <a href="/user/{{ $post->userName }}">{{ $post->userName }}</a><br>
                                on {{ date('F j, Y @ g:i a', strtotime($post->creationTime)) }}
                            </small>
                        </p>
                        <p>
                            {!! $post->content !!}
                        </p>
                </div>

                @if (Auth::user())

                @php
                $loggedUserId = Auth::user()->id;
                $existingVote = DB::table('votes')->where('postId', $post->postId)->where('userId', $loggedUserId)->first();
                $dataLike = false;
                if($existingVote){
                    $dataLike = true;
                }
                @endphp

                {{-- like --}}
                <div class="col-md-2 text-end">
                    <form class="d-inline" action="{{ route("post.votepost", $post->postId) }}" method="POST">
                        @csrf
                        <button id="like-button" data-post-id="{{ $post->postId }}" data-like="{{ $dataLike ? 'true' : 'false' }}" class="btn btn-link">
                            <i class="bi bi-arrow-up-circle fs-1"></i>
                        </button>
                    </form>
                </div>
                @endif

            </div>

            @if (Auth::user())

                {{-- errors added --}}
                @if ($errors->any())

                    <div class="mb-4 text-danger">

                        <p>Something went wrong...</p>

                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                            @endforeach
                        </ul>

                    </div>

                @endif

                {{-- comment textarea --}}
                <form class="my-5" action="{{ route('comment.create', $post->postId) }}" method="POST">
                    @csrf

                    <div class="form-floating my-4">
                        <textarea class="form-control" id="comment-textarea" placeholder="Write a comment" style="height: 100px" name="commentContent" required></textarea>
                        <label for="comment-textarea">Write a comment</label>
                    </div>

                    <div class="text-end">
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </div>
                </form>

                {{-- flash message --}}
                @if (session()->has('success'))
                    <div class="p-3 text-success bg-success-subtle border border-success-subtle rounded-4">
                        {{ session()->get('success') }}
                    </div>
                @endif

            @endif

            @if (count($comments) == 0)

            <p class="my-4 text-muted">No comments yet.</p>

            @else

            <h3 class="my-4">{{ count($comments) }} comments</h3>

            @endif

            @foreach ($comments as $comment)

            <div class="row border-bottom align-items-center py-2">

                <span class="text-muted">

                    {{-- meta --}}
                    <span class="fw-light">[<span class="score-text">{{ $comment->scoreCount }}</span>]</span>

                    @if (Auth::user())
                    @php
                    $loggedUserId = Auth::user()->id;
                    $existingVote = DB::table('votes')->where('commentId', $comment->commentId)->where('userId', $loggedUserId)->first();
                    $dataLike = false;
                    if($existingVote){
                    $dataLike = true;
                    }
                    @endphp

                        {{-- like --}}
                        <form class="d-inline" action="{{ route("comment.vote", $comment->commentId) }}" method="POST">
                        @csrf
                            <button id="comment-like-button" class="btn btn-link" type="submit" data-comment-id="{{ $comment->commentId }}" data-like="{{ $dataLike ? 'true' : 'false' }}">
                                <i class="bi bi-arrow-up-circle fs-4"></i>
                            </button>

                        </form>

                    @endif

                    <small>by <strong>{{ $comment->userName }}</strong> on {{ date('F j, Y @ g:i a', strtotime($comment->creationTime)) }}</small>

                    {{-- delete --}}
                    @if (Auth::user() && ($comment->userId === $id || Auth::user()->role === 'admin'))

                        <form class="d-inline" action="{{ route('comment.delete', $comment->commentId) }}" method="POST">
                        @csrf
                        @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger p-1" type="submit" style="border:none;">
                                <i class="bi bi-x-circle"></i>
                            </button>

                        </form>

                    @endif

                </span>

            </div>

            {{-- comment body --}}
            <p class="py-2">
                {!! $comment->content !!}
            </p>

            @endforeach

    </div>

</div>

@endsection