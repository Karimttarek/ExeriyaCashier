<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{route('Branch.get')}}" class="ml-1 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        {{__('app.BRANCHES')}}
                    </a>
                </li>
                <li class="inline-flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.New Branch')}}</p>
                </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <form method="POST" action="{{route('Branch.store')}}" enctype="multipart/form-data">
            @csrf
            <!-- Name -->
            <div class="mb-2">
              <label for="branch_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.Branch Name')}}
                <span class="text-red-500">*</span>
            </label>
              <input type="text" name="branch_name" value="{{ old('branch_name') }}" class="@error('branch_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
              @error('branch_name')
               <div>
                   <span class="font-medium text-red-600">{{$message}}</span>
               </div>
               @enderror
            </div>
            <!-- Name Ar -->
            <div class="mb-2">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.EMAIL')}}
                </label>
                <input type="text" name="email" value="{{ old('email') }}" class="@error('email') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('email')
                 <div>
                     <span class="font-medium text-red-600">{{$message}}</span>
                 </div>
                 @enderror
              </div>
              <!-- Description -->
            <div class="mb-2">
                <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.PHONE')}}
                </label>
                <input type="text" name="mobile" value="{{ old('mobile') }}" class="@error('mobile') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('mobile')
                 <div>
                     <span class="font-medium text-red-600">{{$message}}</span>
                 </div>
                 @enderror
              </div>
            <!-- COUNTRY & GOVS -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Purchase Price -->
                <div class="mb-2">
                    <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.COUNTRY')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="country" id="country" class="@error('country') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @if(LaravelLocalization::getCurrentLocale() == 'en')
                            <option value="EG">Egypt</option>
                        @else
                            <option value="EG">مصر</option>
                        @endif
                        @foreach ($countries as $country)
                            @if(LaravelLocalization::getCurrentLocale() == 'en')
                                <option value="{{$country->code}}">{{$country->Desc_en}}</option>
                            @else
                                <option value="{{$country->code}}">{{$country->Desc_ar}}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('country')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Sell Price -->
                <div class="mb-2">
                    <label for="gov" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.GOV')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="governorate" id="gov" class="@error('governorate') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option selected disabled>{{__('app.SELECTGOV')}}</option>
                        @foreach ($governorates as $govs)
                            <option value="{{$govs->Desc_en}}">{{$govs->Desc_en.' - '.$govs->Desc_ar}}</option>
                        @endforeach
                    </select>
                    @error('governorate')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- City & Street -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Purchase Price -->
                <div class="mb-2">
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.CITY')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="city" id="cities" class="@error('city') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option selected disabled>{{__('app.SELECTCITY')}}</option>
                        @foreach ($cities as $city)
                            <option value="{{$city->Desc_en}}">{{$city->Desc_en.' - '.$city->Desc_ar}}</option>
                        @endforeach
                    </select>
                    @error('city')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
                <!-- Sell Price -->
                <div class="mb-2">
                    <label for="building_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.BUILDING')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="building_number" value="{{ old('building_number') }}" class="@error('building_number') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    @error('building_number')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- Address -->
            <div class="mb-2">
                <label for="street" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.ADDRESS')}}
                    <span class="text-red-500">*</span>
                </label>
                <textarea name="street" id="street" rows="3" style="resize: none;" required class="@error('street') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                @error('street')
                <div>
                    <span class="font-medium text-red-600">{{$message}}</span>
                </div>
                @enderror
            </div>
            <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{__('app.SUBMIT')}}
            </button>
        </form>
    </div>

    @push('script')
    <script src="{{URL::asset('js/custom.js')}}"></script>
    @endpush
</x-app-layout>
