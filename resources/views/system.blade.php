<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb>
            <x-slot name="breadcrumb">
                <li class="inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <p class="ml-1 text-sm font-medium hover:text-blue-800 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{__('app.Configuration')}}</p>
                    </li>
            </x-slot>
        </x-breadcrumb>
    </x-slot>
    <!-- Content -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
        <form method="POST" action="{{route('System.store')}}" enctype="multipart/form-data">
            @csrf
            <!-- Company -->
            <div class="mb-2">
                <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  {{__('app.COMPANY')}}
                  <span class="text-red-500">*</span>
              </label>
                <input type="text" name="company_name" value="@isset($system[0]) {{ $system[0]->company_name }} @endisset" class="@error('company_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                @error('company_name')
                 <div>
                     <span class="font-medium text-red-600">{{$message}}</span>
                 </div>
                 @enderror
              </div>
            <!-- Tax Reg Number -->
            <div class="mb-2">
                <label for="tax_rCode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  {{__('app.TAXREGCODE')}}
                  <span class="text-red-500">*</span>
              </label>
              <input type="text" name="tax_rCode" value="@isset($system[0]) {{ $system[0]->tax_rCode }} @endisset" class="@error('tax_rCode') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                @error('tax_rCode')
                 <div>
                     <span class="font-medium text-red-600">{{$message}}</span>
                 </div>
                 @enderror
              </div>
            <!-- Tax Activity Code -->
            <div class="mb-2">
                <div class="mb-2">
                    <label for="tax_aCode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.TAXACTIVITYCODE')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="tax_aCode" id="tax_aCode" class="@error('tax_aCode') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @isset($system[0])
                            <option selected value="{{$system[0]->tax_aCode}}">-- {{$system[0]->tax_aCode}} --</option>
                        @else
                            <option selected disabled></option>
                        @endisset
                        @foreach ($aCodes as $code)
                            @if(LaravelLocalization::getCurrentLocale() == 'en')
                                <option value="{{$code->code}}">{{$code->code .' : '.$code->Desc_en}}</option>
                            @elseif (LaravelLocalization::getCurrentLocale() == 'ar')
                            <option value="{{$code->code}}">{{$code->code .' : '.$code->Desc_ar}}</option>
                            @else
                            <option value="{{$code->code}}">{{$code->code .' : '.$code->Desc_en.' - '.$code->Desc_ar}}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('tax_aCode')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- Id -->
            <div class="mb-2">
              <label for="client_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{__('app.CLIENTID')}}
                <span class="text-red-500">*</span>
            </label>
              <input type="password" name="client_id" value="{{ old('client_id') }}" class="@error('client_id') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              @error('client_id')
               <div>
                   <span class="font-medium text-red-600">{{$message}}</span>
               </div>
               @enderror
            </div>
            <!-- Secret -->
            <div class="mb-2">
                <label for="client_secret" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{__('app.CLIENTSECRET')}}
                </label>
                <input type="password" name="client_secret" value="{{ old('client_secret') }}" class="@error('client_secret') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('client_secret')
                 <div>
                     <span class="font-medium text-red-600">{{$message}}</span>
                 </div>
                 @enderror
            </div>
            <!-- Token Certificate -->
            <div class="mb-2">
                <div class="mb-2">
                    <label for="token_certificate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.Token Certificate')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="token_certificate" id="token_certificate" class="@error('token_certificate') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @isset($system[0])
                            <option selected value="{{ $system[0]->token_certificate }}">{{ $system[0]->token_certificate }}</option>
                        @else
                            <option selected disabled></option>
                        @endisset
                        <option value="Egypt Trust Sealing CA">Egypt Trust Sealing CA</option>
                        <option value="Egypt Trust CA G6">Egypt Trust CA G6</option>
                        <option value="MCDR CA">MCDR CA</option>
                        <option value="Fixed Misr Corporate CA G1">Fixed Misr Corporate CA G1</option>
                    </select>
                    @error('token_certificate')
                    <div>
                        <span class="font-medium text-red-600">{{$message}}</span>
                    </div>
                    @enderror
                </div>
            </div>
            <!-- Thumbnail -->
            <div class="mb-2">
                <div class="mb-2">
                    <label for="thumbnail" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{__('app.Thumbnail')}}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="file" name="thumbnail" class="@error('token_certificate') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @error('thumbnail')
                        <div>
                            <span style="color: red">{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{__('app.SUBMIT')}}
            </button>
        </form>
    </div>
</x-app-layout>
