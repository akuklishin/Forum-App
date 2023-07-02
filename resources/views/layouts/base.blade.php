<!DOCTYPE html>
<html lang="en">

{{-- head --}}
@include('layouts.head')

<body>

    {{-- nav --}}
    @include('layouts.nav')

    <div class="container my-4">

        <div class="row">

            <div class="col-md-9">

                {{-- content --}}
                @yield('content')

            </div>

            <div class="col-md-3">

                {{-- aside --}}
                @include('layouts.aside')

            </div>

        </div>

    </div>

    {{-- spacer --}}
    <div class="m-5 p-5">
        <br>
    </div>

    {{-- footer --}}
    @include('layouts.footer')

    {{-- bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- custom js --}}
    <script src="{{ asset('/js/animated-gradient.js') }}"></script>
    <script src="{{ asset('/js/like-button.js') }}"></script>

</body>
</html>