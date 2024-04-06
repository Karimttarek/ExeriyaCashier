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
    <!-- Styles -->
    <script src="{{URL::asset('plugins/jquery.js')}}"></script>
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
    <!-- Bootstrap -->
    @livewireStyles
    {{-- <wireui:scripts /> --}}
    @stack('styles')
</head>

<body class="font-sans antialiased">
    @include('components.sessions')
    <div
        x-data="mainState"
        {{-- :class="{ dark: isDarkMode }" --}}
        x-on:resize.window="handleWindowResize"
        x-cloak
    >
        <div class="min-h-screen text-gray-900 bg-gray-100 dark:bg-dark-eval-0 dark:text-gray-200">
            <!-- Sidebar -->
            {{-- <x-sidebar.sidebar /> --}}

            <!-- Page Wrapper -->
            <div
                class="min-h-screen"
                :class="{
                    // 'lg:ml-64': isSidebarOpen,
                    // 'md:ml-16': !isSidebarOpen
                }"
                style="transition-property: margin; transition-duration: 150ms;"
            >

                <!-- Navbar -->
                <x-navbar />

                <!-- Page Heading -->
                {{-- @if (isset($header))
                <header>
                    <div class="p-4 sm:p-6 @if(LaravelLocalization::getCurrentLocale() == 'en') lg:ml-64 md:ml-24 @else lg:mr-64 md:mr-24 @endif">
                        {{ $header }}
                    </div>
                </header>
                @endif --}}

                <!-- Page Content -->
                {{-- @if(LaravelLocalization::getCurrentLocale() == 'en') lg:ml-64 md:ml-24 @else lg:mr-64 md:mr-24 @endif --}}
                <main class="p-4 sm:p-6">
                    {{ $slot }}
                </main>

                <!-- Page Footer -->
                {{-- @if(LaravelLocalization::getCurrentLocale() == 'en') lg:ml-64 md:ml-24 @else lg:mr-64 md:mr-24 @endif --}}
                <main class="flex sticky top-[100vh] p-4 sm:p-6 ">
                    <x-footer />
                </main>

            </div>
        </div>
    </div>
    @include('components.notification')
    @stack('script')
    @livewireScripts
    <!-- WireUI -->
    @wireUiScripts
{{--    <script src="//unpkg.com/alpinejs" defer></script>--}}
        <script src="{{URL::asset('plugins/alphinejs.js')}}" defer></script>
    <script type="module" src="{{URL::asset('js/custom.js')}}"></script>
    <script src="{{URL::asset('js/side.js')}}"></script>
</body>
</html>
