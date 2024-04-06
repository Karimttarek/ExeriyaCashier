<div class="grid md:grid-cols-1">
    <!-- Receiver Type & TaxRegNumber & Name -->
    <div class="grid grid-flow-row-dense lg:grid-cols-5 md:grid-cols-2 sm:grid-cols-2 gap-4 mb-2">
        <div class="lg:col-span-1">
            <label for="customer_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.TYPE')}}
                <span class="text-red-500">*</span>
            </label>
            <select name="customer_type" id="customer_type" class="@error('customer_type') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                @isset($head->customer_type)
                <option value="{{$head->customer_type}}" selected>
                    @if($head->customer_type == 'B')
                        {{__('app.BUSINESS')}}
                    @elseif($head->customer_type == 'P')
                        {{__('app.PERSON')}}
                    @else
                        {{__('app.FOREIGER')}}
                    @endif
                </option>
                @else
                    <option selected disabled></option>
                @endisset
                <option value="B" @selected(old('customer_type') == "B")>{{__('app.BUSINESS')}}</option>
                <option value="P" @selected(old('customer_type') == "P")>{{__('app.PERSON')}}</option>
                <option value="F" @selected(old('customer_type') == "F")>{{__('app.FOREIGER')}}</option>
            </select>
            @error('customer_type')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
        <!-- Tax Reg number -->
        <div class="lg:col-span-1">
            <label for="customer_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.TAXREGCODE')}}
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="customer_id" id="customer_id" value="@isset($head->customer_id) {{ $head->customer_id }} @else {{old('customer_id')}} @endisset" class="@error('customer_id') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >
            @error('customer_id')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
        <!-- Name -->
        <div  class="lg:col-span-3">
            <label for="customer_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.CUSTOMER')}}
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="customer_name" id="customer_name" value="@isset($head->customer_name) {{ $head->customer_name }} @else {{old('customer_name')}} @endisset" class="@error('customer_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required>
            @error('customer_name')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
    </div>
    <hr class="bg-gray-100 block lg:hidden md:hidden mt-5 mb-5">
    <div class="grid grid-flow-row-dense lg:grid-cols-8 md:grid-cols-2 gap-4">
        <!-- Country -->
        <div class="lg:col-span-1">
            <label for="customer_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.COUNTRY')}}
                <span class="text-red-500">*</span>
            </label>
            <select name="customer_country" id="customer_country" class="@error('customer_country') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                @isset($head->customer_country)
                    <option value="{{$head->customer_country}}">{{$head->customer_country}}</option>
                @else
                    <option selected disabled></option>
                @endisset
                @if(LaravelLocalization::getCurrentLocale() == 'en')
                    <option value="EG" @selected(old('customer_country'))>Egypt</option>
                @else
                    <option value="EG" @selected(old('customer_country'))>مصر</option>
                @endif

                @foreach ($countries as $country)
                    @if(LaravelLocalization::getCurrentLocale() == 'en')
                        <option value="{{$country->code}}"  @selected(old('customer_country') == $country->code)>{{$country->Desc_en}}</option>
                    @else
                        <option value="{{$country->code}}"  @selected(old('customer_country') == $country->code)>{{$country->Desc_ar}}</option>
                    @endif
                @endforeach
            </select>
            @error('customer_country')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
         <!-- Gov -->
         <div class="lg:col-span-1">
            <label for="customer_gov" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.GOV')}}
                <span class="text-red-500">*</span>
            </label>
            <select name="customer_gov" id="gov" class="@error('customer_gov') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                @isset($head->customer_gov)
                    <option value="{{$head->customer_gov}}">{{$head->customer_gov}}</option>
                @else
                    <option selected disabled></option>
                @endisset
                @foreach ($governorates as $govs)
                    <option value="{{$govs->Desc_en}}" @selected(old('customer_gov') == $govs->Desc_en)>{{$govs->Desc_en.' - '.$govs->Desc_ar}}</option>
                @endforeach
            </select>
            @error('customer_gov')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
         <!-- City -->
         <div class="lg:col-span-1">
            <label for="customer_city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.CITY')}}
                <span class="text-red-500">*</span>
            </label>
            <select name="customer_city" id="cities" class="@error('customer_city') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                @isset($head->customer_city)
                    <option value="{{$head->customer_city}}">{{$head->customer_city}}</option>
                @else
                    <option selected disabled></option>
                @endisset
                @foreach ($cities as $city)
                    <option value="{{$city->Desc_en}}" @selected(old('customer_city') == $city->Desc_en)>{{$city->Desc_en.' - '.$city->Desc_ar}}</option>
                @endforeach
            </select>
            @error('customer_city')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
        <!-- Building Number -->
        <div class="lg:col-span-1">
            <label for="customer_building_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.BUILDING')}}
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="customer_building_number" id="customer_building_number" value="@isset($head->customer_building_number) {{ $head->customer_building_number }} @else {{old('customer_building_number')}} @endisset" class="@error('customer_building_number') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required>
            @error('customer_building_number')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
        <!-- Building Number -->
        <div class="lg:col-span-4">
            <label for="customer_street" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.STREET')}}
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="customer_street" id="customer_street" value="@isset($head->customer_street) {{ $head->customer_street }} @else {{old('customer_street')}} @endisset" class="@error('customer_street') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required>
            @error('customer_street')
            <div>
                <span class="font-medium text-red-600">{{$message}}</span>
            </div>
            @enderror
        </div>
    </div>
</div>
