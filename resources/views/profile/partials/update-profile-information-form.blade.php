<section>
    <header class="mb-6">
        <div class="flex items-center gap-2 text-slate-900 font-bold text-base">
            <i class="bi bi-person-lines-fill text-blue-600"></i>
            <h2>Informações do Perfil</h2>
        </div>
        <p class="mt-1 text-sm text-gray-500">
            Atualize seus dados cadastrais e o endereço de e-mail utilizado para login no sistema.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5 max-w-xl">
        @csrf
        @method('patch')

        {{-- Nome Completo --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Nome Completo <span class="text-rose-500">*</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : '' }}"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Seu nome completo"
            >
            @error('name')
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- E-mail --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Endereço de E-mail <span class="text-rose-500">*</span>
            </label>
            <input
                id="email"
                name="email"
                type="email"
                class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : '' }}"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                placeholder="seu@email.com"
            >
            @error('email')
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex flex-col gap-1.5">
                    <p class="font-medium">
                        Seu endereço de e-mail ainda não foi verificado.
                    </p>
                    <button
                        form="send-verification"
                        type="submit"
                        class="text-left font-bold text-amber-900 underline hover:text-amber-950 focus:outline-none"
                    >
                        Clique aqui para reenviar o e-mail de verificação.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-semibold text-emerald-700">
                            Um novo link de verificação foi enviado para o seu endereço de e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all cursor-pointer"
            >
                <i class="bi bi-check-lg"></i>
                Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg"
                >
                    <i class="bi bi-check-circle-fill text-emerald-600"></i>
                    Alterações salvas com sucesso!
                </div>
            @endif
        </div>
    </form>
</section>
