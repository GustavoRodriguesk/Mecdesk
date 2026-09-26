<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrdemServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => [
                'required',
                Rule::exists('clientes', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id)
            ],
            'veiculo_id' => [
                'required',
                Rule::exists('veiculos', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->where('cliente_id', $this->cliente_id)
            ],
            'funcionario_id' => [
                'nullable',
                Rule::exists('users', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
            'descricao_problema' => 'required|string',
            'problemas_previos'  => 'nullable|string',
            'observacoes'        => 'nullable|string',
            'status'             => [
                'required',
                Rule::in([
                    'aberta',
                    'em_andamento',
                    'aguardando_aprovacao',
                    'aprovada',
                    'reprovada',
                    'concluida',
                    'entregue',
                    'cancelada',
                ])
            ],
            'fotos'              => 'nullable|array',
            'fotos.*'            => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ];
    }
}
