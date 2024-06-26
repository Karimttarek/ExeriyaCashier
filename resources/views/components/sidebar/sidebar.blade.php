<x-sidebar.overlay />

<aside
    class="fixed inset-y-0 z-20 flex flex-col py-4 space-y-6 bg-white shadow-lg dark:bg-dark-eval-1 sm:hidden lg:block md:block"
    :class="{
        'translate-x-0 w-64': isSidebarOpen || isSidebarHovered,
        '-translate-x-full w-64 md:w-16 md:translate-x-0 @if(LaravelLocalization::getCurrentLocale() == 'ar') translate-x-full w-64 md:w-16 md:translate-x-0 @endif': !isSidebarOpen && !isSidebarHovered, //-translate-x-full w-64 md:w-16 md:translate-x-0
    }"
    style="transition-property: width, transform; transition-duration: 150ms;"
    x-on:mouseenter="handleSidebarHover(true)"
    x-on:mouseleave="handleSidebarHover(false)"
>
    <x-sidebar.header />

    <x-sidebar.content />

    {{-- <x-sidebar.footer /> --}}
</aside>
