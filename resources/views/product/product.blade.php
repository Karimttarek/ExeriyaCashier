<x-app-layout>
    @push('styles')
        <link href="{{URL::asset('dist/css/filepond.css')}}" rel="stylesheet" />
    @endpush
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
            <livewire:datatable.main.product/>
        {{-- </form> --}}
    </div>
    <!-- Table -->
    @push('script')
        {{-- <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script> --}}
        <script src="{{URL::asset('js/filepond.js')}}"></script>
        <script src="{{URL::asset('js/product_1.0.js')}}"></script>
            <script>
                const inputElement = document.querySelector('input[id="file"]');
                // Create a FilePond instance
                const pond = FilePond.create(inputElement);
                FilePond.setOptions({
                    server:{
                        url: '/product/bulk/upload',
                        headers:{
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                });
            </script>
    @endpush
    @include('modal.ProductBulkUpload')
</x-app-layout>
