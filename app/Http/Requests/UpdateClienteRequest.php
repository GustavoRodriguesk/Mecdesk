<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'     => 'required|string|max:100',
            'telefone' => 'required|string',
            'email'    => 'nullable|email|max:100',
            'cpf_cnpj' => ['nullable', new \App\Rules\CpfCnpjRule],
        ];
    }
}
