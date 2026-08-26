<section>
    <header class="mb-6">
        <div class="flex items-center gap-2 text-slate-900 font-bold text-base">
            <i class="bi bi-key-fill text-blue-600"></i>
            <h2>Segurança e Senha</h2>
        </div>
        <p class="mt-1 text-sm text-gray-500">
            Certifique-se de utilizar uma senha forte e segura para proteger o acesso à sua conta.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5 max-w-xl">
        @csrf
        @method('put')

        {{-- Senha Atual --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Senha Atual <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all {{ $errors->updatePassword->has('current_password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : '' }}"
                    autocomplete="current-password"
                    placeholder="Digite sua senha atual"
                    required
                >
                <button
                    type="button"
                    onclick="togglePasswordVisibility('update_password_current_password', 'eyeCurrent')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"
                    aria-label="Mostrar ou ocultar senha"
                >
                    <i class="bi bi-eye" id="eyeCurrent"></i>
                </button>
            </div>
            @if ($errors->updatePassword->has('current_password'))
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        {{-- Nova Senha --}}
        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Nova Senha <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all {{ $errors->updatePassword->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : '' }}"
                    autocomplete="new-password"
                    placeholder="Mínimo de 8 caracteres"
                    required
                >
                <button
                    type="button"
                    onclick="togglePasswordVisibility('update_password_password', 'eyeNew')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"
                    aria-label="Mostrar ou ocultar senha"
                >
                    <i class="bi bi-eye" id="eyeNew"></i>
                </button>
            </div>
            @if ($errors->updatePassword->has('password'))
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        {{-- Confirmar Nova Senha --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Confirmar Nova Senha <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all {{ $errors->updatePassword->has('password_confirmation') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : '' }}"
                    autocomplete="new-password"
                    placeholder="Repita a nova senha"
                    required
                >
                <button
                    type="button"
                    onclick="togglePasswordVisibility('update_password_password_confirmation', 'eyeConfirm')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"
                    aria-label="Mostrar ou ocultar senha"
                >
                    <i class="bi bi-eye" id="eyeConfirm"></i>
                </button>
            </div>
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all cursor-pointer"
            >
                <i class="bi bi-shield-lock-fill"></i>
                Atualizar Senha
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg"
                >
                    <i class="bi bi-check-circle-fill text-emerald-600"></i>
                    Senha alterada com sucesso!
                </div>
            @endif
        </div>
    </form>
</section>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
    }
</script>
