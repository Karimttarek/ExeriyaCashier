<x-guest-layout>
    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="grid gap-6">
                <!-- Email Address -->
                <div class="space-y-2">
                    <x-form.label
                        for="email"
                        :value="__('app.EMAIL')"
                    />

                    <x-form.input
                        id="email"
                        class="block w-full"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required
                        autofocus
                    />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-form.label
                        for="password"
                        :value="__('auth.pass')"
                    />

                    <x-form.input
                        id="password"
                        class="block w-full"
                        type="password"
                        name="password"
                        required
                    />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <x-form.label
                        for="password_confirmation"
                        :value="__('auth.passConfirm')"
                    />

                    <x-form.input
                        id="password_confirmation"
                        class="block w-full"
                        type="password"
                        name="password_confirmation"
                        required
                    />
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class=" justify-center w-full gap-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('auth.resetPass') }}
                    </button>
                </div>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
