<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Expenses.get')}}" class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.EXPENSES')}}
                    </a>
                </li>
                <li class="inline-flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.NEWEXPENSES')}}</p>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <form method="POST" action="{{route('Expenses.store')}}" enctype="multipart/form-data">
            @csrf
            <!-- Code -->
            <div class="grid gap-6 md:grid-cols-2">
                               <!-- Item Code -->
                <div class="mb-2">
                    <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.CODE')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" pattern="[0-9]+" name="code" value="{{ old('code') }}" class="@error('code') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required>
                    @error('code')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- Name -->
            <div class="mb-2">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.NAME')}}
                <span class="text-red-500">*</span>
            </label>
              <input type="text" name="name" value="{{ old('name') }}" class="@error('name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
              @error('name')
               <div>
                   <span class="font-medium text-red-600">{{$message}}</span>
               </div>
               @enderror
            </div>
            <!-- Address -->
            <div class="mb-2">
                <label for="statement" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.STATEMENT')}}
                </label>
                <textarea name="statement" id="statement" rows="3" style="resize: none;" class="@error('statement') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                @error('statement')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{__('app.SUBMIT')}}
            </button>
        </form>
    </div>

    @push('script')
    @endpush
</x-app-layout>
