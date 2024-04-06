<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <p class=" ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.PRODUCTS')}}</p>
                    </div>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Table -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        {{-- <form method="GET" action="{{route('Product.destroy')}}" accept-charset="UTF-8" id="form"> --}}
            <livewire:datatable.main.client/>
        {{-- </form> --}}
    </div>
    <!-- Table -->
    @push('script')
    @endpush
</x-app-layout>
