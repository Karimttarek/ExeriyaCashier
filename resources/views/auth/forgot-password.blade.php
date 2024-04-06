<x-guest-layout>
    <x-auth-card>
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('auth.Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
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
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            placeholder="{{ __('app.EMAIL') }}"
                        />
                    </x-form.input-with-icon-wrapper>
                </div>

                <div>
                    <button type="submit" class=" justify-center w-full gap-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('auth.passResetLink') }}
                    </button>
                </div>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
