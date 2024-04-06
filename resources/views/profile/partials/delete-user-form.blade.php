<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium">
            {{ __('app.Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('app.Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain') }}
        </p>
    </header>

    <x-button
        variant="danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('app.Delete Account') }}
    </x-button>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >
        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="p-6"
        >
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium">
                {{ __('app.Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('app.Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account') }}
            </p>

            <div class="mt-6 space-y-6">
                <x-form.label
                    for="delete-user-password"
                    value="Password"
                    class="sr-only"
                />

                <x-form.input
                    id="delete-user-password"
                    name="password"
                    type="password"
                    class="block w-3/4"
                    placeholder="Password"
                />

                <x-form.error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" variant="secondary" class="justify-center gap-2 inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('app.Cancel') }}
                </button>
                <button type="submit" class="justify-center gap-2 inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('app.Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
