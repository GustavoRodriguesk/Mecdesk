<x-app-layout>

    <x-slot name="header">
        Meu Perfil
    </x-slot>

    <div class="w-full mx-auto space-y-6">

        {{-- ── Cabeçalho da Página ── --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div class="flex items-center gap-3.5">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center text-2xl font-bold">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Meu Perfil</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Gerencie suas informações pessoais e credenciais de acesso
                    </p>
                </div>
            </div>

            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-200">
                <i class="bi bi-shield-lock-fill text-blue-600"></i>
                {{ auth()->user()->role === 'admin' ? 'Administrador' : 'Funcionário' }}
            </span>
        </div>

        {{-- ── Seção 1: Dados Pessoais ── --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- ── Seção 2: Atualização de Senha ── --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        {{-- ── Seção 3: Exclusão de Conta ── --}}
        <div class="bg-white border border-rose-200 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

</x-app-layout>
