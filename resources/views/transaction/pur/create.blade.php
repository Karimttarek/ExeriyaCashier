<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Pur.get')}}" class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.PURINV')}}
                    </a>
                </li>
                <li class="inline-flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.NEWINVOICE')}}</p>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    @if ($errors->any())
        <div class="py-2 px-5 list-none bg-white mb-5 rounded-md">
            @foreach ($errors->all() as $error)
                <li class="text-red-500">{{ $error }}</li>
            @endforeach
        </div>
    @endif
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <form method="POST" action="{{route('Pur.store')}}" enctype="multipart/form-data">
            @csrf
            @include('inc.InvoiceTabs')
        </form>
    </div>
    @include('modal.InvoiceItemsPur')

    @push('script')
    <script src="{{URL::asset('js/invoices.js')}}"></script>

    @endpush

</x-app-layout>
