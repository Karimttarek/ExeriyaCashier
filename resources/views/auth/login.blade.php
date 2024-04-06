<x-guest-layout>
    <x-auth-card>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="grid gap-6">
                <!-- Email Address -->
                <div class="space-y-2">
                    <x-form.label
                        for="email"
                        :value="__('app.EMAIL')"
                    />

                    <x-form.input-with-icon-wrapper>
                        <x-form.input
                            id="email"
                            class="block w-full"
                            type="text"
                            name="email"
                            :value="old('email')"
                            placeholder="{{ __('app.EMAIL')}}"
                            required
                            autofocus
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-form.label
                        for="password"
                        :value="__('auth.pass')"
                    />

                    <x-form.input-with-icon-wrapper>

                        <x-form.input
                            id="password"
                            class="block w-full"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="{{ __('auth.pass') }}"
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="text-blue-500 border-gray-300 rounded"
                            name="remember"
                        >

                        <span class="@if(LaravelLocalization::getCurrentLocale() == 'en') ml-2 @else mr-2 @endif text-sm text-gray-600 dark:text-gray-400">
                            {{ __('auth.Remember Me') }}
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-500 hover:underline" href="{{ route('password.request') }}">
                            {{ __('auth.forgetPass') }}
                        </a>
                    @endif
                </div>

                <button type="submit" class=" justify-center w-full gap-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{__('auth.login')}}
                </button>

                @if (Route::has('register'))
                    <p class="text-sm text-gray-600 dark:text-gray-400">
{{--                        {{ __('auth.Createacount') }}--}}
                        <a href="{{ route('register') }}" class="text-blue-500 hover:underline">
                            {{ __('auth.Createacount') }}
                        </a>
                    </p>
                @endif
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
