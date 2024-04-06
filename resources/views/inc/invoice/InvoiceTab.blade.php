<div x-show="openTab === 1"
    class="text-body-color p-2 text-base leading-relaxed">
    <!-- InternalID & Date and Type -->
    <div class="grid lg:grid-cols-3 md:grid-col-1 sm:grid-col-1 gap-4 border-b border-gray-100 mb-5">
        <div>
            <!-- InternalID -->
            <div>
                <label for="internal_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.INTERNALID')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" name="internal_id" id="internal_id" value="@if(isset($head->internal_id) && str_contains(url()->current(), '/edit')){{$head->internal_id}}@else{{$id}}@endif" class="@error('internal_id') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required @if(isset($head->status) && $head->status != 'Pending' && str_contains(url()->current(), 'sales/edit') ) readonly @endisset>
                @error('internal_id')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Datetime -->
            <div class="mt-2">
                <label for="invoice_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.INVOICEDATE')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="invoice_date" id="invoice_date"
                   @if(isset($head->internal_id) && str_contains(url()->current(), 'edit'))
                    value="{{ date("Y-m-d\TH:i", strtotime($head->invoice_date)) }}"
                    @else
                    value="{{ date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))) }}"
                       @endif
                    @if(isset($head->status) && $head->status != 'Pending' && str_contains(url()->current(), 'sales/edit')) readonly @endisset
                    class="@error('invoice_date') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required>
                    @error('invoice_date')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            <!-- Type -->
            @if (in_array(Route::current()->getName() , array('Sales.create','Sales.edit','Sales.getCopy')) )
                <div class="mt-2 mb-5">
                    <label for="internal_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.INVOICETYPE')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type" class="@error('document_type') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @isset($head->document_type)
                            <option value="{{$head->document_type}}" selected>{{ __('app.'.\App\Enums\InvoiceType::byName($head->document_type))}}</option>
                        @endisset
                        <option value="I" @selected(old('document_type') == "I")>{{__('app.INVOICEI')}}</option>
                        <option value="C" @selected(old('document_type') == "C")>{{__('app.CREDITNOTE')}}</option>
                        <option value="D" @selected(old('document_type') == "D")>{{__('app.DEBITNOTE')}}</option>
                    </select>
                    @error('document_type')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            @endif

        </div>
        <!-- Issuer -->
        <div>
            <!-- Issuer -->
            <div>
                <label for="internal_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Branch Name')}}
                </label>
                <select name="branch_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    @isset($head->branch_name)
                        <option selected value="{{ $head->branch_name }}">{{ $head->branch_name }}</option>
                    @endisset
                    @foreach ($branches as $b)
                    <option value="{{ $b->branch_name }}" @selected(old('branch_name') == $b->branch_name )>{{ $b->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Address -->
            <div class="mt-1">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.ADDRESS')}}
                </label>
                <textarea readonly name="notes" rows="5" cols="2" class="text-left resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">@isset($head->country)
                    {{ $head->country }}
                    {{ $head->governorate}}
                    {{  $head->city }}
                    {{ $head->street}}
                    @endisset
                    {{ $branches[0]->country }}
                    {{ $branches[0]->governorate}}
                    {{  $branches[0]->city }}
                    {{ $branches[0]->street}}
                </textarea>
            </div>
        </div>

        <div>
            <!-- Payment Type -->
            <label for="internal_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.PAYMENTTYPE')}}
                <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1">
                <div class="flex items-center pl-4 rounded">
                    <input id="cashed" type="radio" value="1" name="is_cash" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    @isset($head->is_cash) @if($head->is_cash == 1) checked @endif @else checked @endisset >
                    <label for="cashed" class="w-full py-1 @if(LaravelLocalization::getCurrentLocale() == 'en') ml-2 @else mr-2 @endif text-sm font-medium text-gray-900 dark:text-gray-300">{{__('app.CASH')}}</label>
                </div>
                <div class="flex items-center pl-4 rounded">
                    <input id="deferred" type="radio" value="0" name="is_cash" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    @isset($head->is_cash) @if($head->is_cash == 0) checked @endif @endisset>
                    <label for="deferred" class="w-full py-1 @if(LaravelLocalization::getCurrentLocale() == 'en') ml-2 @else mr-2 @endif text-sm font-medium text-gray-900 dark:text-gray-300">{{__('app.DEFERRED')}}</label>
                </div>
            </div>
        </div>
    </div>
    <!-- Issuer & Receiver -->
    @if(str_contains(url()->current(), '/sales'))
        @include('inc.invoice.receiver')
    @else
        @include('inc.invoice.receiverPur')
    @endif
    <div class="flex justify-end">
        <button type="button" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            onclick="document.getElementById('ItemTab').click();">
            {{__('app.NEXT')}}
        </button>
    </div>
</div>
