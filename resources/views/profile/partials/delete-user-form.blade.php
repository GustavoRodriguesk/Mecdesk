<section>
    <header class="mb-6">
        <div class="flex items-center gap-2 text-rose-700 font-bold text-base">
            <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
            <h2>Exclusão de Conta</h2>
        </div>
        <p class="mt-1 text-sm text-gray-500 max-w-2xl">
            Uma vez excluída, todos os recursos e dados vinculados à sua conta serão permanentemente removidos.
            Certifique-se de que não precisará mais deste acesso antes de prosseguir.
        </p>
    </header>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all cursor-pointer">
        <i class="bi bi-trash-fill"></i>
        Excluir Minha Conta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 text-rose-600 mb-4">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-xl shrink-0">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">
                    Tem certeza de que deseja excluir sua conta?
                </h3>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed mb-6">
                Esta ação é <strong class="text-rose-700">irreversível</strong>. Todos os seus dados serão
                permanentemente apagados.
                Por favor, digite sua senha de acesso abaixo para confirmar a exclusão.
            </p>

            <div class="mb-6">
                <label for="password_deletion" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Sua Senha de Confirmação <span class="text-rose-500">*</span>
                </label>
                <input id="password_deletion" name="password" type="password"
                    class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-100 transition-all {{ $errors->userDeletion->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : '' }}"
                    placeholder="Digite sua senha para confirmar">
                @if ($errors->userDeletion->has('password'))
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-sm font-semibold rounded-lg shadow-sm transition-all cursor-pointer">
                    Cancelar
                </button>

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all cursor-pointer">
                    <i class="bi bi-trash-fill"></i>
                    Confirmar Exclusão Definitiva
                </button>
            </div>
        </form>
    </x-modal>
</section>
