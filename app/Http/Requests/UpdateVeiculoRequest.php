<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVeiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->filled('placa')) {
            $this->merge([
                'placa' => strtoupper(trim(preg_replace('/[^A-Za-z0-9]/', '', $this->placa)))
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'cliente_id' => [
                'required',
                Rule::exists('clientes', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
            'marca'  => 'required',
            'modelo' => 'required',
            'placa'  => [
                'required',
                new \App\Rules\PlacaVeiculoRule,
                Rule::unique('veiculos', 'placa')
                    ->ignore($this->route('veiculo')->id)
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
