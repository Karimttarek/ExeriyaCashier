<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Finances.Revenues.get')}}"
                       class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.REVENUES')}}
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 9 4-4-4-4"/>
                    </svg>
                    <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.Edit Revenue')}}</p>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        @forelse($receipt as $rec)
            <form method="POST" action="{{route('Finances.Revenues.update' ,$rec->uuid)}}"
                  enctype="multipart/form-data">
                @csrf
                <!-- Receipt Id & Date -->
                <div class="grid gap-6 md:grid-cols-2 mt-5">
                    <!-- Id -->
                    <div class="mb-2">
                        <label for="no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.RECEIPTID')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no" value="{{ $rec->no }}"
                               class="@error('no') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
                        <input type="datetime-local" name="receipt_date"
                               value="{{ date("Y-m-d\TH:i", strtotime($rec->receipt_date)) }}"
                               class="@error('receipt_date') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required>
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
                    <textarea name="statement" rows="2"
                              class="@error('statement') border border-red-500 @enderror resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $rec->statement }}</textarea>
                    @error('statement')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Children Table -->
                <div class="relative overflow-x-auto rounded mt-5 mb-5">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 text-center">
                        <thead class="text-xs text-gray-900 uppercase bg-blue-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{__('app.REVENUECODE')}}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{__('app.REVENUENAME')}}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{__('app.STATEMENT')}}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{__('app.VALUE')}}
                            </th>
                            <th>*</th>
                        </tr>
                        </thead>
                        <tbody id="tbody" class="tr-item">
                        @foreach($current_expenses as $current)
                            <tr>
                                <input type="hidden" name="exp[{{$loop->index}}][number]" id="num" class="item"
                                       value="{{$loop->index}}">
                                <td style="width: 5%;">
                                    <input type="text" class="text-center bg-inherit border-none w-full rounded-lg"
                                           name="exp[{{$loop->index}}][exp_code]'" value="{{$current->type_id}}"
                                           readonly>
                                </td>
                                <td style="width: 30%;">
                                    <input type="text" name="exp[{{$loop->index}}][exp_name]"
                                           class="text-center bg-inherit border-none w-full rounded-lg"
                                           value="{{$current->type_name}}" readonly>
                                </td>
                                <td style="width: 50%;">
                                    <input type="text"
                                           class="text-center text-sm w-full rounded-lg cursor-pointer hover:ring-blue-500 hover:border-blue-500 border-white rounded-lg"
                                           name="exp[{{$loop->index}}][exp_statement]" id="statement"
                                           value="{{$current->statement}}">
                                </td>
                                <td style="width: 30%;">
                                    <input type="number"
                                           class="text-center w-full text-sm rounded-lg cursor-pointer hover:ring-blue-500 hover:border-blue-500 border-white rounded-lg"
                                           name="exp[{{$loop->index}}][exp_val]" id="rwval" value="{{$current->value}}">
                                </td>
                                <td class="h-print">
                                    <a href="#" id="trash">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                            <path fill-rule="evenodd"
                                                  d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100  hover:bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-200 cursor-pointer"
                            style="color:inherit" id="MoreRows">
                            <td colspan="23" class="p-2 text-center">
                                {{__('app.CLICKTOADDREVENUES')}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Value -->
                <div class="grid grid-flow-row-dense grid-cols-3 gap-6">
                    <!-- Check Number -->
                    <div class="mb-2 col-span-1">
                        <label for="check_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.VALUE')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.000001" name="value" value="{{ $rec->value }}" id="value"
                               class="@error('value') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               readonly>
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
                        <input type="text" name="value_text" value="{{ $rec->value_text }}" id="value_text"
                               class="@error('value_text') border border-red-500 @enderror bg-inherit border-none w-full rounded-lg"
                               readonly>
                        @error('value_text')
                        <div>
                            <span class="font-medium text-red-600">{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- Submit -->
                <button type="submit"
                        class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{__('app.UPDATE')}}
                </button>
                @empty

                @endforelse
            </form>
    </div>
    @include('modal.finances.revenues')
    @push('script')
        <script src="{{URL::asset('js/finances.js')}}"></script>
    @endpush
</x-app-layout>
