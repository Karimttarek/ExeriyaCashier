<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Product.get')}}" class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.PRODUCTS')}}
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

        @forelse ($data as $product)
    <form method="POST" action="{{route('Product.update' ,$product->uuid)}}" enctype="multipart/form-data">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                @csrf
                <!-- Item Code & Code Type -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Code Type -->
                    <div class="mb-2">
                        <label for="bar_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.Barcode')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bar_code" value="{{ $product->bar_code }}" class="@error('bar_code') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               >
                        @error('bar_code')
                        <div>
                            <span class="font-medium text-red-600">{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                    <!-- Item Code -->
                    <div class="mb-2">
                        <label for="item_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.ITEMCODE')}}
                            <span class="text-red-500">*</span>
                        </label>
{{--                        {{ str_contains($product->item_code ,'EG-') ? e(substr($product->item_code, strrpos($product->item_code, '-') + 1)) : $product->item_code }}--}}
                        <input type="text" name="item_code" value="{{ $product->item_code }}" class="@error('item_code') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required>
                        @error('item_code')
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
                    <input type="text" name="name" value="{{ $product->name }}" class="@error('name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    @error('name')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Name Ar -->
                <div class="mb-2">
                    <label for="namear" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.NAMEAR')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name_ar" value="{{ $product->name_ar }}" class="@error('name_ar') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    @error('name_ar')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Description -->
                <div class="mb-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.DESCRIPTION')}}
                    </label>
                    <input type="text" name="description" value="{{ $product->description }}" class="@error('description') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @error('description')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Description Ar -->
                <div class="mb-2">
                    <label for="desc_ar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.DESCRIPTIONAR')}}
                    </label>
                    <input type="text" name="description_ar" value="{{ $product->description_ar}}" class="@error('description_ar') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @error('description_ar')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>


                <!-- Unit Type & Currency -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Purchase Price -->
                    <div class="mb-2">
                        <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.UNITTYPE')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="unit" id="unit" class="@error('unit') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option selected value="{{$product->type_code.','.$product->type_desc}}">{{ $product->type_desc }}</option>
                            @foreach ($units as $u)
                                <option value="{{$u->name .','.$u->name}}">{{$u->name}}</option>
                            @endforeach
                        </select>
                        @error('unit')
                        <div>
                            <span class="font-medium text-red-600">{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                    <!-- Sell Price -->
                    <div class="mb-2">
                        <label for="currency" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('app.CURRENCY')}}
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="currency" id="currency" class="@error('currency') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option selected value="EGP">Egyption Pound</option>
                        </select>
                        @error('currency')
                        <div>
                            <span class="font-medium text-red-600">{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800 mt-2">
            <!-- First of Units -->
        <div class="grid gap-6 md:grid-cols-4">
            <!-- Second UNit Type -->
            <div class="mb-2">
                <label for="first_unit_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Type')}}
                    <span class="text-red-500">*</span>
                </label>
                <select name="first_unit_type" id="first_unit_type" class="@error('first_unit_type') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option></option>
                    @foreach ($units as $u)
                        <option value="{{$u->name}}" @selected(  $product->first_unit_type == $u->name )>
                            {{$u->name}}
                        </option>
                    @endforeach
                </select>
                @error('first_unit_type')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Unit Qty -->
            <div class="mb-2">
                <label for="first_unit_qty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Content')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" name="first_unit_qty" step=".001" min="1" value="{{ $product->first_unit_qty }}" class="@error('first_unit_qty') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('first_unit_qty')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Second UNit Type -->
            <div class="mb-2">
                <label for="first_unit_pur_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.PURCHASEPRICE')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" name="first_unit_pur_price" step=".001" min="0" value="{{ $product->first_unit_pur_price }}" class="@error('first_unit_pur_price') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('first_unit_pur_price')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Unit Qty -->
            <div class="mb-2">
                <label for="first_unit_sell_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.SELLPRICE')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" name="first_unit_sell_price" step=".001" min="0" value="{{ $product->first_unit_sell_price }}" class="@error('second_unit_qty') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('first_unit_sell_price')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
        </div>
        <!-- Second of Units -->
        <div class="grid gap-6 md:grid-cols-4">
            <!-- Second UNit Type -->
            <div class="mb-2">
                <label for="second_unit_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Type')}}
                </label>
                <select name="second_unit_type" id="second_unit_type" class="@error('second_unit_type') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option></option>
                    @foreach ($units as $u)
                    <option value="{{$u->name}}" @selected( $product->second_unit_type == $u->name )>{{$u->name}}</option>
                    @endforeach
                </select>
                @error('second_unit_type')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Unit Qty -->
            <div class="mb-2">
                <label for="second_unit_qty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Content')}}
                </label>
                <input type="number" name="second_unit_qty" step=".001" min="1" value="{{ $product->second_unit_qty }}" class="@error('second_unit_qty') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('second_unit_qty')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Second UNit Type -->
            <div class="mb-2">
                <label for="second_unit_pur_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.PURCHASEPRICE')}}
                </label>
                <input type="number" name="second_unit_pur_price" step=".001" min="0" value="{{ $product->second_unit_pur_price }}" class="@error('second_unit_pur_price') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('second_unit_pur_price')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Unit Qty -->
            <div class="mb-2">
                <label for="second_unit_sell_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.SELLPRICE')}}
                </label>
                <input type="number" name="second_unit_sell_price" step=".001" min="0" value="{{ $product->second_unit_sell_price }}" class="@error('second_unit_qty') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('second_unit_sell_price')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
        </div>
        <!-- Third of Units -->
        <div class="grid gap-6 md:grid-cols-4">
            <!-- Second UNit Type -->
            <div class="mb-2">
                <label for="second_unit_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Type')}}
                </label>
                <select name="third_unit_type" id="third_unit_type" class="@error('third_unit_type') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option></option>
                    @foreach ($units as $u)
                    <option value="{{$u->name}}" @selected( $product->third_unit_type == $u->name )>{{$u->name}}</option>
                    @endforeach
                </select>
                @error('third_unit_type')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Unit Qty -->
            <div class="mb-2">
                <label for="third_unit_qty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.Content')}}
                </label>
                <input type="number" name="third_unit_qty" step=".001" min="1" value="{{ $product->third_unit_qty }}" class="@error('third_unit_qty') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('third_unit_qty')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Second UNit Type -->
            <div class="mb-2">
                <label for="third_unit_pur_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.PURCHASEPRICE')}}
                </label>
                <input type="number" name="third_unit_pur_price" step=".001" min="0" value="{{ $product->third_unit_pur_price }}" class="@error('third_unit_pur_price') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('third_unit_pur_price')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Unit Qty -->
            <div class="mb-2">
                <label for="third_unit_sell_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.SELLPRICE')}}
                </label>
                <input type="number" name="third_unit_sell_price" step=".001" min="0" value="{{ $product->third_unit_sell_price }}" class="@error('third_unit_sell_price') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                @error('third_unit_sell_price')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
        </div>

    </div>
{{--
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800 mt-2">
        <!-- Active From & To -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Active From -->
            <div class="mb-2">
                <label for="active_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.ACTIVEFROM')}}
                    <span class="text-red-500">*</span>
                </label>
                <input type="date" name="active_from" value="{{\Illuminate\Support\Carbon::parse($product->active_from)->format('Y-m-d')}}" class="@error('active_from') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__('app.ACTIVEFROM')}}">
                @error('active_from')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Active To -->
            <div class="mb-2">
                <label for="active_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.ACTIVETO')}}
                </label>
                <input type="date" name="active_to" value="{{\Illuminate\Support\Carbon::parse($product->active_to)->format('Y-m-d')}}" class="@error('active_to') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__('app.PARENTCODE')}}">

                @error('active_to')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
        </div>
        <!-- Parent Code & Request Reason -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Active From -->
            <div class="mb-2">
                <label for="parent_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.PARENTCODE')}}
                </label>
                <input type="text" pattern="[0-9]+" name="parent_code" value="{{ $product->parent_code }}" class="@error('parent_code') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__('app.PARENTCODE')}}">
                <span class="text-gray-400 font-sm">Required only while sending to ETA</span>
                @error('parent_code')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <!-- Active To -->
            <div class="mb-2">
                <label for="parent_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.REQUESTREASON')}}
                </label>
                <input type="text" name="request_reason" value="{{ $product->request_reason }}" class="@error('request_reason') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__('app.REQUESTREASON')}}">
                <span class="text-gray-400 font-sm">Required only while sending to ETA</span>
                @error('request_reason')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
        </div>
    </div> --}}

    @if (Auth::user()->role == 1 && !empty($product->codeUsageRequestId))
                    <div class="flex items-start mb-6 mt-6">
                        <div class="flex items-center h-5">
                            <input id="updateETA" type="checkbox" name="updateEinvoice" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800">
                        </div>
                        <label for="updateETA" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{__('app.Update ETA')}}</label>
                    </div>
                @endif
        @empty

        @endforelse

    <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        {{__('app.UPDATE')}}
    </button>
</form>
    @push('script')
    @endpush
</x-app-layout>
