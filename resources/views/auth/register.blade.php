<x-guest-layout>
    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <x-form.label
                        for="name"
                        :value="__('app.NAME')"
                    />

                    <x-form.input-with-icon-wrapper>

                        <x-form.input
                            id="name"
                            class="block w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus
                            placeholder="{{ __('app.NAME') }}"
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

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
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            placeholder="{{ __('app.EMAIL') }}"
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

                <!-- Phone  -->
                <div class="space-y-2">
                    <x-form.label
                        for="email"
                        :value="__('app.PHONE')"
                    />

                    <x-form.input-with-icon-wrapper>

                        <x-form.input
                            id="phone"
                            class="block w-full"
                            type="text"
                            name="phone"
                            :value="old('phone')"
                            required
                            placeholder="{{ __('app.PHONE') }}"
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
                            autocomplete="new-password"
                            placeholder="{{ __('auth.pass') }}"
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <x-form.label
                        for="password_confirmation"
                        :value="__('auth.passConfirm')"
                    />

                    <x-form.input-with-icon-wrapper>

                        <x-form.input
                            id="password_confirmation"
                            class="block w-full"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="{{ __('auth.passConfirm') }}"
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

                <div>
                    <button type="submit" class="justify-center w-full gap-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('auth.Register') }}
                    </button>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('auth.alreadyHaveAccount') }}
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                        {{__('auth.login')}}
                    </a>
                </p>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
