<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-5xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Meu Perfil
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Gerencie suas informações de conta e senha
            </p>
        </div>

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 border border-gray-200 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 border border-gray-200 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 border border-gray-200 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
