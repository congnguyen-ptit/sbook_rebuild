<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Fbook</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">

        @include('layout.style')

        {{ Html::script('assets/js/vendor/modernizr-2.8.3.min.js') }}
    </head>
    <body>
        @include('layout.header')
            @section('header')
                @show

        @yield('content')

        @include('layout.footer')
            @section('footer')
                @show

        @include('layout.script')
            @section('script')
                @show
    </body>
</html>
