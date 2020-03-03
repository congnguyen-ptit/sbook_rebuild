<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>S*Book</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/png" href="{{ config('view.links.icon_header') }}">

        @include('layout.style')

        {{ Html::script('assets/js/vendor/modernizr-2.8.3.min.js') }}
        @routes()

    </head>
    <body>
        @include('layout.header')
            @section('header')
                @show

        @yield('content')

        @include('layout.footer')
            @section('footer')
                @show

        <div class="modal fade" id="book-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modal-content"></div>
            </div>
        </div>
        <script type='text/javascript'>
            window.translations = {!! $translations !!}
        </script>
        @include('layout.script')
            @section('script')
                @show
    </body>
</html>
