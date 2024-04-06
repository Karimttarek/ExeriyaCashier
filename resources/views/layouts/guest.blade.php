<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{URL::asset('favicons/Exeriya-non-bg.png')}}">
    <title>{{ config('app.name', 'Exeriya') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
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
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
        <div class="flex flex-col min-h-screen text-gray-900 bg-gray-100 dark:bg-dark-eval-0 dark:text-gray-200">
            {{ $slot }}
            <x-footer />
        </div>
    </div>
</body>
</html>
