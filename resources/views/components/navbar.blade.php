<nav
    aria-label="secondary"
    x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between px-4 py-4 sm:px-6 transition-transform duration-500 bg-white dark:bg-dark-eval-1"
    :class="{
        // '-translate-y-full': scrollingDown,
        // 'translate-y-full': scrollingUp,
    }">

    <div class="flex items-center gap-3">

        <a href="{{ route('home') }}">
            <x-application-logo aria-hidden="true" class="w-10 h-10" />

            <span class="sr-only">home</span>
        </a>
        {{-- <x-button
            type="button"
            class="md:hidden"
            icon-only
            variant="secondary"
            sr-text="Toggle dark mode"
            x-on:click="toggleTheme"
        >
            <x-heroicon-o-moon
                x-show="!isDarkMode"
                aria-hidden="true"
                class="w-6 h-6"
            />

            <x-heroicon-o-sun
                x-show="isDarkMode"
                aria-hidden="true"
                class="w-6 h-6"
            />
        </x-button> --}}
    </div>

    <div class="flex items-center gap-3">
        {{-- <x-button
            type="button"
            class="hidden md:inline-flex"
            icon-only
            variant="secondary"
            sr-text="Toggle dark mode"
            x-on:click="toggleTheme"
        >
            <x-heroicon-o-moon
                x-show="!isDarkMode"
                aria-hidden="true"
                class="w-6 h-6"
            />

            <x-heroicon-o-sun
                x-show="isDarkMode"
                aria-hidden="true"
                class="w-6 h-6"
            />
        </x-button> --}}

        {{-- <ul>
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="inline-flex w-12 hover:underline ">
                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $localeCode  }} <!-- $properties['native'] -->
                </a>
            </li>
            @endforeach
        </ul> --}}

        <x-dropdown align="{{LaravelLocalization::getCurrentLocale() == 'en' ? 'right' : 'left'}}" width="48">
            <x-slot name="trigger">
                <button
                    class="flex items-center p-2 text-sm font-medium text-gray-500 rounded-md transition duration-150 ease-in-out hover:outline-none hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark-eval-1 dark:text-gray-400 dark:hover:text-gray-200">
                    <div>{{\LaravelLocalization::getCurrentLocaleName()}}</div>
                    <div class="ml-1">
                        <svg
                            class="w-4 h-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </button>
            </x-slot>

                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if(\LaravelLocalization::getCurrentLocaleName() == $properties['native'])
                    <p class="block px-4 py-2 text-sm leading-5 text-gray-700 bg-gray-100 cursor-not-allowed focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out dark:focus:text-white dark:focus:bg-dark-eval-3 dark:text-gray-400 dark:hover:text-gray-400 dark:hover:bg-gray-600">
                        {{ $properties['native'] }}
                    </p>
                    @else
                        <x-dropdown-link href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            {{ $properties['native'] }}
                        </x-dropdown-link>
                    @endif

                @endforeach
        </x-dropdown>

        <x-dropdown align="{{LaravelLocalization::getCurrentLocale() == 'en' ? 'right' : 'left'}}">
            <x-slot name="trigger">
                <button
                    class="flex items-center p-2 text-sm font-medium text-gray-500 rounded-md transition duration-150 ease-in-out hover:outline-none hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark-eval-1 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    <div>{{ Auth::user()->name }}</div>

                    <div class="ml-1">
                        <svg
                            class="w-4 h-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </button>
            </x-slot>
            @if (auth()->user()->role != App\Enums\Role::CASHIER->value)
                <!-- Profile -->
                <x-dropdown-link
                    :href="route('profile.edit')"
                >
                    {{ __('app.PROFILE') }}
                </x-dropdown-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('app.LOGOUT') }}
                    </x-dropdown-link>
                </form>
        </x-dropdown>
    </div>
</nav>

<!-- Mobile bottom bar -->
<div
    class="fixed z-10 inset-x-0 bottom-0 flex items-center justify-between px-2 py-2 sm:px-2 transition-transform duration-500 bg-white md:hidden dark:bg-dark-eval-1"
    :class="{
        // 'translate-y-full': scrollingDown,
        // 'translate-y-0': scrollingUp,
    }"
>

    <a href="{{ route('home') }}">
        <x-application-logo aria-hidden="true" class="w-10 h-10" />

        <span class="sr-only">home</span>
    </a>

    <x-button
        type="button"
        icon-only
        variant="secondary"
        sr-text="Open main menu"
        x-on:click="isSidebarOpen = !isSidebarOpen"
    >
        <x-heroicon-o-menu
            x-show="!isSidebarOpen"
            aria-hidden="true"
            class="w-6 h-6"
        />

        <x-heroicon-o-x
            x-show="isSidebarOpen"
            aria-hidden="true"
            class="w-6 h-6"
        />
    </x-button>
</div>
