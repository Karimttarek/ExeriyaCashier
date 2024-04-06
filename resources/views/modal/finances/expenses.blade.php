<div class="relative hidden z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="finances">
    <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <form>
        <div class="relative transform overflow-hidden rounded-lg border border-gray bg-white @if(LaravelLocalization::getCurrentLocale() == 'en') text-left @else text-right @endif shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 mb-3">
            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">{{__('app.EXPENSES')}}</h3>
            <div class="mt-2">
                <!--Form -->
                <!-- Item Code & Code Type -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Code Type -->
                    <div class="mb-2">
                        <label for="code_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.EXPENSECODE')}}
                        </label>
                        <input type="number" name="exp_code" value="{{ old('exp_code') }}" id="exp_code" class="@error('exp_code') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <!-- Item Code -->
                    <div class="mb-2">
                        <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.EXPENSENAME')}}
                        </label>
                        <select name="exp_name" id="exp_name" class="@error('exp_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option selected disabled></option>
                            @foreach ($expenses as $exp)
                                <option value="{{$exp->name}}">{{$exp->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Item Name -->
                <div class="mb-2">
                    <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.STATEMENT')}}
                    </label>
                    <textarea name="statement" id="exp_statement" rows="2" class="@error('statement') border border-red-500 @enderror resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('statement') }}</textarea>
                </div>
                <!-- Item Description -->
                <div class="mb-2">
                    <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.VALUE')}}
                    </label>
                    <input type="number" step="0.000001" id="exp_value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                </div>
                <!--Form -->
                <span class="text-red-600" id="err-list"></span>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button type="button" id="expApply" class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                {{__('app.APPLY')}}
            </button>
            <button type="reset" id="reset" class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                {{__('app.RESET')}}
            </button>
            <button type="button" class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
             onclick="$('#finances').addClass('hidden')">
                {{__('app.CANCEL')}}
            </button>
          </div>
        </div>
    </form>
      </div>
    </div>
  </div>

