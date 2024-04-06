<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Manufactur.get')}}" class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.MANUFACTURS')}}
                    </a>
                </li>
                <li class="inline-flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.EDITPRODUCT')}}</p>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        @forelse($parent_product as $parent)
        <form method="POST" action="{{route('Manufactur.update',$parent->uuid)}}" enctype="multipart/form-data">
            @csrf
            <!-- Item Code & Code Type -->
                <!-- Item Name -->
                <div class="mb-2">
                    <label for="tax_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.SEARCHBYITEMNAMEORCODE')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" value="{{ old('products-filter') }}" id="products-filter" class="@error('products-filter') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                    @error('products-filter')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Parent -->
                <div class="mb-2">
                    <label for="parent" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.PRODUCTS')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="parent" id="products-search" class="@error('parent') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option selected value="{{$parent->uuid . '*' . $parent->name}}">-- {{ $parent->name }} --</option>
                        @foreach ($products as $product)
                                <option value="{{$product->name}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                    @error('type')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Children Table -->
                <div class="relative overflow-x-auto rounded mt-5 mb-5">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-stripd">
                        <thead class="text-xs text-gray-900 uppercase bg-blue-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{__('app.ITEM')}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{__('app.DESCRIPTION')}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{__('app.UNITPRICE')}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{__('app.QTY')}}
                                </th>
                                <th scope="col" class="px-6 py-3">*</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" class="tr-item">
                            @foreach($relateds as $related)
                                <tr>
                                    <input type='hidden' name='items[{{ $related->number }}][number]' class='item' id="num" value="{{$related->number}}">
                                    <input type='hidden' name='items[{{ $related->number}}][uuid]' class='item' value="{{$related->child_uuid}}">
                                    <td style='width: 40%;'>
                                        <input type='text' class="col" name='items[{{$related->number}}][item]' style='background: transparent ; border:none' value="{{ $related->name }}" readonly>
                                    </td>
                                    <td style='width: 40%;'>
                                        <input type='text' class="col" style='background: transparent ; border:none' value="{{$related->description}}" readonly>
                                    </td>
                                    <td class='rw-price'>
                                        <input type='number' class="col" name='items[{{$related->number}}][price]' id='price' style='background: transparent ; border:none' value="{{ $related->price }}" >
                                    </td>
                                    <td class='rw-qty'>
                                        <input type='number' class="col" name='items[{{$related->number}}][qty]' id='rwqty' style='background: transparent ; border:none' value="{{ $related->qty }}" >
                                    </td>
                                    <td class=' h-print'><a href='#' id="trash">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"> <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/> <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/> </svg>
                                    </a></td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100  hover:bg-gray-200 border-b dark:bg-gray-800 dark:border-gray-200 cursor-pointer" style="color:inherit" id="MoreRows">
                                <td colspan="23" class="p-2 text-center">
                                    {{__('app.CLICKTOADDPRODUCT')}}
                                </td>
                            </tr>
                            </tbody>
                        </tbody>
                    </table>
                </div>
                <!-- Cost-->
                <div class="mb-2">
                    <label for="tax_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.COST')}}
                    </label>
                    <input type="text"  id="total" class="@error('total') border border-red-500 @enderror border-none focus:border-none rounded"
                    placeholder="0.0" readonly>
                </div>
            <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{__('app.UPDATE')}}
            </button>
        </form>
        @empty
        @endforelse
    </div>
    @include('modal.manufacturs')
    @push('script')
    <script src="{{URL::asset('js/manufactur1608.js')}}"></script>
    @endpush
</x-app-layout>
