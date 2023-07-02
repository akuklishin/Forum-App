<div class="row mb-4 rounded-start rounded-5 align-items-center shadow">

    {{-- image column --}}
    @if(!isset($post->imagePath) || empty($post->imagePath))

        <div class="col-md-2 p-0 m-md-0 m-4 animated-gradient-js" style="height:144px;width:144px;"></div>

    @else

        <div class="col-md-2 p-0 m-md-0 m-4" style="height:144px;width:144px;" >
            <img style="display:block; height:100%; width:100%;" src="{{ asset('/img/' . $post->imagePath) }}" alt="image thumbnail" />
        </div>

    @endif


    {{-- title / meta / comments column --}}
    <div class="col-md-8 ms-2">

        <span>

            {{-- title --}}
            <h5 class="d-inline">
                <a href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a>
                <small class="text-muted fs-6 fw-light">[<span class="score-text">{{ $post->scoreCount }}</span>]</small>
            </h5>

            {{-- delete (admin and owner user only) --}}
            @if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->id === $post->userId))

                <form class="d-inline" action="{{ route('post.delete', $post->id) }}" method="POST">
                @csrf
                @method('DELETE')

                    <button class="btn btn-sm btn-outline-danger p-1" type="submit" style="border:none;">
                        <i class="bi bi-x-circle"></i>
                    </button>

                </form>

            @endif

        </span>


        {{-- meta --}}
        <p>
            <small class="text-muted">posted in
                <a href="/sub/{{ $post->subforumName }}">{{ $post->subforumName }}</a> by
                <a href="/user/{{ $post->author }}">{{ $post->author }}</a> on
                {{ date('F j, Y @ g:i a', strtotime($post->creationTime)) }}
            </small>
        </p>

        {{-- comments --}}
        @php

            $commentAmount = DB::table('comments')->select('postId')->where("postId", $post->id)->count();

        @endphp

        <div>
            <small><a href="{{ route('post.show', $post->id) }}">{{ $commentAmount }} comments</a></small>
        </div>

    </div>

    {{-- like column --}}
    @if (Auth::user())

        @php
        $loggedUserId = Auth::user()->id;
        $existingVote = DB::table('votes')->where('postId', $post->id)->where('userId', $loggedUserId)->first();
        $dataLike = false;
        if($existingVote){
            $dataLike = true;
        }
        @endphp


    <div class="col-md-1 p-2">
        <form class="d-inline" action="{{ route("post.votepost", $post->id) }}" method="POST">
        @csrf
            <button id="like-button" data-post-id="{{ $post->id }}" data-like="{{ $dataLike ? 'true' : 'false' }}" class="btn btn-link">
                <i class="bi bi-arrow-up-circle fs-1"></i>
            </button>
        </form>
    </div>

    @endif

</div>