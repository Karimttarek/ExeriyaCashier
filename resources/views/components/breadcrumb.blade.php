<nav class="flex font-semibold text-xl leading-tight" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
    <li class="inline-flex items-center">
        <a href="{{route('home')}}" class="inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
        {{__('app.HOMEPAGE')}}
        </a>
    </li>
        @if (isset($breadcrumb))
            {{$breadcrumb}}
        @endif
    </ol>
</nav>
