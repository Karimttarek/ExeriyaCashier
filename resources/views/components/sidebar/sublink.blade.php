@props([
    'title' => '',
    'active' => false
])

@php

$classes = 'transition-colors hover:text-gray-500 dark:hover:text-gray-100 dark:text-white';

$active
    ? $classes .= 'bg-blue-500 shadow-lg hover:bg-blue-600 text-gray-500 dark:text-white'
    : $classes .= ' text-gray-500 dark:text-white';

@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    <li class="relative leading-8 m-0 @if(LaravelLocalization::getCurrentLocale() == "en" ) pl-6 @else pr-8 @endif hover:bg-gray-100 dark:hover:bg-gray-600"> <!-- last:before:bg-white last:before:h-auto last:before:top-4 last:before:bottom-0 dark:last:before:bg-dark-eval-1
                                                    before:block before:w-4 before:h-0 before:absolute before:left-0 before:top-4 before:border-t-2
                                                    before:border-t-gray-200 before:-mt-0.5 dark:before:border-t-gray-600 -->
            {{ $title }}
    </li>
</a>
