<div x-show="openTab === 2"
    class="text-body-color p-2 text-base leading-relaxed"
    style="display: none" >
    <div class="relative overflow-x-auto shadow-md sm:rounded-sm">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-stripd">
            <thead class="text-xs text-gray-900 uppercase bg-blue-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3 text-center">
                        {{__('app.CODE')}}
                    </th>
                    <th class="px-6 py-3 text-center">
                        {{__('app.ITEM')}}
                    </th>
                    <th class="px-6 py-3 text-center">
                        {{__('app.QTY')}}
                    </th>
                    <th class="px-6 py-3 text-center">
                        {{__('app.UNITPRICE')}}
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        {{__('app.DISCOUNT')}}
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        {{__('app.NET')}}
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        {{__('app.TAX')}}
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
                @if(isset($invoicedetails))
                    @include('inc.invoiceItemsTBody')
                @endif
                <tr id="MoreRows" class="bg-gray-100 hover:bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-200 cursor-pointer">
                    <td scope="row" colspan="99" class="p-2 text-center">
                        <p>{{__('app.CLICKTOADDPRODUCT')}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Totals -->
    <div class="relative overflow-x-auto sm:rounded-sm mt-5 grid md:grid-cols-2 sm:grid-cols-1 gap-2">
        <!-- Notes -->
        <div>
            <!-- Invoice Discount & Invoice Tax -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Code Type -->
                <div class="mb-2">
                    <label for="code_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.INVOICEDISCOUNT')}}
                    </label>
                    <input type="number" name="invoice_discount" step=".000001" id="invDisc" value="{{$head->invoice_discount}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <!-- Item Code -->
                <div class="mb-2">
                    <label for="invoice_discount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.INVOICETAX')}}
                    </label>
                    <input type="number" name="invoice_tax" step=".000001" id="invTax" value="{{$head->invoice_tax}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
            </div>
            <!-- Notes -->
            <div>
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Additional notes')}}
                </label>
                <textarea name="notes" rows="3" cols="2" class="resize-none	bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $head->notes}}</textarea>
                @error('notes')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <input type="text" name="itemsCount" id="itemsCount" value="{{ $head->items_count}} ITEM" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" style="background: transparent ; border:none" value="0 ITEMS" readonly>
        </div>
        <!-- Values -->
        <div class="flex justify-end">
            @include('inc.invoiceTotalsEdit')
        </div>
    </div>
    <div class="flex justify-between">
        <div>
            <button type="button" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            onclick="document.getElementById('InvoiceTab').click();">
                {{__('app.PREVIOUS')}}
            </button>
        </div>
        <div>
            <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                @if(str_contains(url()->current(), '/edit'))
                    {{__('app.UPDATE')}}
                @else
                    {{__('app.SUBMIT')}}
                @endif
            </button>
        </div>
    </div>
</div>
