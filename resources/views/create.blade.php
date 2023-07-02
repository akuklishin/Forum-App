@extends('layouts.base')

@section('content')

<div class="container my-4 p-4 rounded-5 shadow">

    <div class="mx-4">

        <h2 class="mt-3 mb-4">
            Create a new post
        </h2>

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

        {{-- form --}}
        <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="my-4 was-validated">
        @csrf

            {{-- subforum --}}
            <div class="form-floating my-3">
                <input type="text" class="form-control" id="subforum-input" placeholder="Subforum" name="subforum" required pattern="^[a-zA-Z0-9]+$">
                <label for="subforum-input">Subforum</label>
            </div>

            {{-- title --}}
            <div class="form-floating my-3">
                <input type="text" class="form-control" id="title-input" placeholder="Title" name="title" required>
                <label for="title-input">Title</label>
            </div>

            {{-- content textarea --}}
            <div class="form-floating my-3">
                <textarea class="form-control" id="content-textarea" placeholder="Content" style="height: 100px" name="content" required></textarea>
                <label for="content-textarea">Content</label>
            </div>

            {{-- image upload --}}
            <div class="my-5">
                <label for="formFile" class="form-label text-muted">Optional featured image</label>
                <input class="form-control" type="file" id="formFile" name="image" accept="image/*">
            </div>

            {{-- submit button --}}
            <div class="mt-5 text-end">
                <input class="btn btn-primary" type="submit" value="Submit">
            </div>

        </form>

    </div>

</div>
@endsection