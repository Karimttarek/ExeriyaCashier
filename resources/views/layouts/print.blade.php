<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{URL::asset('favicons/Exeriya-non-bg.png')}}">
    <title>{{ config('app.name', 'Exeriya') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{URL::asset('dist/css/adminlte.min.css')}}">
    <!-- Styles -->

    <style>
        [x-cloak] {
            display: none;
        }
        a{
            text-decoration: none !important;
        }
    </style>
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
    <!-- CAIRO -->
    {{-- <link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'> --}}
    <style>
        *{font-family: Cairo; }
        @font-face {
            font-family: "Cairo";
            src: url({{URL::asset('fonts/cairo/Cairo-Regular.ttf')}}) format("truetype");
        }
    </style>
    @endif
    <style>
        body {margin: 20px}
        @page { size: auto;  margin: 8mm; }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/sass/app.scss'])
</head>

<body class="font-sans antialiased bg-white h-100 d-flex flex-column h-100">

        {{ $slot }}
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>
</html>
