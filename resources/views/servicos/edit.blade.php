<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('servicos.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-tools"></i>
                <span>Serviços</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5 truncate max-w-xs sm:max-w-md">
                <i class="bi bi-pencil-square text-blue-600"></i>
                Editar Serviço: {{ $servico->nome }}
            </span>
        </div>
    </x-slot>

    <div class="w-full">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Editar Serviço
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Atualize os dados e o valor base do serviço
                </p>
            </div>

            <a href="{{ route('servicos.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">

            <form action="{{ route('servicos.update', $servico->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nome do Serviço
                    </label>
                    <input type="text" name="nome" value="{{ old('nome', $servico->nome) }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                        required>
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Descrição
                    </label>
                    <textarea name="descricao" rows="4"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">{{ old('descricao', $servico->descricao) }}</textarea>
                </div>

                <div class="md:w-1/2">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Valor Padrão (R$)
                    </label>
                    <input type="number" step="0.01" name="valor_base"
                        value="{{ old('valor_base', $servico->valor_base) }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                        required>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-6">
                    <a href="{{ route('servicos.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Atualizar Serviço
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>
