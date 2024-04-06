<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Voucher.get')}}" class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.VOUCHERRECEIPTS')}}
                    </a>
                </li>
                <li class="inline-flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.NEWVOUCHERRECEIPT')}}</p>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <form method="POST" action="{{route('Voucher.store')}}" enctype="multipart/form-data">
            @csrf
            <!-- Receipt Type -->
            <div class="mb-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.RECEIPTTYPE')}}
                    <span class="text-red-500">*</span>
                </label>
                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center pl-3">
                            <input id="cust" type="radio" name="receipt_type" value="1" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 @if(LaravelLocalization::getCurrentLocale() == 'ar') mr-2 @endif">
                            <label for="cust" class="w-full py-3 @if(LaravelLocalization::getCurrentLocale() == 'en') ml-2 @else mr-2 @endif text-sm font-medium text-gray-900 dark:text-gray-300">{{__('app.RECEIPTFROMCUSTOMER')}}</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center pl-3">
                            <input id="supp" type="radio" name="receipt_type" value="9" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 @if(LaravelLocalization::getCurrentLocale() == 'ar') mr-2 @endif">
                            <label for="supp" class="w-full py-3 @if(LaravelLocalization::getCurrentLocale() == 'en') ml-2 @else mr-2 @endif text-sm font-medium text-gray-900 dark:text-gray-300">{{__('app.RECEIPTFROMSUPPLIER')}}</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center pl-3">
                            <input id="other" type="radio" name="receipt_type" value="11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 @if(LaravelLocalization::getCurrentLocale() == 'ar') mr-2 @endif">
                            <label for="other" class="w-full py-3 @if(LaravelLocalization::getCurrentLocale() == 'en') ml-2 @else mr-2 @endif text-sm font-medium text-gray-900 dark:text-gray-300">{{__('app.OTHER')}}</label>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- Receipt Id & Date -->
            <div class="grid gap-6 md:grid-cols-2 mt-5">
                <!-- Id -->
                <div class="mb-2">
                <label for="no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.RECEIPTID')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no" value="{{ $id }}" class="@error('no') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required readonly>
                @error('no')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
                </div>
                <!-- Date -->
                <div class="mb-2">
                    <label for="receipt_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.RECEIPTDATE')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="receipt_date" value="{{ date("Y-m-d\TH:i", strtotime(now())) }}" class="@error('receipt_date') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    @error('receipt_date')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
              <!-- Statement -->
            <div class="mb-2">
                <label for="statement" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.STATEMENT')}}
                    <span class="text-red-500">*</span>
                </label>
                <textarea name="statement" rows="5" class="@error('statement') border border-red-500 @enderror resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('statement') }}</textarea>
                @error('statement')
                 <div>
                     <span class="font-medium text-red-600">{{$message}}</span>
                 </div>
                 @enderror
            </div>
            <!-- Customer ID & Name -->
            <div class="grid grid-flow-row-dense grid-cols-3 gap-6">
                <!-- Customer ID -->
                <div class="mb-2 col-span-1">
                    <label for="customer_uuid" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.CUSTOMERID')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="customer_uuid" value="{{ old('customer_uuid') }}" id="customer_uuid" class="@error('customer_uuid') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                    @error('customer_uuid')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Customer Name -->
                <div class="mb-2 col-span-2">
                    <label for="customer_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.CUSTOMERNAME')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_name" id="customer_name" class="@error('customer_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled></option>
                        @foreach ($customers as $customer)
                            <option value="{{$customer->name}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                    @error('customer_name')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- Receiver -->
            <div class="mb-2">
                <label for="receiver_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.RECEIVERNAME')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" id="receiver" class="@error('receiver_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                disabled>
                @error('receiver_name')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
           <!-- Check Number & Bank name -->
           <div class="grid grid-flow-row-dense grid-cols-3 gap-6">
                <!-- Check Number -->
                <div class="mb-2 col-span-1">
                    <label for="check_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.CHECKNO')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="check_no" value="{{ old('check_no') }}" class="@error('check_no') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                    @error('check_no')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Bank name -->
                <div class="mb-2 col-span-2">
                    <label for="bank_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.BANKNAME')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="@error('bank_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                    @error('bank_name')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- Value -->
           <div class="grid grid-flow-row-dense grid-cols-3 gap-6">
            <!-- Check Number -->
            <div class="mb-2 col-span-1">
                <label for="check_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.VALUE')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" step="0.000001" name="value" value="{{ old('value') }}" id="value" class="@error('value') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
                @error('value')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Bank name -->
            <div class="mb-2 col-span-2">
                <label for="check_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{'.'}}
                </label>
                <input type="text" name="value_text" value="{{ old('value_text') }}" id="value_text" class="@error('value_text') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('value_text')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
        </div>
        <!-- Submit -->
        <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            {{__('app.SUBMIT')}}
        </button>
        </form>
    </div>

    @push('script')
    <script src="{{URL::asset('js/receipts_v1.0.0.js')}}"></script>
    @endpush
</x-app-layout>
