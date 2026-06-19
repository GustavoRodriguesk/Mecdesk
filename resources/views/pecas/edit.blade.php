<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Peça
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Editar Peça
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Atualize os dados e o estoque
                </p>
            </div>
            
            <a href="{{ route('pecas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">

            <form action="{{ route('pecas.update', $peca->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nome da Peça
                    </label>
                    <input type="text" name="nome" value="{{ old('nome', $peca->nome) }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                    @error('nome') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Código
                        </label>
                        <input type="text" name="codigo" value="{{ old('codigo', $peca->codigo) }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        @error('codigo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Valor Unitário (R$)
                        </label>
                        <input type="number" step="0.01" name="valor_unitario" value="{{ old('valor_unitario', $peca->valor_unitario) }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                        @error('valor_unitario') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Estoque
                        </label>
                        <input type="number" name="estoque" value="{{ old('estoque', $peca->estoque) }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                        @error('estoque') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-6">
                    <a href="{{ route('pecas.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Atualizar Peça
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>