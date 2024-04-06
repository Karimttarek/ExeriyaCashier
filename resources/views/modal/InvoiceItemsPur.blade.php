<div class="relative hidden z-30 h-full" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="InvoiceItems">
    <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex h-screen items-end justify-start p-4 text-center sm:items-center sm:p-0">
        <form class="lg:w-1/3  sm:w-1/2 h-screen">
            <div class="relative transform rounded-sm border border-gray bg-white @if(LaravelLocalization::getCurrentLocale() == 'en') text-left @else text-right @endif shadow-xl transition-all sm:w-full">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 mb-3">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">{{__('app.PRODUCT')}}</h3>
                <div class="mt-2">
                    <!--Form -->
                    <input type="hidden" id="product-uuid">
                    <!-- Item Name -->
                    <div class="mb-2">
                        <label for="tax_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.SEARCHBYITEMNAMEORCODE')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="product-filter-pur" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                    </div>
                    <!-- Parent -->
                    <div class="mb-2">
                        <label for="parent" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.PRODUCTS')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="product-search-pur" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected disabled>{{__('app.SELECTPRODUCT')}}</option>
                            @foreach ($products as $product)
                                    <option value="{{$product->name}}" data-codetype="{{$product->code_type}}" data-name="{{$product->name}}" data-barcode="{{ $product->bar_code }}"
                                        data-itemcode="{{$product->item_code}}" data-description="{{$product->description}}" data-uuid="{{$product->uuid}}"
                                        data-price="{{$product->first_unit_pur_price}}" data-first_unit_type="{{$product->first_unit_type}}"
                                        data-second_unit_type="{{$product->second_unit_type}}" data-third_unit_type="{{$product->third_unit_type}}"
                                        data-first_unit_pur_price="{{ $product->first_unit_pur_price }}" data-second_unit_pur_price="{{ $product->second_unit_pur_price }}"
                                        data-third_unit_pur_price="{{ $product->third_unit_pur_price }}">
                                        {{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Item Code & Code Type -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Code Type -->
                        <div class="mb-2">
                            <label for="code_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.CODETYPE')}}
                            </label>
                            <input type="text" id="product-codeType" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                            readonly>
                        </div>
                        <!-- Item Code -->
                        <div class="mb-2">
                            <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.ITEMCODE')}}
                            </label>
                            <input type="text" id="product-itemCode" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                            readonly>
                        </div>
                    </div>
                    <!-- Item Name -->
                    <div class="mb-2">
                        <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.ITEMNAME')}}
                        </label>
                        <input type="text" id="product-name" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                        readonly>
                    </div>
                    <!-- Item Description -->
                    <div class="mb-2 hidden">
                        <label for="product-description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.DESCRIPTION')}}
                        </label>
                        <input type="text" id="product-description" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                        readonly>
                    </div>
                    <!-- Qty & Unit Type -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Qty -->
                        <div class="mb-2">
                            <label for="product-qty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.QTY')}}
                            </label>
                            <input type="number" id="product-qty" step=".000001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                        </div>
                        <!-- Unit Type -->
                        <div class="mb-2">
                            <label for="product-type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.TYPE')}}
                            </label>
                            <select name="product-type" id="product-type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected></option>
                                @foreach($unit as $u)
                                    <option value="{{$u->code}}">{{$u->desc_en}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- tem Price & Currency -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Item Price -->
                        <div class="mb-2">
                            <label for="product-price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.UNITPRICE')}}
                            </label>
                            <input type="number" id="product-price" step=".000001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                        </div>
                        <!-- Unit Type -->
                        <div class="mb-2">
                            <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.CURRENCY')}}
                            </label>
                            <select name="currency-type" id="currency-type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected></option>
                                @foreach($currency as $c)
                                    <option value="{{$c->code}}">{{$c->Desc_en}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Discount -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Value -->
                        <div class="mb-2">
                            <label for="product-discount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.DISCOUNT')}}
                            </label>
                            <input type="number" id="product-discount" step=".000001" min="0" pattern= "[0-9]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                        </div>
                        <!-- Precentage -->
                        <div class="mb-2">
                            <label for="product-discount-per" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                %
                            </label>
                            <input type="number" id="product-discount-per" step=".000001" min="0" pattern= "[0-9]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                        </div>
                    </div>
                    <!-- Tax Table -->
                    <div class="grid grid-cols-2 gap-2 hidden">
                        <!-- Value -->
                        <div class="mb-2">
                            <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('app.TAXTABLE')}}
                            </label>
                            <input type="number" id="product-tax-table" step=".000001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                        </div>
                        <!-- Precentage -->
                        <div class="mb-2">
                            <label for="table-per" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                %
                            </label>
                            <input type="number" id="product-tax-table-per" step=".000001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                        </div>
                    </div>
                    <!-- Net -->
                    <div class="mb-2">
                        <label for="product-net" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.NET')}}
                        </label>
                        <input type="number" id="product-net" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                        readonly>
                    </div>
                    <!-- Tax -->
                    <div class="mb-2 tax-select hidden">
                        <label for="tax-select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.TAXES')}}
                        </label>
                        <select name="tax" id="tax-select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected disabled>{{__('app.SELECTTAX')}}</option>
                            @foreach ($taxTypes as $tax)
                                <option value="{{$tax->Code}}">{{$tax->Code.' - '.$tax->Desc_ar}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--Discount After Tax -->
                    <div class="mb-2 mt-2 hidden">
                        <label for="product-discAfterTax" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.DISCOUNTAFTERTAX')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step=".000001" id="product-discAfterTax" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                    </div>
                    <!-- Net -->
                    <div class="mb-2">
                        <label for="product-totalSales" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.TOTALPURCHASES')}}
                        </label>
                        <input type="number" id="product-totalSales" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                        readonly>
                    </div>
                    <!-- Net -->
                    <div class="mb-2">
                        <label for="product-total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.TOTAL')}}
                        </label>
                        <input type="number" id="product-total" class="bg-gray-100 text-gray-900 border-none text-sm rounded-sm block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                        readonly>
                    </div>
                    <!--Form -->
                    <p class="text-sm">{{__('app.PRODUCTAPPLYNOTE')}}</p>
                    <span class="text-red-600" id="err-list"></span>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" id="apply" class="mt-3 inline-flex justify-end rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    {{__('app.APPLY')}}
                </button>
                <button type="reset" id="reset" class="mt-3 inline-flex justify-end rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    {{__('app.RESET')}}
                </button>
                <button type="button" class="mt-3 inline-flex justify-start rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                    onclick="$('#InvoiceItems').addClass('hidden')">
                    {{__('app.CANCEL')}}
                </button>
            </div>
            </div>
        </form>
      </div>
    </div>
  </div>

