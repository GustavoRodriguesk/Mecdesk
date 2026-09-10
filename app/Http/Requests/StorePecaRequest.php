<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePecaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required',
            'codigo' => [
                'nullable',
                Rule::unique('pecas', 'codigo')
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->whereNull('deleted_at'),
            ],
            'estoque' => 'required|integer|min:0',
            'valor_unitario' => 'required|numeric|min:0',
        ];
    }
}
