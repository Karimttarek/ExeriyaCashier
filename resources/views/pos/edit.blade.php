<x-pos-layout>

    @if ($errors->any())
        <div class="py-2 px-5 list-none bg-white mb-5 rounded-md">
            @foreach ($errors->all() as $error)
                <li class="text-red-500">{{ $error }}</li>
            @endforeach
        </div>
    @endif

    <div class="py-2 px-5 list-none bg-white mb-5 rounded-md hidden" id="paidLessThanTotal">
        <li class="text-red-500">@if (LaravelLocalization::getCurrentLocale() == 'en') Paid connot be less than total. @else لا يمكن ان يكون المدفوع اقل من الاجمالي. @endif</li>
    </div>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        @foreach ($invoicehead as $head)
            <form method="POST" action="{{route('POS.update',$head->uuid)}}" enctype="multipart/form-data">
                @csrf
                <div class="mb-2 flex justify-between">
                    <div class="grid grid-cols-1 justify-items-start">
                        <div>
                            {{__('app.INTERNALID') .' : '   }}
                            <input type="text" name="invoice_number" value="{{ $head->invoice_number }}" class="font-bold border-none bg-transparent">
                            @error('invoice_number')
                                <div>
                                    <span class="font-medium text-red-600">{{$message}}</span>
                                </div>
                            @enderror
                            <br>
                                {{__('app.Datetime') . ' : ' . (new DateTime(str_contains(url()->current(), '/edit') ? date('Y-m-d h:m:s',strtotime($head->invoice_date)) : "now", new DateTimeZone('Africa/Cairo')))->format('F j, Y, g:i a') }}
                            @error('invoice_date')
                                <div>
                                    <span class="font-medium text-red-600">{{$message}}</span>
                                </div>
                            @enderror
                        </div>
                        <input type="hidden" name="invoice_date" value="{{$head->invoice_date}}">
                    </div>
                    <div class="grid grid-cols-2 justify-items-end">
                        <div>
                            <label for="total" class="block mb-2 text-lg font-bold text-black dark:text-white">{{__('app.TOTAL')}}</label>
                            <input type="text" id="total" name="total" class="text-center block w-full p-4 text-gray-900 border border-gray-300 rounded-lg font-bold bg-gray-50 text-2xl focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly
                            value="{{$head->total}}">
                        </div>
                        <div>
                            <label for="paid" class="block mb-2 text-lg font-bold text-gray-900 dark:text-white">{{__('app.Paid')}}</label>
                            <input type="text" id="paid" name="paid" class="text-center block w-full p-4 text-gray-900 border border-gray-300 rounded-lg font-bold bg-gray-50 text-2xl focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            value="{{$head->total}}" required>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-sm">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-stripd">
                        <thead class="text-xs text-gray-900 uppercase bg-blue-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                {{-- <th scope="col" class="px-6 py-3 w-8 text-center">
                                    {{__('app.CODE')}}
                                </th> --}}
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.ITEM')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.Type')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.QTY')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.UNITPRICE')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.NET')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.DISCOUNT')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    {{__('app.TOTAL')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    *
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tr-item" id="tbody">
                            @isset($invoicedetails)
                                @include('inc.posReceiptItemsTBody')
                            @endisset
                            <tr id="MoreRows" class="bg-gray-100 hover:bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-200 cursor-pointer">
                                <td colspan="99" class="p-2 text-center">
                                    <p>{{__('app.CLICKTOADDPRODUCT')}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @error('items_count')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                @enderror

                <div class="relative overflow-x-auto sm:rounded-sm mt-5 grid md:grid-cols-2 sm:grid-cols-1 gap-2">
                    <!-- Notes -->
                    <div>
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">
                                {{__('app.Additional notes')}}
                            </label>
                            <textarea name="notes" rows="2" cols="2" class="resize-none	bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"> {{ $head->notes }}</textarea>
                            @error('notes')
                            <div>
                                <span class="font-medium text-red-600">{{$message}}</span>
                            </div>
                            @enderror
                        </div>
                        <div class="flex items-center mt-2">
                            <label for="itemsCount" class="ms-2 text-lg font-medium text-gray-900 dark:text-gray-300">
                                {{__('app.ITEMSCOUNT')}}
                            </label>
                            <input id="itemsCount" name="items_count" type="text" value="{{ $head->items_count }}" class="block text-lg font-medium text-gray-900 dark:text-white bg-transparent border-none" >
                        </div>
                    </div>
                    <!-- TOTALS -->
                    <div class="flex justify-end">
                        <div class="list-none">
                            <ul>
                                <li class="grid grid-cols-2">
                                    <p class="font-bold">{{__('app.TOTAL')}}:</p>
                                    <input type="number" name="transTotal" id="transTotal" class="font-bold text-left transTotal border-none bgb-transparent" value="{{ $head->total }}" readonly>
                                </li>
                                <li class="grid grid-cols-2">
                                    <p class="font-bold">{{__('app.Paid')}}:</p>
                                    <input type="number" name="transPaid" id="transPaid" class="font-bold text-left paid border-none bgb-transparent" value="{{ $head->total }}" readonly required>
                                </li>
                                <li class="grid grid-cols-2">
                                    <p class="font-bold">{{__('app.Remaining')}}:</p>
                                    <input type="number" name="remaining" id="remaining" class="font-bold text-left remaining border-none bgb-transparent" value="0.00" readonly>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End TOTALS -->
                </div>

                <div class="flex justify-end">
                    <div>
                        <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            {{__('app.SUBMIT')}}
                        </button>
                    </div>
                </div>
            </form>
        @endforeach
    </div>
    @include('modal.POSItem')

    @push('script')
    <script src="{{URL::asset('js/pos.js')}}"></script>
    @endpush

</x-pos-layout>
